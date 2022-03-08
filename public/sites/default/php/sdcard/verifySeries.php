<?php
myRequireOnce('getLatestContent.php');
myRequireOnce('writeLog.php');
myRequireOnce('dirMake.php');

function verifySeriesDir($p){
    if (!isset($p['sdcard_settings'])){
        $message= 'No sdcard_settings in verifySeriesDir';
        trigger_error($message, E_USER_ERROR);
    }
    writeLogDebug('verifySeries', $p);
    $p['dir_sdcard'] = ROOT_SDCARD . _verifySeriesClean($p['sdcard_settings']->subDirectory) .'/';
    $p['dir_video_list'] = ROOT_EDIT . 'sites/' . SITE_CODE .'/sdcard/';
    $p['dir_series'] =  $p['country_code'] .'/'. $p['language_iso'] . '/'. $p['code'];
    return $p;

}

function _verifySeriesClean($dir_sdcard){
  $bad =['/'.'..'];
  $clean = str_replace($bad, '', $dir_sdcard);
  return $clean;
}
/*
    $p['dir_sdcard'] = ROOT_SDCARD . _verifySeriesClean($p['sdcard_settings']->subDirectory);
    $p['dir_video_list'] = ROOT_EDIT . 'sites/' . SITE_CODE .'/sdcard/'.'/';
    $p['dir_series'] =  $p['country_code'] .'/'. $p['language_iso'] . '/'. $p[code];
*/
function verifySeriesSDCard($p){
    $p = verifySeriesDir($p);
    $p['scope'] = 'series';
    $content = getLatestContent($p);
    $text = json_decode($content['text']);
    if (!file_exists($p['dir_sdcard'] . '/folder/content/'. $p['dir_series'] )){
       return 'ready';
    }
    return 'done';
}
function verifySeriesNoJS($p){
     $p = verifySeriesDir($p);
     if (!file_exists($p['dir_sdcard'] . '/folder/nojs/'. $p['dir_series'] )){
        return 'ready';
    }
    return 'done';
}
function verifySeriesPDF($p){
     $p = verifySeriesDir($p);
    if (!file_exists($p['dir_sdcard'] . '/folder/pdf/'. $p['dir_series'] )){
        return 'ready';
    }
    return 'done';
}
function verifySeriesVideoList($p){
     $p = verifySeriesDir($p);
   if (!file_exists($p['dir_video_list'] .  $p['dir_series'] )){
        return 'ready';
    }
    return 'done';
}
