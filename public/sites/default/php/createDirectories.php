<?php

/*  creates directories for copying files
    directory may have . in name.
*/

function createDirectories($dir){
    $dir = str_replace('..', '', $dir); // make safe
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