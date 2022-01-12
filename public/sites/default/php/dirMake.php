<?php
// make directory if not found. No directory can have words .html
function dirMake($filename){
	$dir = '';
	if (strpos($filename, '//') !== FALSE){
		$filename = str_ireplace ('//', '/', $filename);
	}
	$parts = explode('/', $filename);
	foreach ($parts as $part){
		if (strpos($part, '.html') === FALSE && strpos($part, '.json') === FALSE){
			$dir .= $part . '/';
			if (!file_exists($dir)){
				mkdir ($dir);
			}
		}
	}
	return  $filename;
}