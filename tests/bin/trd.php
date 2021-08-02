<?php
include_once('../src/ByteParser.php');
include_once('../src/Disk/Disk.php');
include_once('../src/Disk/File.php');
include_once('../src/Disk/Trd.php');

$disk = new \ZxFiles\Disk\Trd();

$content = file_get_contents('test.trd');

$disk->setBinary($content);
foreach ($disk->getFiles() as $file) {
    echo $file->getFullName() .'<br/>';
}