<?php

namespace ZxFiles;

trait ByteParser
{
    private function parseChar($string, $offset)
    {
        return $offset >= 0 && $offset < strlen($string) ? ord($string[$offset]) : 0;
    }

    function parseByte($string, $offset)
    {
        return $this->parseChar($string, $offset);
    }

    function parseWord($string, $offset)
    {
        return $this->parseChar($string, $offset) + $this->parseChar($string, $offset + 1) * 0x100;
    }

    function parseDWord($string, $offset)
    {
        return $this->parseChar($string, $offset) + $this->parseChar($string, $offset + 1) * 0x100 + $this->parseChar($string, $offset + 2) * 0x100 * 0x100 + $this->parseChar($string, $offset + 3) * 0x100 * 0x100 * 0x100;
    }

    function parseWordBigEndian($string, $offset)
    {
        return $this->parseChar($string, $offset + 1) + $this->parseChar($string, $offset) * 0x100;
    }
}
