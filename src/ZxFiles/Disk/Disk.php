<?php
namespace ZxFiles\Disk;

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
