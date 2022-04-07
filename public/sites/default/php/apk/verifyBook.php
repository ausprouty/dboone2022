<?php
myRequireOnce('getLatestContent.php');
myRequireOnce('writeLog.php');
myRequireOnce('dirMake.php');
myRequireOnce('verifyBookDir.php', 'apk');
myRequireOnce('verifyBookMedia.php', 'apk');


function verifyBookCover($p){
    $p = verifyBookDir($p);
    return 'ready';
}
function verifyBookApk($p){
    writeLogDebug('verifyBookApk-17', $p);
    $p = verifyBookDir($p);
    $p['scope'] = 'series';
    $content = getLatestContent($p);
    $text = json_decode($content['text']);
    $dir_series = $p['dir_apk'] . '/folder/content/'. $p['dir_series'] .'/';
    writeLogDebug('verifyBookApk-23', $dir_series);
    if (!file_exists($dir_series)){
       return 'ready';
    }
    // now see if all items are there
    //writeLogDebug('verifyBookApk-text', $text);
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
function verifyBookVideoList($p){
    $p = verifyBookDir($p);
    $fn = $p['dir_video_list'];
    //writeLogDebug('verifyBookVideoList-90', $fn);
    if (!file_exists( $fn)){
        return 'ready';
    }
    $fn = $p['dir_video_list'] . $p['folder_name']. '.bat';
    //writeLogDebug('verifyBookVideoList-94', $fn);
    if (!file_exists($fn)){
        return 'ready';
    }
     $fn = $p['dir_video_list'] . $p['folder_name'] . 'audio.bat';
     //writeLogDebug('verifyBookVideoList-99', $fn);
    if (!file_exists($fn)){
        return 'ready';
    }
    return 'done';

}
