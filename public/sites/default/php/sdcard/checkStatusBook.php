<?php
myRequireOnce('writeLog.php');
myRequireOnce('dirMake.php');

function checkStatusBook($p){
    $check = [];
    $out = new stdClass();
    writeLogDebug('checkStatusBook-p', $p);
    //define("ROOT_SDCARD", ROOT . 'sdcard.mc2.')
    $p['dir_sdcard'] = ROOT_SDCARD . _checkStatusBookClean($p['sdSubDir']);
    $p['dir_video_list'] = ROOT_EDIT . 'sites/' . SITE_CODE .'/sdcard/';
    $p['dir_series'] =  $p['country_code'] .'/'. $p['language_iso'] . '/'. $p['code'];


    writeLogDebug('checkStatusBook-dir', $p['dir_sdcard'] . '/folder/content/');
    $progress = json_decode($p['progress']);
    foreach ($progress as $key=>$value){
        $out->$key = $value;
        switch ($key){
            case "sdcard":
                if (file_exists($p['dir_sdcard'] . '/folder/content/')){
                   $out->sdcard = checkStatusBookSDCard($p);
                }
                else{
                    $out->sdcard = 'undone';
                }
                break;
            case "nojs":
                if (file_exists($p['dir_sdcard'] . '/folder/nojs/')){
                   $out->nojs = checkStatusBookNoJS($p);
                }
                else{
                    $out->nojs = 'undone';
                }
                break;
            case "pdf":
                if (file_exists($p['dir_sdcard'] . '/folder/pdf/')){
                   $out->pdf = checkStatusBookPDF($p);
                }
                else{
                    $out->pdf = 'undone';
                }
                break;
            case "videolist":
                if (file_exists($p['dir_video_list'] . $p['country_code'])){
                   $out->videolist = checkStatusBookVideoList($p);
                }
                else{
                    $out->videolist = 'undone';
                }
                break;
            default:
        }
    }
    writeLogDebug('checkStatusBook-10-out', $out);
    return $out;
}


function _checkStatusBookClean($dir_sdcard){
  $bad =['/'.'..'];
  $clean = str_replace($bad, '', $dir_sdcard);
  return $clean;
}
/*
    $p['dir_sdcard'] = ROOT_SDCARD . _checkStatusBookClean($p['sdSubDir']);
    $p['dir_video_list'] = ROOT_EDIT . 'sites/' . SITE_CODE .'/sdcard/'.'/';
    $p['dir_series'] =  $p['country_code'] .'/'. $p['language_iso'] . '/'. $p[code];
*/
function checkStatusBookSDCard($p){
    if (!file_exists($p['dir_sdcard'] . '/folder/content/'. $p['dir_series'] )){
       return 'ready';
    }
    return 'done';
}
function checkStatusBookNoJS($p){
     if (!file_exists($p['dir_sdcard'] . '/folder/nojs/'. $p['dir_series'] )){
        return 'ready';
    }
    return 'done';
}
function checkStatusBookPDF($p){
    if (!file_exists($p['dir_sdcard'] . '/folder/pdf/'. $p['dir_series'] )){
        return 'ready';
    }
    return 'done';
}
function checkStatusBookVideoList($p){
   if (!file_exists($p['dir_video_list'] .  $p['dir_series'] )){
        return 'ready';
    }
    return 'done';
}
