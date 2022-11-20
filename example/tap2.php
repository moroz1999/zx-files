<?php

use ZxFiles\Tape\Tap;

include_once('../vendor/autoload.php');
$dir = 'extracted';
if (!is_dir($dir)) {
    mkdir($dir);
}
$tap = new Tap();
$tap->setBinary(file_get_contents('../samples/Dragon\'s Lair 2 (Erbe).tap'));
foreach ($tap->getFiles() as $file) {
    echo $file->getFullName() . ' ' . $file->getDataLength() . '<br>';
    file_put_contents($dir . '/' . $file->getFullName(), $file->getContents());
}