<?php
myRequireOnce('cleanParameters.php');
function verifyBookDir($p){
    if (!isset($p['apk_settings'])){
        $message= 'No apk_settings in verifyBookDir';
        trigger_error($message, E_USER_ERROR);
    }
    $p['dir_apk'] = ROOT_APK . cleanParametersApkDir($p['apk_settings']->build) .'/';
    $p['dir_video_list'] = ROOT_EDIT . 'sites/' . SITE_CODE .'/apk/' .$p['country_code'] .'/'. $p['language_iso'] .'/';
    $p['dir_series'] =  $p['country_code'] .'/'. $p['language_iso'] . '/'. $p['folder_name'];
    return $p;
}
