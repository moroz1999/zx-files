<?php
include_once('../src/ByteParser.php');
include_once('../src/Tape/File.php');
include_once('../src/Tape/Tap.php');

$tape = new \ZxFiles\Tape\Tap();

$content = file_get_contents('test.tap');

$tape->setBinary($content);
foreach ($tape->getFiles() as $file) {
    echo $file->getFullName() . ' ' . $file->getDataLength() . '<br/>';
}