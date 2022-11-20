<?php

use ZxFiles\Tape\Tzx;

include_once('../vendor/autoload.php');
$dir = 'extracted';
if (!is_dir($dir)) {
    mkdir($dir);
}
$tzx = new Tzx();
$tzx->setBinary(file_get_contents('../samples/Elite - 48k.tzx'));
foreach ($tzx->getFiles() as $file) {
    echo $file->getFullName() . ' ' . $file->getDataLength() . '<br>';
    file_put_contents($dir . '/' . $file->getFullName(), $file->getContents());
}

//echo "[\n" .
//    "'index'=>" . $i . ",\n" .
//    "'md5'=>'" . md5($file->getContents()) . "',\n" .
//    "'fileName'=>'" . $file->getFullName() . "',\n" .
//    "'size'=>" . $file->getDataLength() . ",\n" .
//
//    "],\n";