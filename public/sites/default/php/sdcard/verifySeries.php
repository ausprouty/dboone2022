<?php
myRequireOnce('writeLog.php');
myRequireOnce('dirMake.php');

function verifySeriesDir($p){
    writeLogDebug('verifySeries', $p);
    $p['dir_sdcard'] = ROOT_SDCARD . _verifySeriesClean($p['sdSubDir']);
    $p['dir_video_list'] = ROOT_EDIT . 'sites/' . SITE_CODE .'/sdcard/';
    $p['dir_series'] =  $p['country_code'] .'/'. $p['language_iso'] . '/'. $p['code'];

}

function _verifySeriesClean($dir_sdcard){
  $bad =['/'.'..'];
  $clean = str_replace($bad, '', $dir_sdcard);
  return $clean;
}
/*
    $p['dir_sdcard'] = ROOT_SDCARD . _verifySeriesClean($p['sdSubDir']);
    $p['dir_video_list'] = ROOT_EDIT . 'sites/' . SITE_CODE .'/sdcard/'.'/';
    $p['dir_series'] =  $p['country_code'] .'/'. $p['language_iso'] . '/'. $p[code];
*/
function verifySeriesSDCard($p){
    if (!file_exists($p['dir_sdcard'] . '/folder/content/'. $p['dir_series'] )){
       return 'ready';
    }
    return 'done';
}
function verifySeriesNoJS($p){
     if (!file_exists($p['dir_sdcard'] . '/folder/nojs/'. $p['dir_series'] )){
        return 'ready';
    }
    return 'done';
}
function verifySeriesPDF($p){
    if (!file_exists($p['dir_sdcard'] . '/folder/pdf/'. $p['dir_series'] )){
        return 'ready';
    }
    return 'done';
}
function verifySeriesVideoList($p){
   if (!file_exists($p['dir_video_list'] .  $p['dir_series'] )){
        return 'ready';
    }
    return 'done';
}
