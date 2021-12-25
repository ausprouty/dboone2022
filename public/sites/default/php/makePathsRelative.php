<?php

myRequireOnce ('writeLog.php');

function makePathsRelative( $text, $filename){
   writeLog('makePathsRelative-6-text', $text);
    $debug="$filename \n";
    $debug .= $text;
    $up ='../';
    $replace = '';
    $count = substr_count($filename, '/');
    for ($i = 1; $i < $count; $i++){
       $replace .=$up;
    }
    $out= str_ireplace ('"\\', $replace, $text);
    writeLog('makePathsRelative-16-text', $out);
     return $out;

}