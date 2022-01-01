<?php

myRequireOnce ('writeLog.php');
/*filenamestring(80) "/home/globa544/test_staging.mc2.online/content/M2/eng/multiply1/multiply105.html"

 What if filename is /home/globa544/test_staging.mc2.online/content/M2/eng/multiply1/multiply105.html
 and filename is  /home/globa544/test_staging.mc2.online/images/icons/manifest.json
You should end up with  ../../../../images/icons/manifest.json

 And file ends up with   ../../../../sites/mc2/images/icons/manifest.json
*/

function makePathsRelative( $text, $filename){
   //writeLog('makePathsRelative-6-text', $text);
   //writeLog('makePathsRelative-8-filename', $filename);
   $up ='../';
   $replace = '"';
   $count = substr_count($filename, '/');
   //writeLog('makePathsRelative-12-count', $count);
    for ($i = 4; $i < $count; $i++){
       $replace .=$up;
    }
   //writeLog('makePathsRelative-16-replace', $replace);
   $out= str_ireplace ('"/', $replace, $text);
   //writeLog('makePathsRelative-16-text', $out);
   return $out;

}