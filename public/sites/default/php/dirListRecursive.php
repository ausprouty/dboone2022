<?php
function dirListRecursive ($directory){
	$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
    $files = array();
    foreach ($rii as $file) {
        if ($file->isDir()){
            continue;
        }
        $files[] = $file->getPathname();
    }
    return $files;
};