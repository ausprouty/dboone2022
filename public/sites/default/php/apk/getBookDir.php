<?php
myRequireOnce('getBuild.php');
function getBookDir($p){
    if (!isset($p['apk_settings'])){
        $message= 'No apk_settings in getBookDir';
        trigger_error($message, E_USER_ERROR);
    }
    $p['dir_apk'] = ROOT_APK . getBuild($p) .'/';
    $p['dir_video_list'] = ROOT_EDIT . 'sites/' . SITE_CODE .'/apk/' .$p['country_code'] .'/'. $p['language_iso'] .'/';
    $p['dir_series'] =  $p['country_code'] .'/'. $p['language_iso'] . '/'. $p['folder_name'];
    return $p;
}
