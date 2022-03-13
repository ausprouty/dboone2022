<?php
// make directory if not found. No directory can have words .html or .json
function dirMake($filename){
	$dir = '';
	$filename= trim($filename);
	if (strpos($filename, '//') !== FALSE){
		$filename = str_ireplace ('//', '/', $filename);
	}
	if (strpos($filename, '..') !== FALSE){
		$filename = str_ireplace ('..', '', $filename);
	}
    $file_types = array('.html', '.json', 'mp3', '.mp4', '.wav');
	$parts = explode('/', $filename);
	foreach ($parts as $part){
		if (!in_array($part, $file_types)){
			$dir .= $part . '/';
			if (!file_exists($dir)){
				mkdir ($dir);
			}
		}
	}
	return  $filename;
}