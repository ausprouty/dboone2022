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
    $destination = $p;
  }

  if($destination == 'staging'){
      return ROOT_STAGING;
  }
  elseif($destination == 'website'){
      return ROOT_WEBSITE;
  }
  elseif($destination == 'sdcard'){
      return ROOT_SDCARD . _publishDestinationClean($p['sdcard_settings']->subDirectory);
  }
  elseif($destination == 'nojs'){
      return ROOT_SDCARD . _publishDestinationClean($p['sdcard_settings']->subDirectory);
  }
  elseif($destination == 'pdf'){
      return ROOT_SDCARD . _publishDestinationClean($p['sdcard_settings']->subDirectory);;
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
function _publishDestinationClean($dir_sdcard){
  $bad =['/'.'..'];
  $clean = str_replace($bad, '', $dir_sdcard);
  return $clean;
}
