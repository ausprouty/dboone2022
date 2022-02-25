<?php
myRequireOnce ('writeLog.php');


function version2Text($text){
    //writeLog('version2Text-6', $text);
    $text = str_ireplace('"/sites/default/images/back.png', '"/sites/default/images/back.png', $text);
    $text = str_ireplace('"/sites/default/images/up.png', '"/sites/default/images/up.png', $text);
    $text = str_ireplace('"/sites/default/images/forward.png', '"/sites/default/images/forward.png', $text);
    $text = str_ireplace('"/content/', '"/sites/default/content/', $text);
    $text = str_ireplace('"/sites/myfriends/sites/mc2/', '/sites/myfriends/sites/mc2/', $text);

    $text = str_ireplace('"/content/AU/images/standard/','', $text);
    $text = str_ireplace('"/content/ZZ/styles/myfriendsGLOBAL.css"', '', $text);
    //writeLog('version2Text-15', $text);
   return $text;
}
