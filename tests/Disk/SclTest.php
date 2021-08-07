<?php

namespace Disk;

use ZxFiles\Disk\Scl;
use PHPUnit\Framework\TestCase;

class SclTest extends TestCase
{
    public function testParsesSample1Scl(): Scl
    {
        $scl = new Scl();
        $scl->setBinary(file_get_contents(__DIR__ . '\..\bin\sample1\disk.scl'));
        $files = $scl->getFiles();
        $this->assertIsArray($files);

        return $scl;
    }

    /**
     * @depends      testParsesSample1Scl
     * @dataProvider referenceFileInfoProvider
     */
    public function testFile(string $md5, string $fileName, int $size, Scl $scl): void
    {
        $files = $scl->getFiles();
        $this->assertIsObject($files[0]);
        $this->assertEquals(md5($files[0]->getContents()), $md5);
        $this->assertEquals($files[0]->getFullName(), $fileName);
        $this->assertEquals($files[0]->getLength(), $size);
    }

    public function referenceFileInfoProvider(): array
    {
        return [
            [
                'index' => 0,
                'md5' => '5261a7cc722b1bd45f2c4ddb96446785',
                'fileName' => 'boot.b',
                'size' => 20189,
            ],
            [
                'index' => 1,
                'md5' => '',
                'fileName' => 'data.c',
                'size' => 17152,
            ],
            ['index' => 2,
                'md5' => '',
                'fileName' => 'splash.c',
                'size' => 6912,
            ],
        ];
    }

//    /**
//     * @depends testParsesSample1Scl/
//     */
//    public function testFile1(Scl $scl): void
//    {
//        $files = $scl->getFiles();
//        $this->assertIsObject($files[0]);
//        $this->assertEquals(md5($files[0]->getContents()), md5(file_get_contents(__DIR__ . '\..\bin\sample1\boot.b')));
//    }
//
//    /**
//     * @depends testParsesSample1Scl
//     */
//    public function testFile2(Scl $scl): void
//    {
//        $files = $scl->getFiles();
//        $this->assertIsObject($files[1]);
//        $this->assertEquals(md5($files[1]->getContents()), md5(file_get_contents(__DIR__ . '\..\bin\sample1\data.c')));
//    }
//
//    /**
//     * @depends testParsesSample1Scl
//     */
//    public function testFile3(Scl $scl): void
//    {
//        $files = $scl->getFiles();
//        $this->assertIsObject($files[2]);
//        $this->assertEquals(md5($files[2]->getContents()), md5(file_get_contents(__DIR__ . '\..\bin\sample1\splash.c')));
//    }
}