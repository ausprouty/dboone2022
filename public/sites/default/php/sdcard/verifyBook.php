<?php
myRequireOnce('getLatestContent.php');
myRequireOnce('writeLog.php');
myRequireOnce('dirMake.php');
myRequireOnce('verifyBookDir.php', 'sdcard');
myRequireOnce('verifyBookMedia.php', 'sdcard');


function verifyBookCover($p){
    $p = verifyBookDir($p);
    return 'ready';

}


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

    $ok= true;
    foreach ($text->chapters as $chapter){
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
    $ok= true;
    foreach ($text->chapters as $chapter){
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
    if (!file_exists( $fn)){
        return 'ready';
    }
    $fn = $p['dir_video_list'] . $p['folder_name']. '.bat';
    if (!file_exists($fn)){
        return 'ready';
    }
     $fn = $p['dir_video_list'] . $p['folder_name'] . 'audio.bat';
    if (!file_exists($fn)){
        return 'ready';
    }
    return 'done';

}
