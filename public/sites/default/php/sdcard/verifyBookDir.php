<?php

function verifyBookDir($p){
    if (!isset($p['sdcard_settings'])){
        $message= 'No sdcard_settings in verifyBookDir';
        trigger_error($message, E_USER_ERROR);
    }
    $p['dir_sdcard'] = ROOT_SDCARD . _verifyBookClean($p['sdcard_settings']->subDirectory) .'/';
    $p['dir_video_list'] = ROOT_EDIT . 'sites/' . SITE_CODE .'/sdcard/' .$p['country_code'] .'/'. $p['language_iso'] .'/';
    $p['dir_series'] =  $p['country_code'] .'/'. $p['language_iso'] . '/'. $p['folder_name'];
    return $p;
}
function _verifyBookClean($dir_sdcard){
  $bad =['/'.'..'];
  $clean = str_replace($bad, '', $dir_sdcard);
  return $clean;
}