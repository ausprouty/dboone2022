<?php
/* Here we copy the files that all apks need, but are not sourced from content direcly
/sites/mc2/images/css ->apk.mc2.something/folder/sites/mc2/images/css
/sites/mc2/images/standard -> apk.mc2.something/folder/sites/mc2/images/standard
/sites/default/apk/Cx File Explorer.apk-> apk.mc2.something/Cx File Explorer.apk
/langugage javascript folders
*/
myRequireOnce('copyDirectory.php');
myRequireOnce('getBuild.php', 'apk');
myRequireOnce('writeLog.php');

function checkCommonFiles($p){
  $build = getBuild($p);
  $p['dir_apk'] = ROOT_APK .  $build. '/';
  // see list from verifyCommonFiles
  $destination = [];
  $destination[] = $p['dir_apk'] . 'folder/sites/default/images/css/';
  $destination[] = $p['dir_apk'] . 'folder/sites/'.SITE_CODE. '/images/css/';
  $destination[] = $p['dir_apk'] . 'folder/sites/'.SITE_CODE. '/images/standard/';
  $destination[] = $p['dir_apk'] . 'folder/sites/'.SITE_CODE. '/images/menu/';
  $destination[] = $p['dir_apk'] . 'folder/sites/'.SITE_CODE. '/images/standard/';
  $destination[] = $p['dir_apk'] . 'folder/sites/'. SITE_CODE. '/'. $p['country_code']. '/images/standard/';
  $destination[] = $p['dir_apk'] .'folder/content/'. $p['country_code'] . '/'.  $p['language_iso'] .'/javascript/';
  foreach ($destination as $d){
    if (!is_dir($d)){
        return 'undone';
    }
  }
  $file = $p['dir_apk'] . 'index.html';
  if (!file_exists($file)){
    return 'undone';
  }
  return 'done';
}