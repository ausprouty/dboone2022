<?php
//define("ROOT_APK", ROOT . 'apk.mc2/');
myRequireOnce('dirList.php');

function getBuilds(){
  $dir = ROOT_APK;
  $output = dirList($dir);
  return $output;

}