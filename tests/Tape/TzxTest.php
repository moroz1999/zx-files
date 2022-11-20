<?php

namespace Tape;

use ZxFiles\Tape\Tzx;
use PHPUnit\Framework\TestCase;

class TzxTest extends TestCase
{
    public function testParsesSampleTzx(): Tzx
    {
        $tzx = new Tzx();
        $tzx->setBinary(file_get_contents(realpath(__DIR__ . '\..\..\samples\Elite - 48k.tzx')));
        $files = $tzx->getFiles();
        $this->assertIsArray($files);
        $this->assertCount(30, $files);

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
                'md5' => 'a34c49094d5539e6c77b240e67703571',
                'fileName' => 'E L I T E.B',
                'size' => 387,
            ],
            [
                'index' => 1,
                'md5' => '2f54e3f82a8b9ae253bd692195f29467',
                'fileName' => 'data01',
                'size' => 7168,
            ],
            [
                'index' => 2,
                'md5' => '1cd48ec096771dafcb93cb8f7fa748bc',
                'fileName' => 'data02',
                'size' => 2,
            ],
            [
                'index' => 3,
                'md5' => '1f59669b5708cfc7264e2958bc2ae505',
                'fileName' => 'data03',
                'size' => 6231,
            ],
            [
                'index' => 4,
                'md5' => 'ae7c1df300139294f6723a6e990bdd2f',
                'fileName' => 'data04',
                'size' => 2,
            ],
            [
                'index' => 5,
                'md5' => '76b9306c3b45d22493fe3662720062aa',
                'fileName' => 'data05',
                'size' => 1999,
            ],
            [
                'index' => 6,
                'md5' => 'ab0276867be4c3e7884f4f3991d7f447',
                'fileName' => 'data06',
                'size' => 2,
            ],
            [
                'index' => 7,
                'md5' => '685fa71c2a5da989cb57c7150484805a',
                'fileName' => 'data07',
                'size' => 399,
            ],
            [
                'index' => 8,
                'md5' => '99a6fbdec944809ca80fb663a6d3be40',
                'fileName' => 'data08',
                'size' => 2,
            ],
            [
                'index' => 9,
                'md5' => 'da844cd1cef15836d22d6bb09e974d2d',
                'fileName' => 'data09',
                'size' => 99,
            ],
            [
                'index' => 10,
                'md5' => '74d6ca2a21069b2f9aaa6bf112bbd0eb',
                'fileName' => 'data10',
                'size' => 2,
            ],
            [
                'index' => 11,
                'md5' => 'a4eff5033e2f9b7a0a70c85497e03d14',
                'fileName' => 'data11',
                'size' => 47,
            ],
            [
                'index' => 12,
                'md5' => '18ba379108cd7ccc2fa0fd754ad45a25',
                'fileName' => 'data12',
                'size' => 2,
            ],
            [
                'index' => 13,
                'md5' => '303a709723da6402c807a8d2c64eedf6',
                'fileName' => 'data13',
                'size' => 3,
            ],
            [
                'index' => 14,
                'md5' => 'fbdce43e7e49aa6b7e324efa3b8776bc',
                'fileName' => 'data14',
                'size' => 16383,
            ],
            [
                'index' => 15,
                'md5' => '982569213f522d8fce898806d0a2c357',
                'fileName' => 'data15',
                'size' => 2,
            ],
            [
                'index' => 16,
                'md5' => 'f23206ac61558fc3b5a2122865ae0770',
                'fileName' => 'data16',
                'size' => 2,
            ],
            [
                'index' => 17,
                'md5' => '65a44af7ed801e1d803164590697fdf2',
                'fileName' => 'data17',
                'size' => 255,
            ],
            [
                'index' => 18,
                'md5' => '1228fb5d6bc1999b9037c89dffc0a29a',
                'fileName' => 'data18',
                'size' => 2,
            ],
            [
                'index' => 19,
                'md5' => '3855a48bda492173d92ea60e14af2730',
                'fileName' => 'data19',
                'size' => 799,
            ],
            [
                'index' => 20,
                'md5' => 'b27a17d547b656287fefce95da2dda0b',
                'fileName' => 'data20',
                'size' => 2,
            ],
            [
                'index' => 21,
                'md5' => '43cab2065067fd525fbb98fc1aa8153c',
                'fileName' => 'data21',
                'size' => 999,
            ],
            [
                'index' => 22,
                'md5' => '7209a1ce16f85bd1cbd287134ff5cbb6',
                'fileName' => 'data22',
                'size' => 2,
            ],
            [
                'index' => 23,
                'md5' => '68548fe101a47f956dad4b6bc2eb91f6',
                'fileName' => 'data23',
                'size' => 16383,
            ],
            [
                'index' => 24,
                'md5' => '18ba379108cd7ccc2fa0fd754ad45a25',
                'fileName' => 'data24',
                'size' => 2,
            ],
            [
                'index' => 25,
                'md5' => 'dc103296b37ac860fde65cc3f7e342bc',
                'fileName' => 'data25',
                'size' => 3,
            ],
            [
                'index' => 26,
                'md5' => 'b35b6f2227f8689a6f0ace6c15bc46f6',
                'fileName' => 'data26',
                'size' => 91,
            ],
            [
                'index' => 27,
                'md5' => 'a23ea484867b582af9ebc73e5703c530',
                'fileName' => 'data27',
                'size' => 2,
            ],
            [
                'index' => 28,
                'md5' => 'c9f69441b19da91023d0aff4a102925e',
                'fileName' => 'data28',
                'size' => 12014,
            ],
            [
                'index' => 29,
                'md5' => 'cd862166d711a9c79763caef5232adb1',
                'fileName' => 'data29',
                'size' => 1352,
            ],
        ];
    }

    public function testParsesSampleTzx2(): Tzx
    {
        $tzx = new Tzx();
        $tzx->setBinary(file_get_contents(realpath(__DIR__ . '\..\..\samples\Kokotoni Wilf.tzx')));
        $files = $tzx->getFiles();
        $this->assertIsArray($files);
        $this->assertCount(5, $files);

        return $tzx;
    }


    /**
     * @depends      testParsesSampleTzx2
     * @dataProvider SampleFileInfoProvider2
     */
    public function testFile2(int $index, string $md5, string $fileName, int $size, Tzx $tzx): void
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
}