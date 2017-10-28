<?php
include_once('../src/ByteParser.php');
include_once('../src/Disk/Disk.php');
include_once('../src/Disk/File.php');
include_once('../src/Disk/Scl.php');

$disk = new ZxFiles\Disk\Scl();

$content = file_get_contents('test.scl');

$disk->setBinary($content);
foreach ($disk->getFiles() as $file) {
    echo $file->getFullName() . '<br/>';
}