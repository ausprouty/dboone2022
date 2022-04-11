<?php


myRequireOnce('getBuild.php', 'apk');
myRequireOnce('writeLog.php');

function checkContentIndex($p){
 writeLogDebug('checkContentIndex-8', $p);
  $build = getBuild($p);
  $p['dir_apk'] = ROOT_APK .  $build. '/';

  $file = $p['dir_apk'] . 'index.html';
  writeLogDebug('checkContentIndex-18', $file);
  if (!file_exists($file)){
    return 'undone';
  }
  $file = $p['dir_apk'] .'folder/content/'.$p['country_code'] .'/'.  $p['language_iso'] .'/index.html';
  writeLogDebug('checkContentIndex-23', $file);
  if (!file_exists($file)){
    return 'undone';
  }
  return 'done';
}