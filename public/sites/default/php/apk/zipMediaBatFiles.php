<?php
myRequireOnce('dirListRecursive.php');
myRequireOnce('writeLog.php');
myRequireOnce('getBuild.php');

function zipMediaBatFiles($p){
     $dir = ROOT_EDIT. 'sites/'. SITE_CODE .'/apk/'. $p['country_code'] .'/';
    // FROM https://stackoverflow.com/questions/1754352/download-multiple-files-as-a-zip-file-using-php
    $files = zipMediaBatFilesGet($p);
    $zipname = 'MediaBatFiles' . getBuild($p) .'.zip';
    $zip = new ZipArchive;
    $zip->open($zipname, ZipArchive::CREATE);
    foreach ($files as $file) {
        writeLogAppend ('zipMediaBatFiles', $file);
        //$zip->addFile($file);
        $filename =str_replace ($dir, '', $file);
        $zip->addFromString($filename ,  file_get_contents($file));
    }
    $zipname = 'sites/default/' . $zipname;
    $zip->close();

    return ($zipname);
}
//define("ROOT_EDIT", ROOT . 'edit.mc2.online/');
function zipMediaBatFilesGet($p){
    $output = [];
    $dir = ROOT_EDIT. 'sites/'. SITE_CODE .'/apk/'. $p['country_code'] .'/' .$p['language_iso'];
    $files = dirListRecursive($dir);
   return $files;
}