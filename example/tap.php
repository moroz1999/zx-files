<?php

use ZxFiles\Tape\Tap;

include_once('../vendor/autoload.php');
$dir = 'extracted';
if (!is_dir($dir)) {
    mkdir($dir);
}
$tap = new Tap();
$tap->setBinary(file_get_contents('../samples/AIRWOLF1.TAP'));
foreach ($tap->getFiles() as $file) {
    file_put_contents($dir . '/' . $file->getFullName(), $file->getContents());
}