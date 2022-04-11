<?php
myRequireOnce('getBuild.php', 'apk');
myRequireOnce('writeLog.php');
myRequireOnce('publishLibrary.php');



function verifyContentIndex($p){
    $build = getBuild($p);
    $p['dir_apk'] = ROOT_APK . $build. '/';
    verifyContentIndexRoot($p);
    $p['library_code'] = 'library';
    publishLibrary($p);
    return 'done';
}


function verifyContentIndexRoot($p){
  $template_file = ROOT_EDIT . 'sites/'. SITE_CODE.'/prototype/apk/rootIndex.html';
  if (!file_exists($template_file)){
    writeLogError('verifyContentIndexRoot'. $template_file);
  }
  $text = file_get_contents($template_file);
  $find = [
    '{{ country_code }}',
    '{{ language_iso }}'
  ];
  $replace = [
    $p['country_code'],
    $p['language_iso']
  ];
  $text = str_replace($find, $replace, $text);
  $filename = $p['dir_apk'] . 'index.html';
  writeLogDebug('verifyContentIndexRoot-34', $filename);
  fileWrite($filename, $text, $p);
  return;

}