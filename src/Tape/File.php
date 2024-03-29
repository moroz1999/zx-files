<?php

namespace ZxFiles\Tape;

use ZxFiles;

class File
{
    use ZxFiles\ByteParser;

    const PROGRAM = 0;
    const NUM_ARRAY = 1;
    const CHAR_ARRAY = 2;
    const CODE = 3;
    const UNDEFINED = null;

    public $extension;

    public function __construct(
        protected Tape   $tapeImage,
        protected int    $contentOffset,
        protected ?int   $type,
        protected string $name,
        protected int    $dataLength,
        protected ?int   $autoStartLine = null,
        protected ?int   $variableAreaStart = null,
        protected ?int   $codeStart = null
    )
    {
        if ($this->type === self::PROGRAM) {
            $this->extension = 'B';
        } elseif ($this->type === self::CODE) {
            $this->extension = 'C';
        } else {
            $this->extension = '';
        }
    }

    /**
     * @return mixed
     */
    public function getFullName()
    {
        if ($this->extension) {
            return $this->name . '.' . $this->extension;
        }
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @return mixed
     */
    public function getDataLength()
    {
        return $this->dataLength;
    }

    /**
     * @return mixed
     */
    public function getCodeStart()
    {
        return $this->codeStart;
    }

    /**
     * @return mixed
     */
    public function getCodeLength()
    {
        return $this->dataLength;
    }

    public function getContents()
    {
        return $this->tapeImage->getData($this->contentOffset, $this->dataLength);
    }
}