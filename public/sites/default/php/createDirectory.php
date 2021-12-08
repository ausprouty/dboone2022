<?php

/*  creates directories for copying files
*/

function createDirectory($dir){
    $out = null;
    $parts = explode('/', $dir);
    $path = null;
    foreach ($parts as $part){
        $path .= $part .'/';
        if (strpos ($part, '.') === false){
            if (!file_exists($path)){
                mkdir($path);
            }
        }
    }
    return $out;
}