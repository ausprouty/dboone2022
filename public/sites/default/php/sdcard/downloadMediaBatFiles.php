<?php
myRequireOnce('dirListRecursive.php');
myRequireOnce('writeLog.php');

function downloadMediaBatFiles($p){
     $dir = ROOT_EDIT. 'sites/'. SITE_CODE .'/sdcard/'. $p['country_code'] .'/';
    // FROM https://stackoverflow.com/questions/1754352/download-multiple-files-as-a-zip-file-using-php
    $files = downloadMediaBatFilesGet($p);
    $zipname = 'MediaBatFiles' . $p['sdcard_settings']->subDirectory .'.zip';
    $zip = new ZipArchive;
    $zip->open($zipname, ZipArchive::CREATE);
    foreach ($files as $file) {
        //$zip->addFile($file);
        $filename =str_replace ($dir, '', $file);
        $zip->addFromString($filename ,  file_get_contents($file));
    }
$zipname = 'php/default/' . $zipname;
    $zip->close();

    return ($zipname);
}
//define("ROOT_EDIT", ROOT . 'edit.mc2.online/');
function downloadMediaBatFilesGet($p){
    $output = [];
    $dir = ROOT_EDIT. 'sites/'. SITE_CODE .'/sdcard/'. $p['country_code'] .'/';
    $subDirectories = explode('.', $p['sdcard_settings']->subDirectory);
    writeLogDebug('downloadMediaBatFilesGet', $subDirectories);
    foreach ($subDirectories as $sub){
        if (strlen ($sub) > 1){ // because first one will be blank
            $check_dir =$dir . $sub;
            $files = dirListRecursive($check_dir);
            $output = array_merge($files, $output);
        }
   }
   writeLogDebug('downloadMediaBatFilesGet-33', $output);
   return $output;
}