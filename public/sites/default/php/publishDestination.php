<?php
/*
define("ROOT_STAGING", '/home/globa544/staging.mc2.online/');
define("ROOT_WEBSITE", '/home/globa544/app.mc2.online/');
define("ROOT_SDCARD", '/home/globa544/usb.mc2.online/');
*/

function publishDestination ($p){
  if (!isset($p['destination'])){
    $message= 'In publishDestination  without  $p[destination]';
     writeLogError('publishDestination', $message);
     trigger_error($message, E_USER_ERROR);
  }
  if($p['destination'] == 'staging'){
      return ROOT_STAGING;
  }
  elseif($p['destination'] == 'website'){
      return ROOT_WEBSITE;
  }
  elseif($p['destination'] == 'sdcard'){
      return ROOT_SDCARD;
  }
  elseif($p['destination'] == 'nojs'){
      return ROOT_NOJS;
  }
  elseif($p['destination'] == 'pdf'){
      return ROOT_PDF;
  }
  $message= 'In publishDestination invalid destination:  ' . $p['destination'];
  writeLogError('publishDestination', $message);
  trigger_error($message, E_USER_ERROR);
}

function publishPageContentURL ($p){
  $url= 'http:/teststaging.mc2.online/' . 'content/';
  $url .= $p['country_code'].'/';
  $url .= $p['language_iso'].'/';
  $url .= $p['folder_name'].'/';
  $url .= $p['filename'] .'.html';
  return $url;
}
