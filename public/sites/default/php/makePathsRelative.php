<?php

myRequireOnce ('writeLog.php');

function makePathsRelative( $text, $filename){
    $debug="$filename \n";
    $debug .= $text;
    $up ='../';
    $replace = '';
    $count = substr_count($filename, '/');
    for ($i = 1; $i < $count; $i++){
       $replace .=$up;
    }
    $out= str_ireplace ('"\\', $replace, $text);
    $debug .= "/n/n/n" . $out;
     return $out;

}