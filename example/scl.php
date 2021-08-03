<?php
include_once('../vendor/autoload.php');
use \ZxFiles\Disk\Scl;

$disk = new Scl();
$content = file_get_contents('test.scl');
$disk->setBinary($content);
foreach ($disk->getFiles() as $file) {
    echo $file->getFullName() . '<br/>';
    file_put_contents($file->getFullName(), $file->getContents());
}