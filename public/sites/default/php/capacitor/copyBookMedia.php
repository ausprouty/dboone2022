<?php

myRequireOnce('getBuild.php', 'apk');
myRequireOnce('verifyBook.php', 'apk');
myRequireOnce('dirListSubdir.php');
myRequireOnce('copyDirectory.php');
myRequireOnce('createDirectories.php');

function copyBookMedia($p){
     $p = getBookDir($p);// set $p['dir_apk']
     /*

    $p['dir_video_list'] = ROOT_EDIT . 'sites/' . SITE_CODE .'/apk/' .$p['country_code'] .'/'. $p['language_iso'] .'/';
    $p['dir_series'] =  $p['country_code'] .'/'. $p['language_iso'] . '/'. $p['folder_name'];
*/
    $dir_source = ROOT_EDIT . 'sites/' . SITE_CODE .'/media/' .$p['country_code'] .'/'. $p['language_iso'] .'/';
    $destination = $p['dir_apk'].'folder/media/' .$p['country_code'] .'/'. $p['language_iso'] .'/';
    createDirectories($destination);
    $directories= dirListSubdir($dir_source);
    foreach($directories as $directory){
        $source = $dir_source . $directory .'/';
        $destination = $p['dir_apk'].'folder/media/'. $directory .'/';
        copyDirectory($source, $destination);
    }
    return;
}
