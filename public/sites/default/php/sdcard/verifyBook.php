<?php
myRequireOnce('getLatestContent.php');
myRequireOnce('writeLog.php');
myRequireOnce('dirMake.php');

function verifyBookDir($p){
    if (!isset($p['sdcard_settings'])){
        $message= 'No sdcard_settings in verifyBookDir';
        trigger_error($message, E_USER_ERROR);
    }
    writeLogDebug('verifyBook', $p);
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
/*
    $p['dir_sdcard'] = ROOT_SDCARD . _verifyBookClean($p['sdcard_settings']->subDirectory);
    $p['dir_video_list'] = ROOT_EDIT . 'sites/' . SITE_CODE .'/sdcard/'.'/';
    $p['dir_series'] =  $p['country_code'] .'/'. $p['language_iso'] . '/'. $p[code];
*/
function verifyBookSDCard($p){
    $p = verifyBookDir($p);
    $p['scope'] = 'series';
    $content = getLatestContent($p);
    $text = json_decode($content['text']);
    $dir_series = $p['dir_sdcard'] . '/folder/content/'. $p['dir_series'] .'/';
    if (!file_exists($dir_series)){
       return 'ready';
    }
    // now see if all items are there
    writeLogDebug('verifyBookSDCard-text', $text);
    $ok= true;
    foreach ($text->chapters as $chapter){
   // foreach ($text['chapters'] as $chapter){
        if ($chapter->publish){
             $filename=  $dir_series. $chapter->filename. '.html';
            if (!file_exists($filename)){
                $ok = false;
            }
        }
    }
    if ($ok){
        return 'done';
    }
    return 'ready';
}
function verifyBookNoJS($p){
    $p = verifyBookDir($p);
    $p['scope'] = 'series';
    $content = getLatestContent($p);
    $text = json_decode($content['text']);
    $dir_series = $p['dir_sdcard'] . '/folder/nojs/'. $p['dir_series'] .'/';
    if (!file_exists($dir_series)){
       return 'ready';
    }
    // now see if all items are there
    writeLogDebug('verifyBookNoJS-text', $text);
    $ok= true;
    foreach ($text->chapters as $chapter){
   // foreach ($text['chapters'] as $chapter){
        if ($chapter->publish){
             $filename=  $dir_series. $chapter->filename. '.html';
            if (!file_exists($filename)){
                $ok = false;
            }
        }
    }
    if ($ok){
        return 'done';
    }
    return 'ready';
}
function verifyBookPDF($p){
     $p = verifyBookDir($p);
    if (!file_exists($p['dir_sdcard'] . '/folder/pdf/'. $p['dir_series'] )){
        return 'ready';
    }
    return 'done';
}
function verifyBookVideoList($p){
    $p = verifyBookDir($p);
    $fn = $p['dir_video_list'];
    writeLogDebug('verifyBookVideoList-90', $fn);
    if (!file_exists( $fn)){
        return 'ready';
    }
    $fn = $p['dir_video_list'] . $p['folder_name']. '.bat';
    writeLogDebug('verifyBookVideoList-94', $fn);
    if (!file_exists($fn)){
        return 'ready';
    }
     $fn = $p['dir_video_list'] . $p['folder_name'] . 'audio.bat';
     writeLogDebug('verifyBookVideoList-99', $fn);
    if (!file_exists($fn)){
        return 'ready';
    }
    return 'done';

}
