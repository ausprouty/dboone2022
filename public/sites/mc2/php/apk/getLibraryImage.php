<?php

myRequireOnce('writeLog.php');

function getLibraryImage($text){
    $library_image =   '/sites/mc2/images/menu/header-front.png';
    writeLogDebug('getLibraryImage-6', $library_image);
    return  $library_image;

}