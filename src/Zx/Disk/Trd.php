<?php
namespace Zx\Disk;

trait ByteParser
{
    function parseByte($string, $offset)
    {
        return ord(substr($string, $offset, 1));
    }

    function parseWord($string, $offset)
    {
        return ord(substr($string, $offset, 1)) + ord(substr($string, $offset + 1, 1)) * 0x100;
    }
}

trait Disk
{
    use ByteParser;
    protected $binary;
    protected $files;

    protected $firstFreeSector;
    protected $firstFreeTrack;

    protected $diskType;
    protected $sides;
    protected $tracks;
    protected $filesNumber;
    protected $freeSectors;
    protected $deletedFiles;
    protected $label;

    protected function getFilesDataOffset()
    {
        return $this->filesDataOffset;
    }

    public function setBinary($binary)
    {
        if ($binary) {
            $this->binary = $binary;
            $this->parseBinary();
        }
    }

    public function getData($start = 0, $offset = null)
    {
        if ($offset === null) {
            return substr($this->binary, $start + $this->getFilesDataOffset());
        }
        return substr($this->binary, $start + $this->getFilesDataOffset(), $offset);
    }

    /**
     * @return mixed
     */
    public function getBinary()
    {
        return $this->binary;
    }

    /**
     * @return mixed
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @return mixed
     */
    public function getFirstFreeSector()
    {
        return $this->firstFreeSector;
    }

    /**
     * @return mixed
     */
    public function getFirstFreeTrack()
    {
        return $this->firstFreeTrack;
    }

    /**
     * @return mixed
     */
    public function getDiskType()
    {
        return $this->diskType;
    }

    /**
     * @return mixed
     */
    public function getSides()
    {
        return $this->sides;
    }

    /**
     * @return mixed
     */
    public function getTracks()
    {
        return $this->tracks;
    }

    /**
     * @return mixed
     */
    public function getFilesNumber()
    {
        return $this->filesNumber;
    }

    /**
     * @return mixed
     */
    public function getFreeSectors()
    {
        return $this->freeSectors;
    }

    /**
     * @return mixed
     */
    public function getDeletedFiles()
    {
        return $this->deletedFiles;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }
}

class Trd
{
    use Disk;
    protected $filesDataOffset = 0;
    const FILE_HEADER_ITEM_LENGTH = 14;

    protected function parseBinary()
    {
        $start = 0;
        while (($fileHeader = substr($this->binary, $start, self::FILE_HEADER_ITEM_LENGTH)) && (ord(substr($fileHeader, 0, 1)) !== 0)) {
            $start = $start + self::FILE_HEADER_ITEM_LENGTH;
            $file = new File($this);
            if ($file->setFileHeader($fileHeader)) {
                $this->files[] = $file;
            }
        }
        $this->firstFreeSector = $this->parseByte($this->binary, 2048 + 225);
        $this->firstFreeTrack = $this->parseByte($this->binary, 2048 + 226);
        $this->diskType = $this->parseByte($this->binary, 2048 + 227);
        if ($this->diskType == 22) {
            $this->sides = 2;
            $this->tracks = 80;
        } elseif ($this->diskType == 23) {
            $this->sides = 2;
            $this->tracks = 40;
        } elseif ($this->diskType == 24) {
            $this->sides = 1;
            $this->tracks = 80;
        } elseif ($this->diskType == 25) {
            $this->sides = 1;
            $this->tracks = 40;
        }
        $this->filesNumber = $this->parseByte($this->binary, 2048 + 228);
        $this->freeSectors = $this->parseWord($this->binary, 2048 + 229);
        $this->deletedFiles = $this->parseByte($this->binary, 2048 + 244);
        $this->label = substr($this->binary, 2048 + 245);
    }
}

class Scl
{
    use Disk;
    protected $filesDataOffset = 0;
    const FILE_HEADER_ITEM_LENGTH = 14;
    const SECTORS_IN_TRACK = 16;
    const SECTOR_LENGTH = 256;

    public function __construct()
    {
        $this->diskType = 22;
        $this->sides = 2;
        $this->tracks = 80;
        $this->label = '';
    }

    protected function parseBinary()
    {
        if (substr($this->binary, 0, 8) == 'SINCLAIR') {
            $filesNumber = $this->filesNumber = $this->parseByte($this->binary, 8);
            $this->filesDataOffset = $filesNumber * self::FILE_HEADER_ITEM_LENGTH - self::SECTORS_IN_TRACK * self::SECTOR_LENGTH;
            $start = 9;
            $this->firstFreeSector = 0;
            $this->firstFreeTrack = 1;
            while (($fileHeader = substr($this->binary, $start, self::FILE_HEADER_ITEM_LENGTH)) && ($filesNumber--)) {
                $start = $start + self::FILE_HEADER_ITEM_LENGTH;
                $file = new File($this);
                if ($file->setFileHeader($fileHeader)) {

                    $fileSectorsLength = $file->getSectorsLength();
                    $file->setSector($this->firstFreeSector);
                    $file->setTrack($this->firstFreeTrack);
                    $this->files[] = $file;

                    $tracks = floor($fileSectorsLength / self::SECTORS_IN_TRACK);
                    $sectors = $fileSectorsLength - $tracks * self::SECTORS_IN_TRACK;

                    $this->firstFreeSector += $sectors;
                    if ($this->firstFreeSector >= self::SECTORS_IN_TRACK) {
                        $this->firstFreeSector -= self::SECTORS_IN_TRACK;
                        $this->firstFreeTrack++;
                    }
                    $this->firstFreeTrack += $tracks;
                }
            }
            $this->freeSectors = $this->tracks * $this->sides - ($this->firstFreeTrack * self::SECTORS_IN_TRACK + $this->firstFreeSector);
        }
    }
}

class File
{
    use ByteParser;

    /**
     * @var Trd
     */
    protected $diskImage;
    protected $name;
    protected $extension;

    protected $programVarsLength;
    protected $programLength;

    protected $dataArrayLength;

    protected $printNumber;
    protected $printLength;

    protected $codeStart;
    protected $codeLength;

    protected $dataLength;
    protected $sectorsLength;
    protected $sector;
    protected $track;

    protected $deleted;

    const SECTOR_LENGTH = 256;
    const SECTORS_IN_TRACK = 16;

    public function __construct($diskImage)
    {
        $this->diskImage = $diskImage;
    }

    public function setFileHeader($fileHeader)
    {
        $headerLength = strlen($fileHeader);
        if ($headerLength == 16 || $headerLength == 14) {
            if ($this->parseByte($fileHeader, 0) == 1) {
                $this->deleted = true;
            } else {
                $this->deleted = false;
            }
            $this->name = trim(substr($fileHeader, 0, 8));
            $this->extension = substr($fileHeader, 8, 1);

            $this->sectorsLength = $this->parseByte($fileHeader, 13);
            if ($headerLength == 16) {
                $this->sector = $this->parseByte($fileHeader, 14);
                $this->track = $this->parseByte($fileHeader, 15);
            }
            if ($this->extension == 'B') {
                $this->programVarsLength = $this->parseWord($fileHeader, 9);
                $this->programLength = $this->parseWord($fileHeader, 11);
                $this->dataLength = $this->sectorsLength * self::SECTOR_LENGTH;
            } elseif ($this->extension == 'D') {
                $this->dataArrayLength = $this->parseWord($fileHeader, 11);
                $this->dataLength = $this->dataArrayLength;
            } elseif ($this->extension == '#') {
                $this->printNumber = $this->parseByte($fileHeader, 9);
                $this->printLength = max($this->parseWord($fileHeader, 11), 4096);
                $this->dataLength = $this->printLength;
            } else {
                $this->codeStart = $this->parseWord($fileHeader, 9);
                $this->codeLength = $this->parseWord($fileHeader, 11);
                if (ceil($this->codeLength / self::SECTOR_LENGTH) == $this->sectorsLength) {
                    $this->dataLength = $this->codeLength;
                } else {
                    $this->dataLength = $this->sectorsLength * self::SECTOR_LENGTH;
                }
            }

            return true;
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function getFullName()
    {
        return $this->name . '.' . $this->extension;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @return mixed
     */
    public function getDiskImage()
    {
        return $this->diskImage;
    }

    /**
     * @return mixed
     */
    public function getProgramVarsLength()
    {
        return $this->programVarsLength;
    }

    /**
     * @return mixed
     */
    public function getProgramLength()
    {
        return $this->programLength;
    }

    /**
     * @return mixed
     */
    public function getDataArrayLength()
    {
        return $this->dataArrayLength;
    }

    /**
     * @return mixed
     */
    public function getPrintNumber()
    {
        return $this->printNumber;
    }

    /**
     * @return mixed
     */
    public function getPrintLength()
    {
        return $this->printLength;
    }

    /**
     * @return mixed
     */
    public function getCodeStart()
    {
        return $this->codeStart;
    }

    /**
     * @return mixed
     */
    public function getCodeLength()
    {
        return $this->codeLength;
    }

    /**
     * @return mixed
     */
    public function getSectorsLength()
    {
        return $this->sectorsLength;
    }

    /**
     * @return mixed
     */
    public function getSector()
    {
        return $this->sector;
    }

    /**
     * @return mixed
     */
    public function getTrack()
    {
        return $this->track;
    }

    /**
     * @param mixed $sector
     */
    public function setSector($sector)
    {
        $this->sector = $sector;
    }

    /**
     * @param mixed $track
     */
    public function setTrack($track)
    {
        $this->track = $track;
    }

    /**
     * @return mixed
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    public function getContents()
    {
        return $this->diskImage->getData(($this->track * self::SECTORS_IN_TRACK + $this->sector) * self::SECTOR_LENGTH, $this->dataLength);
    }
}