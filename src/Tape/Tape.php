<?php

namespace ZxFiles\Tape;

interface Tape
{
    public function getData($start = 0, $bytes = null): string;
}