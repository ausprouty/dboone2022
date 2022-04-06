<?php
myRequireOnce('writeLog.php');
myRequireOnce('dirMake.php');
myRequireOnce('verifyBook.php', 'apk');
myRequireOnce('verifyBookDir.php', 'apk');

function checkStatusBook($p){
    writeLogDebug('checkStatusBook-8', $p);
    if (!isset($p['apk_settings']->build)){
        $message = 'p[apk_settings]->build not set';
        writeLogError('checkStatusBook', $message);
        trigger_error($message, E_USER_ERROR);
    }
    $p = verifyBookDir($p);// set $p['dir_apk']
    $check = [];
    $out = new stdClass();
    ////writeLogDebug('checkStatusBook-dir', $p['dir_apk'] . '/folder/content/');
    $progress = json_decode($p['progress']);
    foreach ($progress as $key=>$value){
        $out->$key = $value;
        switch ($key){
            case "content":
                if (file_exists($p['dir_apk'] . '/folder/content/')){
                   $out->content = verifyBookApk($p);
                }
                else{
                    $out->content = 'undone';
                }
                break;

            case "videolist":
                $fn = $p['dir_video_list'];
                ////writeLogDebug('checkStatusBook-46', $fn);
                if (file_exists($fn)){
                   $out->videolist = verifyBookVideoList($p);
                }
                else{
                    $out->videolist = 'undone';
                }
                break;
            default:
        }
    }
    ////writeLogDebug('checkStatusBook-10-out', $out);
    return $out;
}
