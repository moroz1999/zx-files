<?php
namespace ZxFiles\Disk;

class Trd extends Disk
{
    protected $filesDataOffset = 0;
    const FILE_HEADER_ITEM_LENGTH = 16;

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