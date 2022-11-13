<?php

use ZxFiles\Disk\Trd;

include_once('../vendor/autoload.php');
$dir = 'extracted';
if (!is_dir($dir)) {
    mkdir($dir);
}
$trd = new Trd();
$trd->setBinary(file_get_contents('../samples/size_matters_by_insiders.trd'));
foreach ($trd->getFiles() as $file) {
    file_put_contents($dir . '/' . $file->getFullName(), $file->getContents());
}