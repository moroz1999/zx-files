<?php

namespace ZxFiles\Tape;

use ZxFiles;

class Tzx implements Tape
{
    use ZxFiles\ByteParser;
    use ZxFiles\Tape\TapParser;

    private int $majorVersion;
    private int $minorVersion;

    public function setBinary($binary)
    {
        if ($binary) {
            $this->binary = $binary;
            $this->parseBinary();
        }
    }

    private function parseTzxHeader()
    {
        if ($this->getData(0, 7) === "ZXTape!") {
            $this->offset = 8;
            $this->majorVersion = $this->parseByte($this->binary, $this->offset++);
            $this->minorVersion = $this->parseByte($this->binary, $this->offset++);
            return true;
        }
        return false;
    }

    private function parseBinary()
    {
        $this->offset = 0;
        $length = strlen($this->binary);
        $blocks = [];
        if ($this->parseTzxHeader()) {
            while (($this->offset < $length)) {
                $id = $this->parseByte($this->binary, $this->offset++);
                $block = false;
                switch ($id) {
                    case 0x10:
                        //ID 10 - Standard Speed Data Block
                        $this->offset += 0x02;
                        $block = $this->parseBlock($this->binary, $length);
                        break;
                    case 0x11:
                        //ID 11 - Turbo Speed Data Block
                        $this->offset += 0x0F;
                        $block = $this->parseBlock($this->binary, $length);
                        break;
                    case 0x12:
                        //ID 12 - Pure Tone
                        $this->offset += 0x04;
                        break;
                    case 0x13:
                        //ID 13 - Pulse sequence
                        $this->offset += $this->parseByte($this->binary, $this->offset) * 2 + 1;
                        break;
                    case 0x14:
                        //ID 14 - Pure Data Block
                        $this->offset += 0x07;
                        $block = $this->parseBlock($this->binary, $length);
                        break;
                    case 0x15:
                        //ID 15 - Direct Recording
                        break;
                    case 0x18:
                        //ID 18 - CSW Recording
                        $this->offset += $this->parseDWord($this->binary, $this->offset) + 4;
                        break;
                    case 0x19:
                        //ID 19 - Generalized Data Block
                        $this->offset += $this->parseDWord($this->binary, $this->offset) + 4;
                        break;
                    case 0x20:
                        //ID 20 - Pause (silence) or 'Stop the Tape' command
                        $this->offset += 0x02;
                        break;
                    case 0x21:
                        //ID 21 - Group start
                        $this->offset += $this->parseByte($this->binary, $this->offset) + 1;
                        break;
                    case 0x22:
                        //ID 22 - Group end
                        break;
                    case 0x23:
                        //ID 23 - Jump to block
                        $this->offset += $this->parseByte($this->binary, $this->offset) + 2;
                        break;
                    case 0x24:
                        //ID 24 - Loop start
                        $this->offset += $this->parseByte($this->binary, $this->offset) + 2;
                        break;
                    case 0x25:
                        //ID 25 - Loop end
                        break;
                    case 0x26:
                        //ID 26 - Call sequence
                        $this->offset += $this->parseWord($this->binary, $this->offset) * 2 + 2;
                        break;
                    case 0x27:
                        //ID 27 - Return from sequence
                        break;
                    case 0x28:
                        //ID 28 - Select block
                        $this->offset += $this->parseWord($this->binary, $this->offset) + 2;
                        break;
                    case 0x2a:
                        //ID 2A - Stop the tape if in 48K mode
                        $this->offset += 4;
                        break;
                    case 0x2b:
                        //ID 2B - Set signal level
                        $this->offset += 5;
                        break;
                    case 0x30:
                        //ID 30 - Text description
                        $this->offset += $this->parseByte($this->binary, $this->offset) + 1;
                        //todo: make text file from this?
                        throw new \Exception('TZX text description found' . $id);
                        break;
                    case 0x31:
                        //ID 31 - Message block
                        $this->offset += $this->parseByte($this->binary, $this->offset) + 2;
                        //todo: make text file from this?
                        throw new \Exception('TZX text message found' . $id);
                        break;
                    case 0x32:
                        //ID 32 - Archive info
                        $this->offset += $this->parseWord($this->binary, $this->offset) + 2;
                        break;
                    case 0x33:
                        //ID 33 - Hardware type
                        $this->offset += $this->parseByte($this->binary, $this->offset) * 3 + 1;
                        //todo: make text file from this?
                        throw new \Exception('TZX Hardware type found' . $id);
                        break;
                    case 0x35:
                        //ID 35 - Custom info block
                        $this->offset += 10;
                        $this->offset += $this->parseDWord($this->binary, $this->offset) + 4;
                        //todo: make text file from this?
                        throw new \Exception('TZX Custom info found' . $id);
                        break;
                    case 0x5a:
                        //ID 5A - "Glue" block
                        $this->offset += 9;
                        break;
                    default:
//                        throw new \Exception('Unknown TZX block type ' . $id);
                        break;
                }
                if ($block) {
                    $blocks[] = $block;
                }
            }
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
