<?php
myRequireOnce('writeLog.php');
myRequireOnce('dirMake.php');
myRequireOnce('verifyBook.php', 'apk');
myRequireOnce('verifyBookDir.php', 'apk');

function checkStatusBook($p){
    if (!isset($p['apk_settings']->subDirectory)){
        $message = 'p[apk_settings]->subDirectory not set';
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
            case "apk":
                if (file_exists($p['dir_apk'] . '/folder/content/')){
                   $out->apk = verifyBookApk($p);
                }
                else{
                    $out->apk = 'undone';
                }
                break;
            case "nojs":
                if (file_exists($p['dir_apk'] . '/folder/nojs/')){
                   $out->nojs = verifyBookNoJS($p);
                }
                else{
                    $out->nojs = 'undone';
                }
                break;
            case "pdf":
                if (file_exists($p['dir_apk'] . '/folder/pdf/')){
                   $out->pdf = verifyBookPDF($p);
                }
                else{
                    $out->pdf = 'undone';
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
