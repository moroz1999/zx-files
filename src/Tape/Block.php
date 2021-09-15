<?php

namespace ZxFiles\Tape;

class Block
{
    const TYPE_HEADER = 0;
    const TYPE_DATA = 255;

    public function __construct(
        public ?int    $type,
        public Tap     $tape,
        public int     $blockStartOffset,
        public int     $blockSize,
        public int     $dataLength,
        public int     $dataStartOffset,
        public ?string $checksum = null
    )
    {

    }
}