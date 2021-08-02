<?php
include_once('../src/ByteParser.php');
include_once('../src/BasicFile.php');

if ($binary = file_get_contents('example.b')) {
    $basicFile = new \ZxFiles\BasicFile();
    $basicFile->setBinary($binary);
    echo '<pre>' . $basicFile->getAsText() . '</pre>';
    echo $basicFile->getAsHtml();
}