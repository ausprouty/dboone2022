<?php
/*
Looking for
 <video id="video"  width = "100%" controls>
        <source src="../../../../media/M2/spa/video/multiply1/102.mp4" type="video/mp4">
        see if directory exists on SD Card -- if not, copy media there.
        pull each html file
        find the videos in it
        see if it exists on directory
    $p['dir_apk'] = ROOT_APK . getBuild($p) .'/';
    $p['dir_video_list'] = ROOT_EDIT . 'sites/' . SITE_CODE .'/apk/' .$p['country_code'] .'/'. $p['language_iso'] .'/';
    $p['dir_series'] =  $p['country_code'] .'/'. $p['language_iso'] . '/'. $p['folder_name'];
*/
myRequireOnce('writeLog.php');
myRequireOnce('publishLanguages.php');
myRequireOnce('dirListFiles.php');
myRequireOnce('dirMake.php');
myRequireOnce('getBuild.php', 'apk');

function verifyLanguageIndex($p){
    $count =count ($p['apk_settings']->languages);
    if ( $count < 2){
       return 1;
    }
    $allowed = [];
    foreach ($p['apk_settings']->languages  as $language){
        $allowed[] = $language->language_iso;
    }
    $response = publishLanguages($p, $allowed);
    return $response;
}