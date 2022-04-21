<?php

/*  creates directories for copying files
    directory may not have .  in name
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