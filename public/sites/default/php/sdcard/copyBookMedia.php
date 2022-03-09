<?php

myRequireOnce('verifyBookDir.php', 'sdcard');
myRequireOnce('dirListSubdir.php');

function copyBookMedia($p){
     $p = verifyBookDir($p);// set $p['dir_sdcard']
     /*
     $p['dir_sdcard'] = ROOT_SDCARD . _verifyBookClean($p['sdcard_settings']->subDirectory) .'/';
    $p['dir_video_list'] = ROOT_EDIT . 'sites/' . SITE_CODE .'/sdcard/' .$p['country_code'] .'/'. $p['language_iso'] .'/';
    $p['dir_series'] =  $p['country_code'] .'/'. $p['language_iso'] . '/'. $p['folder_name'];
*/
    $dir_source = ROOT_EDIT . 'sites/' . SITE_CODE .'/media/' .$p['country_code'] .'/'. $p['language_iso'] .'/';
    $directories= dirListSubdir($dir_source);
        writeLogDebug('copyBookMedia-15', $directories);
    foreach($directories as $directory){
        writeLogDebug('copyBookMedia-17', $directory);
    }
    return;
}
