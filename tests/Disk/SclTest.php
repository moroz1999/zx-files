<?php

namespace Disk;

use ZxFiles\Disk\Scl;
use PHPUnit\Framework\TestCase;

class SclTest extends TestCase
{
    public function testParsesSample1Scl(): Scl
    {
        $scl = new Scl();
        $scl->setBinary(file_get_contents(__DIR__ . '\..\bin\sample1.scl'));
        $files = $scl->getFiles();
        $this->assertIsArray($files);
        $this->assertEquals(3, count($files));

        return $scl;
    }

    /**
     * @depends      testParsesSample1Scl
     * @dataProvider referenceFileInfoProvider
     */
    public function testFile(int $index, string $md5, string $fileName, int $size, Scl $scl): void
    {
        $files = $scl->getFiles();
        $this->assertIsObject($files[$index]);
        $this->assertEquals($md5, md5($files[$index]->getContents()));
        $this->assertEquals($fileName, $files[$index]->getFullName());
        $this->assertEquals($size, $files[$index]->getLength());
    }

    public function referenceFileInfoProvider(): array
    {
        return [
            [
                'index' => 0,
                'md5' => '5261a7cc722b1bd45f2c4ddb96446785',
                'fileName' => 'boot.B',
                'size' => 20189,
            ],
            [
                'index' => 1,
                'md5' => 'ab28f07c4dd35ac645747505734ad8a4',
                'fileName' => 'data.C',
                'size' => 17152,
            ],
            [
                'index' => 2,
                'md5' => '3047e67e70a828834a3ef2eac3ccdeab',
                'fileName' => 'splash.C',
                'size' => 6912,
            ],
        ];
    }
}