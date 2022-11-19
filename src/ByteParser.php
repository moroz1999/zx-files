<?php

namespace ZxFiles;

trait ByteParser
{
    function parseByte($string, $offset)
    {
        return ord(substr($string, $offset, 1));
    }

    function parseWord($string, $offset)
    {
        return ord(substr($string, $offset, 1)) + ord(substr($string, $offset + 1, 1)) * 0x100;
    }

    function parseDWord($string, $offset)
    {
        return ord(substr($string, $offset, 1)) + ord(substr($string, $offset + 1, 1)) * 0x100 + ord(substr($string, $offset + 2, 1)) * 0x100 * 0x100 + ord(substr($string, $offset + 3, 1)) * 0x100 * 0x100 * 0x100;
    }

    function parseWordBigEndian($string, $offset)
    {
        return ord(substr($string, $offset + 1, 1)) + ord(substr($string, $offset, 1)) * 0x100;
    }
}
