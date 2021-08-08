<?php

use ZxFiles\Tape\Tap;

include_once('../vendor/autoload.php');
$tap = new Tap();
$tap->setBinary(file_get_contents('../samples/AIRWOLF1.TAP'));
foreach ($tap->getFiles() as $file) {
    file_put_contents($file->getFullName(), $file->getContents());
}