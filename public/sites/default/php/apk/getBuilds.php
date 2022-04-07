<?php
//define("ROOT_APK", ROOT . 'apk.mc2/');
myRequireOnce('dirList.php');

function getsubdirectorys(){
  $dir = ROOT_APK;
  $output = dirList($dir);
  return $output;

}