<?php

namespace Tape;

use ZxFiles\Tape\Tap;
use PHPUnit\Framework\TestCase;

class TapTest extends TestCase
{
    public function testParsesSampleTap(): Tap
    {
        $tap = new Tap();
        $tap->setBinary(file_get_contents(realpath(__DIR__ . '\..\..\samples\AIRWOLF1.TAP')));
        $files = $tap->getFiles();
        $this->assertIsArray($files);
        $this->assertCount(5, $files);

        return $tap;
    }

    /**
     * @depends      testParsesSampleTap
     * @dataProvider SampleFileInfoProvider
     */
    public function testFile(int $index, string $md5, string $fileName, int $size, Tap $tap): void
    {
        $files = $tap->getFiles();
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
                'md5' => '12ad0292f9d8e97167cf3e755e08b79c',
                'fileName' => 'Airwolf.B',
                'size' => 104,
            ],
            [
                'index' => 1,
                'md5' => '9c793b19d691fadc5edf1518f8735fcf',
                'fileName' => 'Dilk.C',
                'size' => 59,
            ],
            [
                'index' => 2,
                'md5' => 'f850bca629d61012d86d422af9249481',
                'fileName' => 'data01',
                'size' => 6912,
            ],
            [
                'index' => 3,
                'md5' => '000be0aadcf35292733e4bde06c3f1a5',
                'fileName' => 'data02',
                'size' => 41535,
            ],
            [
                'index' => 4,
                'md5' => '74d7df2e5331b946a58bbca2f4276c0d',
                'fileName' => 'data03',
                'size' => 256,
            ],
        ];
    }
}