<?php

myRequireOnce ('writeLog.php');
//define("ROOT_EDIT", ROOT . 'edit.mc2.online/');
function getFooters($p){
    $output= [];
    $directory= ROOT_EDIT .'/sites/'. SITE_CODE . '/prototype/apk/';
    if (file_exists($directory)){
		$handler = opendir ($directory);
		while ($mfile = readdir ($handler)){
			if ($mfile != '.' && $mfile != '..'){
                if (strpos ($mfile, 'languageFooter') !== FALSE){
                   $output[] = $mfile;
                }
			}
		}
		closedir ($handler);
	}
	return $output;
}