<?php
//define("ROOT_APK", ROOT . 'apk.mc2/');
myRequireOnce('folderList.php');

function getBuilds(){
  $dir = ROOT_APK;
  $output = folderList($dir);
  sort($output);
  return $output;

}