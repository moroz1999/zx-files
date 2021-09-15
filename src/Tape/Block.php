<?php

namespace ZxFiles\Tape;

class Block
{
    const TYPE_HEADER = 0;
    const TYPE_DATA = 255;
    const TYPE_FRAGMENT = 1;

    public function __construct(
        public int     $type,
        public Tap     $tape,
        public int     $dataLength,
        public int     $dataStartOffset,
        public ?string $checksum = null
    )
    {

    }
}