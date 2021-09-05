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

    const TYPE_HEADER = 0;

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

    protected function getFileHeader(string $binary, int $pointer, $headerLength): ?FileHeader
    {
        if ($headerString = substr($this->binary, $pointer, $headerLength)) {
            $type = $this->parseByte($headerString, 0);

            $name = trim(substr($headerString, 1, 10));
            $dataLength = $this->parseWord($headerString, 11);
            $autoStartLine = null;
            $variableAreaStart = null;
            $codeStart = null;
            if ($type == self::PROGRAM) {
                $autoStartLine = $this->parseWord($headerString, 13);
                $variableAreaStart = $this->parseWord($headerString, 15);
            } elseif ($type == self::CODE) {
                $codeStart = $this->parseWord($headerString, 13);
            }
            $fileHeader = new FileHeader(
                $type,
                $name,
                $dataLength,
                $autoStartLine,
                $variableAreaStart,
                $codeStart
            );
            return $fileHeader;
        }
        return null;
    }

    protected function parseBlock(string $binary, int $pointer)
    {
        if ($pointer < strlen($this->binary)) {
            $blockSize = $this->parseWord($binary, $pointer);
            $pointer += 2;
            if ($blockSize > 2){
                $blockType = $this->parseByte($binary, $pointer);
                $pointer++;

                $dataStartOffset = $pointer;
                $pointer += $blockSize - 2;

                $checksum = $this->parseByte($this->binary, $pointer);
                $pointer++;

                $block = new Block($blockType, $this, $dataStartOffset, $checksum);
                return $block;
            }
        }
    }

    protected function parseBinary()
    {
        $pointer = 0;
        $file = false;

        $dataFilesAmount = 1;

        while ($block = $this->parseBlock($this->binary, $pointer)) {
//        while ($pointer < $binaryLength) {
//            $blockSize = $this->parseWord($this->binary, $pointer);
//            $pointer += 2;
//
//            $blockType = $this->parseByte($this->binary, $pointer);
//            $pointer++;

            // valid block is bigger than 2
            if ($blockSize >= 2) {
                //block size includes checksum and blocktype
                $dataLength = $blockSize - 2;

                if ($dataLength > 0 && $blockType == self::TYPE_HEADER) {
                    if ($fileHeader = $this->getFileHeader($this->binary, $pointer, $dataLength)) {
                        $pointer += $dataLength;

                        $file = new File(
                            $this,
                            $pointer,
                            $fileHeader->type,
                            $fileHeader->name,
                            $fileHeader->dataLength,
                            $fileHeader->autoStartLine,
                            $fileHeader->variableAreaStart,
                            $fileHeader->codeStart
                        );
                        $this->files[] = $file;
                    }

                    $checksum = $this->parseByte($this->binary, $pointer);
                    $pointer++;
                } else {
                    if (!$file) {
                        $file = new File($this, self::CODE);
                        $this->files[] = $file;
                        $file->setName('data' . sprintf('%02d', $dataFilesAmount));
                        $dataFilesAmount++;
                    }

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
