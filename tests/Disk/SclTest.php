<?php

namespace Disk;

use ZxFiles\Disk\Scl;
use PHPUnit\Framework\TestCase;

class SclTest extends TestCase
{
    private ?Scl $scl;

    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->scl = new Scl();
    }

    public function tearDown(): void
    {
        $this->scl = NULL;
    }

    public function testParsesSample1Scl(): void
    {
        $this->scl->setBinary(file_get_contents(__DIR__ . '\..\bin\sample1\disk.scl'));
        $files = $this->scl->getFiles();
        $this->assertIsArray($files);
    }

    public function testFile1(): void
    {
        $files = $this->scl->getFiles();
        $this->assertIsObject($files[0]);
        $this->assertEquals(md5($files[0]->getContents()), md5(file_get_contents(__DIR__ . '\..\bin\sample1\boot.b')));
    }

    public function testFile2(): void
    {
        $files = $this->scl->getFiles();
        $this->assertIsObject($files[1]);
        $this->assertEquals(md5($files[1]->getContents()), md5(file_get_contents(__DIR__ . '\..\bin\sample1\data.c')));
    }

    public function testFile3(): void
    {
        $files = $this->scl->getFiles();
        $this->assertIsObject($files[2]);
        $this->assertEquals(md5($files[2]->getContents()), md5(file_get_contents(__DIR__ . '\..\bin\sample1\splash.c')));
    }
}