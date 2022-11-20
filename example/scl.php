<?php

use ZxFiles\Disk\Scl;

include_once('../vendor/autoload.php');
$dir = 'extracted';
if (!is_dir($dir)) {
    mkdir($dir);
}
$scl = new Scl();
$scl->setBinary(file_get_contents('../samples/b2bf.scl'));
foreach ($scl->getFiles() as $file) {
    echo $file->getFullName() . ' ' . $file->getDataLength() . '<br>';
    file_put_contents($dir . '/' . $file->getFullName(), $file->getContents());
}