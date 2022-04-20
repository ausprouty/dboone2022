<?php
// make directory if not found. No directory can have words .html or .json
myRequireOnce('writeLog.php');
function dirMake($filename){
	$dir = '';
	$filename= rtrim($filename);
	if (strpos($filename, '//') !== FALSE){
		$filename = str_ireplace ('//', '/', $filename);
	}
	if (strpos($filename, '..') !== FALSE){
		$filename = str_ireplace ('..', '', $filename);
	}
    $file_types = array('.bat','.html', '.json', '.mp3', '.mp4', '.wav');
	$parts = explode('/', $filename);
	foreach ($parts as $part){
		$ok = true;
		foreach ($file_types as $type){
			if (strpos($part, $type) !== false){
               $ok = false;
			}
		}
		if ($ok){
			$dir .= $part . '/';
			if (!file_exists($dir)){
				mkdir ($dir);
			}
		}
	}
	return  $filename;
}