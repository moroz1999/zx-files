<?php
include_once('../src/ByteParser.php');
include_once('../src/BasicFile.php');

if ($bin = file_get_contents('boot.b')) {
    $conv = new \ZxFiles\BasicFile();
    echo '<pre>' . $conv->getAsText($bin) . '</pre>';
}