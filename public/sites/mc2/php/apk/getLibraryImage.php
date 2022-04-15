<?php

myRequireOnce('writeLog.php');

function getLibraryImage($p, $text){
    writeLogAppend('getLibraryImage-6', $p);
    if($p['library_code'] == 'library' ){
        $library_image =   '/sites/mc2/images/menu/header-front.png';
    }
    else{
        $library_image =   '/content/M2/images/standard/MC2-Header.png';
    }

    return  $library_image;

}