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

    /**
     * @var Tap
     */
    protected $tapeImage;
    protected $name;
    protected $extension;

    protected $autoStartLine;
    protected $variableAreaStart;

    protected $codeStart;
    protected $codeLength;

    protected $contentOffset;
    protected $dataLength;
    protected $type; //0,1,2 or 3 for a Program, Number array, Character array or Code file

    public function __construct($tapeImage)
    {
        $this->tapeImage = $tapeImage;
    }

    public function setFileHeader($fileHeader)
    {
        if (strlen($fileHeader) == 17) {
            $this->type = $this->parseByte($fileHeader, 0);
            if ($this->type == self::PROGRAM) {
                $this->extension = 'B';
            } else {
                $this->extension = 'C';
            }
            $this->name = trim(substr($fileHeader, 1, 10));
            $this->dataLength = $this->parseWord($fileHeader, 11);
            if ($this->type == self::PROGRAM) {
                $this->autoStartLine = $this->parseWord($fileHeader, 13);
                $this->variableAreaStart = $this->parseWord($fileHeader, 15);
            } elseif ($this->type == self::CODE) {
                $this->codeStart = $this->parseWord($fileHeader, 13);
            } else {
                //                todo: find examples of other files
            }

            return true;
        }
        return true;
    }

    /**
     * @param mixed $dataLength
     */
    public function setDataLength($dataLength)
    {
        $this->dataLength = $dataLength;
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
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
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

    public function setContentOffset($contentOffset)
    {
        $this->contentOffset = $contentOffset;
    }

    //
    //    /**
    //     * @return mixed
    //     */
    //    public function getProgramVarsLength()
    //    {
    //        return $this->programVarsLength;
    //    }
    //
    //    /**
    //     * @return mixed
    //     */
    //    public function getProgramLength()
    //    {
    //        return $this->programLength;
    //    }
    //
    //    /**
    //     * @return mixed
    //     */
    //    public function getDataArrayLength()
    //    {
    //        return $this->dataArrayLength;
    //    }
    //
    //    /**
    //     * @return mixed
    //     */
    //    public function getPrintNumber()
    //    {
    //        return $this->printNumber;
    //    }
    //
    //    /**
    //     * @return mixed
    //     */
    //    public function getPrintLength()
    //    {
    //        return $this->printLength;
    //    }
    //
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
        return $this->codeLength;
    }

    public function getContents()
    {
        return $this->tapeImage->getData($this->contentOffset, $this->dataLength);
    }
}