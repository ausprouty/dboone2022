<?php
/*
define("ROOT_STAGING", '/home/globa544/staging.mc2.online/');
define("ROOT_PUBLISH", '/home/globa544/app.mc2.online/');
define("ROOT_USB", '/home/globa544/usb.mc2.online/');
*/

function publishDestination ($p){
    if($p['destination'] == 'staging'){
      return ROOT_STAGING;
  }
  if($p['destination'] == 'publish'){
      return ROOT_PUBLISH;
  }
  if($p['destination'] == 'usb'){
      return ROOT_USB;
  }
  $message= 'In publishDestination invalid destination:  ' . $p['destination'];
  writeLogError('publishDestination', $message);
  trigger_error($message, E_USER_ERROR);
}