<?php
myRequireOnce('writeLog.php');
myRequireOnce('dirMake.php');
myRequireOnce('verifyBook.php', 'apk');
myRequireOnce('getBookDir.php', 'apk');

function checkStatusBook($p){

    $p = getBookDir($p);// set $p['dir_apk']
    $check = [];
    $out = new stdClass();
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
    return $out;
}
