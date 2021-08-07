<?php

namespace Disk;

use ZxFiles\Disk\Trd;
use PHPUnit\Framework\TestCase;

class TrdTest extends TestCase
{
    private ?Trd $trd;

    public function setUp(): void
    {
        parent::setUp();
        $this->trd = new Trd();
    }

    public function tearDown(): void
    {
        $this->trd = NULL;
    }
//    public function testParsesSample1Trd(): void
//    {
//        $this->trd->setBinary(file_get_contents(__DIR__ . '\..\bin\sample1\disk.trd'));
//        $files = $this->trd->getFiles();
//        $this->assertIsArray($files);
//    }
}
