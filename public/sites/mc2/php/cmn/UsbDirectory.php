<?php

    $dir = "M:/MC2/SDCARD2020";
    //header('Content-type: text/html; charset=utf-8');
    //echo nl2br("starting UBB\n");
    // listFolderFiles($dir);
    header('Content-type: text/html; charset=utf-8');
    $files = scandir($dir);
    echo "<pre>";
    print_r($files);
    echo "</pre>";



function listFolderFiles($dir){
    $ffs = scandir($dir);

    unset($ffs[array_search('.', $ffs, true)]);
    unset($ffs[array_search('..', $ffs, true)]);

    // prevent empty ordered elements
    if (count($ffs) < 1)
        return;

    echo '<ol>';
    foreach($ffs as $ff){
        echo '<li>'.$ff;
        if(is_dir($dir.'/'.$ff)) listFolderFiles($dir.'/'.$ff);
        echo '</li>';
    }
    echo '</ol>';
}
