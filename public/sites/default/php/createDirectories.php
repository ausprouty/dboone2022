<?php

/*  creates directories for copying files
    directory may have . in name.
*/

function createDirectories($dir){
    if (strpos($dir, './') !== FALSE){
       writeLogAppend('ERROR-createDirectories', $dir);
       return;
    }
    $out = null;
    $parts = explode('/', $dir);
    $path = null;
    foreach ($parts as $part){
        $path .= $part .'/';
        if (!file_exists($path)){
            mkdir($path);
        }
    }
    return $out;
}