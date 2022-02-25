<?php
myRequireOnce ('dirList.php');
myRequireOnce ('dirMake.php');
function copyStandardFiles($p){
    $dirStandard = 'sites/' . SITE_CODE . '/images/standard/';
    $dirSource =  ROOT_EDIT . $dirCSS;
    $dirDesination = ROOT_SDCARD .$p['language_iso'].'/folder/'. $dirCSS;
    dirMake ($dirDestination);
    $files = dirList($dirCSS);
    foreach ($files as $file){
        $sourceFile = $dirSource . $file;
        $destinationFile =$dirDestination . $file;
        copy ($sourceFile, $destinationFile);
    }
}