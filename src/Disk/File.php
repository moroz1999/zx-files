<?php
namespace ZxFiles\Disk;
use ZxFiles;

class File
{
    use ZxFiles\ByteParser;

    /**
     * @var Disk
     */
    protected $diskImage;
    protected $name;
    protected $extension;

    protected $programVarsLength;
    protected $programLength;

    protected $dataArrayLength;

    protected $codeStart;
    protected $codeLength;

    protected $printNumber;
    protected $printLength;

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