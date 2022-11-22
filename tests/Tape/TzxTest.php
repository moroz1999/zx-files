<?php

namespace Tape;

use ZxFiles\Tape\Tzx;
use PHPUnit\Framework\TestCase;

class TzxTest extends TestCase
{
    public function testParsesSampleTzx(): Tzx
    {
        $tzx = new Tzx();
        $tzx->setBinary(file_get_contents(realpath(__DIR__ . '\..\..\samples\Kokotoni Wilf.tzx')));
        $files = $tzx->getFiles();
        $this->assertIsArray($files);
        $this->assertCount(5, $files);

        return $tzx;
    }


    /**
     * @depends      testParsesSampleTzx
     * @dataProvider SampleFileInfoProvider
     */
    public function testFile(int $index, string $md5, string $fileName, int $size, Tzx $tzx): void
    {
        $files = $tzx->getFiles();
        $this->assertIsObject($files[$index]);
        $this->assertEquals($md5, md5($files[$index]->getContents()));
        $this->assertEquals($fileName, $files[$index]->getFullName());
        $this->assertEquals($size, $files[$index]->getDataLength());
    }

    public function SampleFileInfoProvider(): array
    {
        return [
            [
                'index' => 0,
                'md5' => '3cd95ded2c81b29f74cc34525d96db81',
                'fileName' => 'kokotoni.B',
                'size' => 198,
            ],
            [
                'index' => 1,
                'md5' => '0bec52489de87e2c9e4f7de62c259ec7',
                'fileName' => 'load.C',
                'size' => 255,
            ],
            [
                'index' => 2,
                'md5' => 'e298d83914d1c65a6137efd414134e56',
                'fileName' => 'data01',
                'size' => 6912,
            ],
            [
                'index' => 3,
                'md5' => '394f46a66c6b88420cf0ec69f65c077c',
                'fileName' => 'data02',
                'size' => 39998,
            ],
            [
                'index' => 4,
                'md5' => 'f0b4c5ce35c62f33c4ce2f8cf2fec37c',
                'fileName' => 'TOP.C',
                'size' => 1435,
            ],
        ];
    }

    public function testParsesSampleTzx2(): Tzx
    {
        $tzx = new Tzx();
        $tzx->setBinary(file_get_contents(realpath(__DIR__ . '\..\..\samples\AticAtac.tzx')));
        $files = $tzx->getFiles();
        $this->assertIsArray($files);
        $this->assertCount(6, $files);

        return $tzx;
    }


    /**
     * @depends      testParsesSampleTzx2
     * @dataProvider SampleFileInfoProvider2
     */
    public function testFile3(int $index, string $md5, string $fileName, int $size, Tzx $tzx): void
    {
        $files = $tzx->getFiles();
        $this->assertIsObject($files[$index]);
        $this->assertEquals($md5, md5($files[$index]->getContents()));
        $this->assertEquals($fileName, $files[$index]->getFullName());
        $this->assertEquals($size, $files[$index]->getDataLength());
    }

    public function SampleFileInfoProvider2(): array
    {
        return [
            [
                'index' => 0,
                'md5' => '56eac715b75a9d40ef43569c9bb94ba0',
                'fileName' => 'ATIC.B',
                'size' => 388,
            ],
            [
                'index' => 1,
                'md5' => 'ae29b447e706bd270c7eaea8efb67891',
                'fileName' => 'ATSP.C',
                'size' => 6912,
            ],
            [
                'index' => 2,
                'md5' => '4256e098bfb3b2e41146b88419ef044f',
                'fileName' => '0.C',
                'size' => 30209,
            ],
            [
                'index' => 3,
                'md5' => '5805aec2e10898c3ac18c7f0aa41829f',
                'fileName' => '1.C',
                'size' => 18,
            ],
            [
                'index' => 4,
                'md5' => '3406877694691ddd1dfb0aca54681407',
                'fileName' => '2.C',
                'size' => 1,
            ],
            [
                'index' => 5,
                'md5' => 'cdf80b787ce31d16c58b311f7869be2f',
                'fileName' => '3.C',
                'size' => 2,
            ],
        ];
    }
}