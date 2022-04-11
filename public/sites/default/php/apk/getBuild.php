<?php
//define("ROOT_APK", ROOT . 'apk.mc2/');


function getBuild($p){
  if (isset($p['apk_settings']->build)){
      $bad =['/','..'];
      $build = str_replace($bad, '', $p['apk_settings']->build);
  }
  else{
    $build = 'unknown';
    writeLogAppend('ERROR-verifyCommonFiles', $p);
  }
  return $build;
}