<?php

namespace ZxFiles\Tape;

use JetBrains\PhpStorm\Pure;
use ZxFiles;

class Tap
{
    use ZxFiles\ByteParser;

    //    const FILE_HEADER_LENGTH = 24;
    const PROGRAM = 0;
    const NUM_ARRAY = 1;
    const CHAR_ARRAY = 2;
    const CODE = 3;

    protected int $pointer = 0;
    protected string $binary = '';
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

    #[Pure] protected function getFileHeader(string $binary, $headerLength): ?FileHeader
    {
        if ($headerString = substr($binary, $this->pointer, $headerLength)) {
            $type = $this->parseByte($headerString, 0);

            $name = trim(substr($headerString, 1, 10));
            $dataLength = $this->parseWord($headerString, 11);
            $autoStartLine = 0;
            $variableAreaStart = 0;
            $codeStart = 0;
            if ($type == self::PROGRAM) {
                $autoStartLine = $this->parseWord($headerString, 13);
                $variableAreaStart = $this->parseWord($headerString, 15);
            } elseif ($type == self::CODE) {
                $codeStart = $this->parseWord($headerString, 13);
            }
            return new FileHeader(
                $type,
                $name,
                $dataLength,
                $autoStartLine,
                $variableAreaStart,
                $codeStart
            );
        }
        return null;
    }

    protected function parseBlock(string $binary): ?Block
    {
        if ($this->pointer < strlen($this->binary)) {
            $blockSize = $this->parseWord($binary, $this->pointer);
            $this->pointer += 2;
            if ($blockSize > 2) {
                $blockType = $this->parseByte($binary, $this->pointer);
                $this->pointer++;

                $dataStartOffset = $this->pointer;
                $this->pointer += $blockSize - 2;

                $checksum = $this->parseByte($this->binary, $this->pointer);
                $this->pointer++;

                return new Block($blockType, $this, $blockSize, $dataStartOffset, $checksum);
            } else {
                return new Block(Block::TYPE_FRAGMENT, $this, $blockSize, $this->pointer, '');
            }
        }
        return null;
    }

    protected function parseBinary()
    {
        $this->pointer = 0;
        $file = false;

        $dataFilesAmount = 1;

        while ($block = $this->parseBlock($this->binary)) {
            // valid block is bigger than 2
            if ($block->dataLength > 0 && $block->type == Block::TYPE_HEADER) {
                if ($fileHeader = $this->getFileHeader($this->binary, $block->dataLength)) {
                    $this->pointer += $block->dataLength;
                    if (($dataBlock = $this->parseBlock($this->binary)) && $dataBlock->type === Block::TYPE_DATA) {
                        $file = new File(
                            $this,
                            $this->pointer,
                            $fileHeader->type,
                            $fileHeader->name,
                            $fileHeader->dataLength,
                            $fileHeader->autoStartLine,
                            $fileHeader->variableAreaStart,
                            $fileHeader->codeStart
                        );
                        $this->files[] = $file;

                        $this->pointer += $block->dataLength;
                    }
                }
            } else {
                $file = new File(
                    $this,
                    $this->pointer,
                    File::CODE,
                    'fragment.C',
                    $block->dataLength,
                    0,
                    0,
                    0
                );
                $this->files[] = $file;

                $this->pointer += $block->dataLength;
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
