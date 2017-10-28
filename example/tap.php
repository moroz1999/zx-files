<?php
include_once('../src/ByteParser.php');
include_once('../src/Tape/File.php');
include_once('../src/Tape/Tap.php');

$disk = new ZxFiles\Tape\Tap();

$content = file_get_contents('Rod-Land.tap');

$disk->setBinary($content);
foreach ($disk->getFiles() as $file) {
    echo $file->getFullName() . ' ' . $file->getDataLength() . '<br/>';
}