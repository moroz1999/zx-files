<?php

namespace ZxFiles\Tape;

use JetBrains\PhpStorm\Pure;
use ZxFiles;

class Tap
{
    use ZxFiles\ByteParser;

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

    #[Pure] protected function getFileHeader(string $binary, $offset, $headerLength): ?FileHeader
    {
        if ($headerString = substr($binary, $offset, $headerLength)) {
            $type = $this->parseByte($headerString, 0);

            $name = trim(substr($headerString, 1, 10));
            $dataLength = $this->parseWord($headerString, 11);
            $autoStartLine = 0;
            $variableAreaStart = 0;
            $codeStart = 0;
            if ($type == File::PROGRAM) {
                $autoStartLine = $this->parseWord($headerString, 13);
                $variableAreaStart = $this->parseWord($headerString, 15);
            } elseif ($type == File::CODE) {
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

            $dataLength = $this->parseWord($binary, $this->pointer);
            $this->pointer += 2;
            if ($dataLength > 2) {
                $blockType = $this->parseByte($binary, $this->pointer);
                $this->pointer++;

                $dataStartOffset = $this->pointer;
                $this->pointer += $dataLength - 2;

                $checksum = $this->parseByte($this->binary, $this->pointer);
                $this->pointer++;

                return new Block($blockType, $this, $dataLength, $dataStartOffset, $checksum);
            } else {
                return new Block(Block::TYPE_FRAGMENT, $this, $dataLength, $this->pointer, '');
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
            if ($block->dataLength > 0 && $block->type == Block::TYPE_HEADER) {
                if ($fileHeader = $this->getFileHeader($this->binary, $block->dataStartOffset, $block->dataLength)) {
                    if (($dataBlock = $this->parseBlock($this->binary)) && $dataBlock->type === Block::TYPE_DATA) {
                        $file = new File(
                            $this,
                            $dataBlock->dataStartOffset,
                            $fileHeader->type,
                            $fileHeader->name,
                            $fileHeader->dataLength,
                            $fileHeader->autoStartLine,
                            $fileHeader->variableAreaStart,
                            $fileHeader->codeStart
                        );
                        $this->files[] = $file;
                    }
                }
            } else {
                if ($block->type === Block::TYPE_DATA) {
                    $file = new File(
                        $this,
                        $dataBlock->dataStartOffset,
                        File::CODE,
                        'data' . sprintf('%02d', $dataFilesAmount++),
                        $block->dataLength - 2,
                        0,
                        0,
                        0
                    );
                } elseif ($block->type === Block::TYPE_FRAGMENT) {
                    $file = new File(
                        $this,
                        $this->pointer,
                        File::CODE,
                        'fragment' . sprintf('%02d', $dataFilesAmount++),
                        $block->dataLength,
                        0,
                        0,
                        0
                    );
                }
                $this->files[] = $file;
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
