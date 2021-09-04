<?php

namespace ZxFiles\Tape;

class FileHeader
{
    public function __construct(
        public int $type,
        public string $name,
        public int $dataLength,
        public int $autoStartLine,
        public int $variableAreaStart,
        public int $codeStart
    )
    {

    }
}