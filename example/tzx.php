<?php

use ZxFiles\Tape\Tzx;

include_once('../vendor/autoload.php');
$dir = 'extracted';
if (!is_dir($dir)) {
    mkdir($dir);
}
$tzx = new Tzx();
$tzx->setBinary(file_get_contents('../samples/AticAtac.tzx'));
foreach ($tzx->getFiles() as $file) {
//    echo     $file->getFullName().' '.$file->getDataLength() .'<br>';

    file_put_contents($dir . '/' . $file->getFullName(), $file->getContents());
}