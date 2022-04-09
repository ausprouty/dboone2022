<?php

function cleanParametersApkDir($param){
  $bad =['/'.'..'];
  $clean = '/'. str_replace($bad, '', $dir_apk);
  return $clean;
}
