<?php

namespace ZxFiles\Tape;

use ZxFiles;

class Tap implements Tape
{
    use ZxFiles\ByteParser;
    use ZxFiles\Tape\TapParser;

    public function setBinary($binary)
    {
        if ($binary) {
            $this->binary = $binary;
            $this->offset = 0;
            $this->parseBinary(strlen($this->binary));
        }
    }

    protected function parseBinary($limit)
    {
        $blocks = [];
        while ($block = $this->parseBlock($this->binary, $limit)) {
            $blocks[] = $block;
        }
        $this->parseBlocks($blocks);
    }

    /**
     * @return mixed
     */
    public function getBinary()
    {
        return $this->binary;
    }

    /**
     * @return File[]
     */
    public function getFiles()
    {
        return $this->files;
    }
}
