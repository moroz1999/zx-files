<?php

use ZxFiles\Disk\Trd;

include_once('../vendor/autoload.php');
$trd = new Trd();
$trd->setBinary(file_get_contents('../tests/bin/size_matters_by_insiders.trd'));
foreach ($trd->getFiles() as $file) {
    file_put_contents($file->getFullName(), $file->getContents());
}