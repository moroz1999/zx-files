<?php
namespace ZxFiles\Disk;

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
