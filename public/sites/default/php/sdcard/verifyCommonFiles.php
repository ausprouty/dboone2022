<?php
/* Here we copy the files that all sdcards need, but are not sourced from content direcly
/sites/mc2/images/css ->sdcard.mc2.something/folder/sites/mc2/images/css
/sites/mc2/images/standard -> sdcard.mc2.something/folder/sites/mc2/images/standard
/sites/default/sdcard/Cx File Explorer.apk-> sdcard.mc2.something/Cx File Explorer.apk
/langugage javascript folders
*/
myRequireOnce('copyDirectory.php');
myRequireOnce('verifyBookDir.php', 'sdcard');

function verifyCommonFiles($p){
  $subdirectory=  _verifyBookClean($p['sdcard_settings']->subDirectory) ;
  $p['dir_sdcard'] = ROOT_SDCARD . $subdirectory. '/';;
  // css
  $source = ROOT_EDIT . 'sites/default/images/css/';
  $destination = $p['dir_sdcard'] . 'folder/sites/default/images/css/';
  copyDirectory($source,$destination);

  $source = ROOT_EDIT .'sites/'. SITE_CODE.'/images/css/';
  $destination = $p['dir_sdcard'] . 'folder/sites/'.SITE_CODE. '/images/css/';
  copyDirectory($source,$destination);

// standrd images
  $source = ROOT_EDIT . 'sites/'. SITE_CODE.'/images/standard/';
  $destination = $p['dir_sdcard'] . 'folder/sites/'.SITE_CODE. '/images/standard/';
  copyDirectory($source,$destination);

  $source = ROOT_EDIT . 'sites/'. SITE_CODE.'/images/menu/';
  $destination = $p['dir_sdcard'] . 'folder/sites/'.SITE_CODE. '/images/menu/';
  copyDirectory($source,$destination);

  $source = ROOT_EDIT . 'sites/'. SITE_CODE.'/images/standard/';
  $destination = $p['dir_sdcard'] . 'folder/sites/'.SITE_CODE. '/images/standard/';
  copyDirectory($source,$destination);

 $source = ROOT_EDIT . 'sites/'. SITE_CODE. '/content/'. $p['country_code']. '/images/standard/';
  $destination = $p['dir_sdcard'] . 'folder/sites/'. SITE_CODE. '/'. $p['country_code']. '/images/standard/';
  copyDirectory($source,$destination);


  // javascript

  $languages = explode('.',  $subdirectory);
  foreach ($languages as $language_code){
    if ($language_code != ''){
      $source = ROOT_EDIT .'sites/'. SITE_CODE. '/content/'. $p['country_code'].'/'.$language_code .'/javascript/';
      $destination = $p['dir_sdcard'] .'folder/content/'. $p['country_code'] . '/'. $language_code .'/javascript/';
      writeLogAppend('verifyCommonFiles-50', "$source\n$destination \n\n");
      copyDirectory($source,$destination);

    }
  }
  // apk
  $source = ROOT_EDIT . 'sites/default/sdcard/Cx File Explorer.apk';
  $destination = $p['dir_sdcard'] . 'Cx File Explorer.apk';
  copy($source,$destination);

  $source = ROOT_EDIT . 'sites/'. SITE_CODE.'/prototype/sdcard/'.  SITE_CODE . '.html';
  $destination = $p['dir_sdcard'] . SITE_CODE . '.html';
  copy($source,$destination);

  return true;
}