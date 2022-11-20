<?php

namespace ZxFiles\Tape;

class Block
{
    const HEADER_LENGTH = 17;
    const TYPE_HEADER = 0;
    const TYPE_DATA = 255;

    public function __construct(
        public ?int    $type,
        public Tape    $tape,
        public int     $blockStartOffset,
        public int     $blockSize,
        public int     $dataLength,
        public int     $dataStartOffset,
        public ?string $checksum = null
    )
    {
        if ($this->type !== self::TYPE_HEADER && $this->type !== self::TYPE_DATA) {
//            throw new \Exception('Invalid block type ' . $this->type);
        }
    }
}