<?php
/*
define("ROOT_STAGING", '/home/globa544/staging.mc2.online/');
define("ROOT_PUBLISH", '/home/globa544/app.mc2.online/');
define("ROOT_SDCARD", '/home/globa544/usb.mc2.online/');
*/

function publishDestination ($p){
    if($p['destination'] == 'staging'){
      return ROOT_STAGING;
  }
  if($p['destination'] == 'website'){
      return ROOT_PUBLISH;
  }
  if($p['destination'] == 'sdcard'){
      return ROOT_SDCARD;
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
