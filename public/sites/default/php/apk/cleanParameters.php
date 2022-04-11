<?php
myRequireOnce(' cleanParametersApkDir.php');
function cleanParametersApkDir($dir_apk){
  $bad =['/','..'];
  $clean = str_replace($bad, '', $dir_apk);
  writeLogDebug('cleanParametersApkDir-6', $clean);
  return $clean;
}
