<?php

namespace ZxFiles\Tape;

class Block
{
    const TYPE_HEADER = 0;
    const TYPE_DATA = 1;
    const TYPE_FRAGMENT = 2;

    public function __construct(
        public int     $type,
        public Tap     $tape,
        public int     $dataLength,
        public int     $offset,
        public ?string $checksum = null
    )
    {

    }
}