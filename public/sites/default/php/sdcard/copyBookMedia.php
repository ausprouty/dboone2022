<?php

myRequireOnce('verifyBookDir.php', 'sdcard');
myRequireOnce('verifyBook.php', 'sdcard');
myRequireOnce('dirListSubdir.php');
myRequireOnce('copyDirectory.php');
myRequireOnce('createDirectories.php');

function copyBookMedia($p){
     $p = verifyBookDir($p);// set $p['dir_sdcard']
     /*
     $p['dir_sdcard'] = ROOT_SDCARD . _verifyBookClean($p['sdcard_settings']->subDirectory) .'/';
    $p['dir_video_list'] = ROOT_EDIT . 'sites/' . SITE_CODE .'/sdcard/' .$p['country_code'] .'/'. $p['language_iso'] .'/';
    $p['dir_series'] =  $p['country_code'] .'/'. $p['language_iso'] . '/'. $p['folder_name'];
*/
    $dir_source = ROOT_EDIT . 'sites/' . SITE_CODE .'/media/' .$p['country_code'] .'/'. $p['language_iso'] .'/';
    $destination = $p['dir_sdcard'].'folder/media/' .$p['country_code'] .'/'. $p['language_iso'] .'/';
    createDirectories($destination);
    $directories= dirListSubdir($dir_source);
    foreach($directories as $directory){
        $source = $dir_source . $directory .'/';
        $destination = $p['dir_sdcard'].'folder/media/'. $directory .'/';
        copyDirectory($source, $destination);
    }
    return;
}
