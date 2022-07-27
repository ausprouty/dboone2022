<?php
// make directory if not found. No directory can have words '.bat','.html', '.json', '.mp3', '.mp4', '.wav')
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
	if (strpos($filename, ROOT_EDIT) === FALSE &&
		strpos($filename, ROOT_STAGING) === FALSE &&
		strpos($filename, ROOT_WEBSITE) === FALSE){
		$filename = ROOT_EDIT.  $filename;
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
			//writeLogAppend('dirMake-24', $dir);
			if (!file_exists($dir)){
				mkdir ($dir);
			}
		}
	}
	return  $filename;
}