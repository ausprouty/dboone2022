<?php
myRequireOnce('getLatestContent.php');
myRequireOnce('writeLog.php');
myRequireOnce('dirMake.php');
myRequireOnce('getBookDir.php', 'apk');
myRequireOnce('verifyBookMedia.php', 'apk');


function verifyBookCover($p){
    $p = getBookDir($p);
    return 'ready';
}
function verifyBookApk($p){
    writeLogDebug('verifyBookApk-17', $p);
    $p = getBookDir($p);
    $p['scope'] = 'series';
    $content = getLatestContent($p);
    $text = json_decode($content['text']);
    $dir_series = $p['dir_apk'] . '/folder/content/'. $p['dir_series'] .'/';
    writeLogDebug('verifyBookApk-23', $dir_series);
    if (!file_exists($dir_series)){
       writeLogAppend('verifyBookApk-22', $dir_series );
       return 'undone';
    }
    // now see if all items are there
    //writeLogDebug('verifyBookApk-text', $text);
    $ok= true;
    foreach ($text->chapters as $chapter){
   // foreach ($text['chapters'] as $chapter){
        if ($chapter->publish){
             $filename=  $dir_series. $chapter->filename. '.html';
            if (!file_exists($filename)){
                writeLogAppend('verifyBookApk-33', $filename );
                return 'undone';
            }
        }
    }
    if ($ok){
        return 'done';
    }
    return 'undone';
}
function verifyBookVideoList($p){
    $p = getBookDir($p);
    $fn = $p['dir_video_list'];
    //writeLogDebug('verifyBookVideoList-90', $fn);
    if (!file_exists( $fn)){
        return 'undone';
    }
    $fn = $p['dir_video_list'] . $p['folder_name']. '.bat';
    //writeLogDebug('verifyBookVideoList-94', $fn);
    if (!file_exists($fn)){
        return 'undone';
    }
     $fn = $p['dir_video_list'] . $p['folder_name'] . 'audio.bat';
     //writeLogDebug('verifyBookVideoList-99', $fn);
    if (!file_exists($fn)){
        return 'undone';
    }
    return 'done';

}
