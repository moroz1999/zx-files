<?php

namespace ZxFiles\Tape;

class Block
{
    public function __construct(
        public int     $type,
        public Tap     $tape,
        public int     $offset,
        public ?string $checksum = null
    )
    {

    }
}