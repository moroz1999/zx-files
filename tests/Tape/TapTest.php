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
                'size' => 100,
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

    public function testParsesSampleTap2(): Tap
    {
        $tap = new Tap();
        $tap->setBinary(file_get_contents(realpath(__DIR__ . '\..\..\samples\Dragon\'s Lair 2 (Erbe).tap')));
        $files = $tap->getFiles();
        $this->assertIsArray($files);
        $this->assertCount(18, $files);

        return $tap;
    }


    /**
     * @depends      testParsesSampleTap2
     * @dataProvider SampleFileInfoProvider2
     */
    public function testFile2(int $index, string $md5, string $fileName, int $size, Tap $tap): void
    {
        $files = $tap->getFiles();
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
                'md5' => '3fa176d602982a4e0d81d303f449cafc',
                'fileName' => 'ESCAPE.B',
                'size' => 425,
            ],
            [
                'index' => 1,
                'md5' => 'e7ec3d280ee222fd2a273044eaa03767',
                'fileName' => '$.C',
                'size' => 6912,
            ],
            [
                'index' => 2,
                'md5' => '9e688c58a5487b8eaf69c9e1005ad0bf',
                'fileName' => 'data01',
                'size' => 1,
            ],
            [
                'index' => 3,
                'md5' => 'ac69577e1189586a7c9e3b099dbfb5c1',
                'fileName' => 'data02',
                'size' => 19200,
            ],
            [
                'index' => 4,
                'md5' => '25710ef2a64cb7b55db56279603afb3d',
                'fileName' => 'data03',
                'size' => 3,
            ],
            [
                'index' => 5,
                'md5' => '2d38cbd47d448556c247b878d9cf61b5',
                'fileName' => 'data04',
                'size' => 5382,
            ],
            [
                'index' => 6,
                'md5' => '653232d925c78be03b63a1baa265b95b',
                'fileName' => 'data05',
                'size' => 3,
            ],
            [
                'index' => 7,
                'md5' => '86c108d2d044684e48c799f39188eb56',
                'fileName' => 'data06',
                'size' => 15360,
            ],
            [
                'index' => 8,
                'md5' => '9290415a55df93ecd1e0f3765313cc4e',
                'fileName' => 'data07',
                'size' => 3,
            ],
            [
                'index' => 9,
                'md5' => '51e4d641e547269aa68f80c2e9ed9492',
                'fileName' => 'data08',
                'size' => 9000,
            ],
            [
                'index' => 10,
                'md5' => '41706984b00319c3a044dafd6507d5fc',
                'fileName' => 'data09',
                'size' => 3,
            ],
            [
                'index' => 11,
                'md5' => 'cd2acd41e19049d8a90492c6ff67fd7a',
                'fileName' => 'data10',
                'size' => 9210,
            ],
            [
                'index' => 12,
                'md5' => '01e35b55cf560f49380912055c650b81',
                'fileName' => 'data11',
                'size' => 3,
            ],
            [
                'index' => 13,
                'md5' => '072a272a3bbae53ac4d7e61654899b86',
                'fileName' => 'data12',
                'size' => 13810,
            ],
            [
                'index' => 14,
                'md5' => 'ee791f5726978694f280e1100df6d9b3',
                'fileName' => 'data13',
                'size' => 3,
            ],
            [
                'index' => 15,
                'md5' => 'ecce473eda9d2d6bba1f4b65089f420a',
                'fileName' => 'data14',
                'size' => 6930,
            ],
            [
                'index' => 16,
                'md5' => '27f61d059e8f7c400ae29fbd2f74ebae',
                'fileName' => 'data15',
                'size' => 3,
            ],
            [
                'index' => 17,
                'md5' => 'a9e3d837f34c49370c146ad9a2f2ab8c',
                'fileName' => 'data16',
                'size' => 10320,
            ],

        ];
    }


}