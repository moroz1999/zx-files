<?php
namespace ZxFiles\Disk;

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
}
