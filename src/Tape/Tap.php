<?php

namespace ZxFiles\Tape;

use ZxFiles;

class Tap
{
    use ZxFiles\ByteParser;

    //    const FILE_HEADER_LENGTH = 24;
    const PROGRAM = 0;
    const NUM_ARRAY = 1;
    const CHAR_ARRAY = 2;
    const CODE = 3;

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

            $pointer++;
            if ($blockSize >= 2) {
                $dataLength = $blockSize - 2;

                if ($dataLength > 0 && $blockType == 0) {
                    if ($fileHeader = substr($this->binary, $pointer, $dataLength)) {
                        $type = $this->parseByte($fileHeader, 0);

                        $name = trim(substr($fileHeader, 1, 10));
                        $dataLength = $this->parseWord($fileHeader, 11);
                        $autoStartLine = null;
                        $variableAreaStart = null;
                        $codeStart = null;
                        if ($type == self::PROGRAM) {
                            $autoStartLine = $this->parseWord($fileHeader, 13);
                            $variableAreaStart = $this->parseWord($fileHeader, 15);
                        } elseif ($type == self::CODE) {
                            $codeStart = $this->parseWord($fileHeader, 13);
                        }
                        $file = new File($this, $type, $name, $dataLength, $autoStartLine, $variableAreaStart, $codeStart);
                        $this->files[] = $file;
                    }
                    $pointer += $dataLength;
                    $checksum = $this->parseByte($this->binary, $pointer);
                    $pointer++;
                } else {
                    if (!$file) {
                        $file = new File($this, self::CODE);
                        $this->files[] = $file;
                        $file->setName('data' . sprintf('%02d', $dataFilesAmount));
                        $dataFilesAmount++;
                    }
                    $file->setContentOffset($pointer);
                    $file->setDataLength($dataLength);

                    $pointer += $dataLength;

                    $checksum = $this->parseByte($this->binary, $pointer);
                    $pointer++;

                    $file = null;
                }
            } else {
                $file = new File($this);
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
