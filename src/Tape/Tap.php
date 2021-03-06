<?php
namespace ZxFiles\Tape;

use ZxFiles;

class Tap
{
    use ZxFiles\ByteParser;

    //    const FILE_HEADER_LENGTH = 24;

    protected $binary;
    /**
     * @var File[]
     */
    protected $files;

    public function setBinary($binary)
    {
        if ($binary) {
            $this->binary = $binary;
            $this->parseBinary();
        }
    }

    protected function parseBinary()
    {
        $pointer = 0;
        $file = false;

        $dataFilesAmount = 1;

        $binaryLength = strlen($this->binary);
        while ($pointer < $binaryLength) {
            $blockSize = $this->parseWord($this->binary, $pointer);
            $pointer += 2;

            $blockType = $this->parseByte($this->binary, $pointer);

            $pointer += 1;
            if ($blockSize >=2){
                $dataLength = $blockSize - 2;
            } else {
                $dataLength = 0;
            }
            if ($dataLength > 0 && $blockType == 0) {
                $file = new File($this);

                if ($fileHeader = substr($this->binary, $pointer, $dataLength)) {
                    if ($file->setFileHeader($fileHeader)) {
                        $this->files[] = $file;
                    }
                }
                $pointer += $dataLength;
                $checksum = $this->parseByte($this->binary, $pointer);
                $pointer += 1;
            } else {
                if (!$file) {
                    $file = new File($this);
                    $this->files[] = $file;
                    $file->setName('data'.sprintf('%02d', $dataFilesAmount));
                    $dataFilesAmount++;
                }
                $file->setContentOffset($pointer);
                $file->setDataLength($dataLength);

                $pointer += $dataLength;

                $checksum = $this->parseByte($this->binary, $pointer);
                $pointer += 1;

                $file = null;
            }
        }
    }

    public function getData($start = 0, $offset = null)
    {
        if ($offset === null) {
            return substr($this->binary, $start);
        }
        return substr($this->binary, $start, $offset);
    }

    /**
     * @return mixed
     */
    public function getBinary()
    {
        return $this->binary;
    }

    /**
     * @return File[]
     */
    public function getFiles()
    {
        return $this->files;
    }
}
