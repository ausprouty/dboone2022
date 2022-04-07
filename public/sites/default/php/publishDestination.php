<?php
/*
define("ROOT_STAGING", '/home/globa544/staging.mc2.online/');
define("ROOT_WEBSITE", '/home/globa544/app.mc2.online/');
define("ROOT_SDCARD", ROOT . 'sdcard.mc2');


*/

function publishDestination ($p){

  if (is_array($p)){
    $destination = $p['destination'];
  }
  else{
    $message ='p must be array';
    trigger_error($message, E_USER_ERROR);
  }

  if($destination == 'staging'){
      return ROOT_STAGING;
  }
  elseif($destination == 'website'){
      return ROOT_WEBSITE;
  }
  elseif($destination == 'sdcard'){
      return ROOT_SDCARD . _publishDestinationSDCard($p) . '/folder/';
  }
  elseif($destination == 'nojs'){
      return ROOT_SDCARD . _publishDestinationSDCard($p)  . '/folder/';
  }
  elseif($destination == 'pdf'){
      return ROOT_SDCARD . _publishDestinationSDCard($p)  . '/folder/';
  }
   elseif($destination == 'apk'){
      return ROOT_APK .'/'. _publishDestinationApk($p)  . '/folder/';
  }
  $message= 'In publishDestination invalid destination:  ' . $destination;
  writeLogError('publishDestination-30', $p);
  trigger_error($message, E_USER_ERROR);
}

function XpublishPageContentURL ($p){
  $url= 'http:/teststaging.mc2.online/' . 'content/';
  $url .= $p['country_code'].'/';
  $url .= $p['language_iso'].'/';
  $url .= $p['folder_name'].'/';
  $url .= $p['filename'] .'.html';
  return $url;
}
function _publishDestinationSDCard($p){
  if (!isset($p['sdcard_settings'])){
    $message = 'No SD Card Settings';
    writeLogError('_publishDestinationSDCard-p ', $p);
    trigger_error($message, E_USER_ERROR);
  }
  $bad =['/','..'];
  $clean = str_replace($bad, '', $p['sdcard_settings']->subDirectory);
  return $clean;
}
function _publishDestinationApk($p){
  if (!isset($p['apk_settings']->build)){
    $message = 'No APK Settings';
    writeLogError('_publishDestinationApk-p ', $p);
    trigger_error($message, E_USER_ERROR);
  }
  $bad =['/','..'];
  $clean = str_replace($bad, '', $p['apk_settings']->build);
  return $clean;
}
