<?php

namespace ZxFiles\Tape;

use JetBrains\PhpStorm\Pure;

trait TapParser
{
    private int $offset = 0;
    private string $binary = '';
    private int $dataFilesAmount = 1;
    /**
     * @var File[]
     */
    private $files = [];

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

    protected function parseBlock(string $binary, $limit): ?Block
    {
        if ($this->offset < $limit) {
            $blockStartOffset = $this->offset;
            $blockSize = $this->parseWord($binary, $this->offset);
            $this->offset += 2;
            if ($blockSize <= 3) {
                //invalid length, fix it (?)
                $blockSize += 2;
            }
            if ($blockSize > 3) {
                $blockType = $this->parseByte($binary, $this->offset);
                if ($blockType === Block::TYPE_HEADER && ($blockSize - 2) !== Block::HEADER_LENGTH) {
                    $blockType = Block::TYPE_DATA;

                    if ($this->parseByte($binary, $this->offset + 1) === Block::TYPE_DATA) {
                        $this->offset++;
                    }
                }
                $this->offset++;

                $dataLength = $blockSize - 2;
                $dataStartOffset = $this->offset;
                $this->offset += $dataLength;

                $checksum = $this->parseByte($this->binary, $this->offset);
                $this->offset++;

                return new Block($blockType, $this, $blockStartOffset, $blockSize, $dataLength, $dataStartOffset, $checksum);
            } else {
                //invalid fragment
                //search for next non-zero byte to find next block
                $blockSize = 0;
                while ($this->parseByte($binary, $this->offset) === 0) {
                    $blockSize++;
                    $this->offset++;
                }

                $dataLength = $blockSize;
                return new Block(Block::TYPE_DATA, $this, $blockStartOffset, $blockSize, $dataLength, $this->offset, '');
            }
        }
        return null;
    }

    public function getData($start = 0, $bytes = null): string
    {
        if ($bytes === null) {
            return substr($this->binary, $start);
        }
        return substr($this->binary, $start, $bytes);
    }

    private function parseBlocks($blocks)
    {
        while ($block = array_shift($blocks)) {
            if ($block->dataLength === 17 && $block->type == Block::TYPE_HEADER) {
                if ($fileHeader = $this->getFileHeader($this->binary, $block->dataStartOffset, $block->dataLength)) {
                    if (($dataBlock = array_shift($blocks)) && $dataBlock->type !== Block::TYPE_HEADER) {
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
                $file = new File(
                    $this,
                    $block->dataStartOffset,
                    File::UNDEFINED,
                    'data' . sprintf('%02d', $this->dataFilesAmount++),
                    $block->dataLength,
                    0,
                    0,
                    0
                );
                $this->files[] = $file;
            }
        }
    }

}