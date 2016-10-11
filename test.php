<?php
include_once('src/Zx/Disk/Trd.php');
$disk = new Zx\Disk\Trd();
$content = file_get_contents('depth2.trd');
$disk->setBinary($content);
//mkdir('1');
foreach ($disk->getFiles() as $file) {
    echo $file->getFullName() .'<br/>';
//    file_put_contents('1/'.$file->getFullName(), $file->getContents());
}