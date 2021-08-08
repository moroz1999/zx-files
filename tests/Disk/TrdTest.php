<?php

namespace Disk;

use ZxFiles\Disk\Trd;
use PHPUnit\Framework\TestCase;

class TrdTest extends TestCase
{
    public function testParsesSampleTrd(): Trd
    {
        $trd = new Trd();
        $trd->setBinary(file_get_contents(realpath(__DIR__ . '\..\..\samples\size_matters_by_insiders.trd')));
        $files = $trd->getFiles();
        $this->assertIsArray($files);
        $this->assertCount(8, $files);

        return $trd;
    }

    /**
     * @depends      testParsesSampleTrd
     * @dataProvider SampleFileInfoProvider
     */
    public function testFile(int $index, string $md5, string $fileName, int $size, Trd $trd): void
    {
        $files = $trd->getFiles();
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
                'md5' => '6366804e118112cef73a7cd3ebd72ea0',
                'fileName' => 'EFFECT.C',
                'size' => 24064,
            ],
            [
                'index' => 1,
                'md5' => '524b61cd4c8fcf3935bd16b2e7baed3b',
                'fileName' => 'PAGE0.C',
                'size' => 7346,
            ],
            [
                'index' => 2,
                'md5' => 'dc0e8e18b986ea62ebe24950d073d95c',
                'fileName' => 'PAGE1.C',
                'size' => 16384,
            ],
            [
                'index' => 3,
                'md5' => '9fab2ff468ce30e8e1ba40893a30fd64',
                'fileName' => 'PAGE2.C',
                'size' => 16384,
            ],
            [
                'index' => 4,
                'md5' => 'bb7a816c471898ac8e2b65588f25ee24',
                'fileName' => 'PAGE7.C',
                'size' => 8738,
            ],
            [
                'index' => 5,
                'md5' => '996f38d4b7211370eb4283675e278b31',
                'fileName' => 'PAGE4.C',
                'size' => 14132,
            ],
            [
                'index' => 6,
                'md5' => '1f865055d8b75a10be9b2b658984051a',
                'fileName' => 'PAGE6.C',
                'size' => 14034,
            ],
            [
                'index' => 7,
                'md5' => '61cd05252af0ea9f43dad5a68f77e457',
                'fileName' => 'PREVIEW.B',
                'size' => 429,
            ],
        ];
    }
}
