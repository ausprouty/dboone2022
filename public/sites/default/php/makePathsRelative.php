<?php

myRequireOnce ('writeLog.php');
/*filenamestring(80) "/home/globa544/test_staging.mc2.online/content/M2/eng/multiply1/multiply105.html"

We assume filename is inside of content
 /content/M2/eng/multiply1/multiply105.html

 and all refered files are in absolute paths
         /sites/mc2/styles/mc2GLOBAL.css


 And ends up with   ../../../../sites/mc2/styles/mc2GLOBAL.css
*/

function makePathsRelative( $text, $filename){
   $rand= random_int(0, 99999);
   //writeLogError('makePathsRelative-15-text-'. $rand, $text);
   //writeLogError('makePathsRelative-15-filename-'. $rand, $filename);
   // make sure all paths are absolute first
   $text = str_ireplace('href="sites', 'href="/sites', $text );
   $up ='../';
   $replace = '"';
   $parts = explode('/', $filename);
   $count = substr_count($filename, '/');
   //writeLog('makePathsRelative-12-count', $count);
   $start = false;
   for ($i = 0; $i < $count; $i++){
      if ($parts[$i] == 'content'){
         $start = true;
      }
      if($start){
         $replace .= $up;
      }
   }
   $text= str_ireplace ('"/', $replace, $text);
   //writeLogError('makePathsRelative-26-text-' . $rand, $text);
   return $text;

}