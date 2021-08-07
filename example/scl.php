<?php

use ZxFiles\Disk\Scl;

include_once('../vendor/autoload.php');
$scl = new Scl();
$scl->setBinary(file_get_contents('../tests/bin/sample1.scl'));
foreach ($scl->getFiles() as $file) {
    file_put_contents($file->getFullName(), $file->getContents());
}