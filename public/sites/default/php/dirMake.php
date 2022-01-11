<?php
// make directory if not found. No directory can have words .html
function dirMake($file){
	$dir = '';
	if (strpos($file, '//') !== FALSE){
		$file = str_ireplace ('//', '/', $file);
	}
	$parts = explode('/', $file);
	foreach ($parts as $part){
		if (strpos($part, '.html') == FALSE){
			$dir .= $part . '/';
			if (!file_exists($dir)){
               writeLogError('dirMake-12-'. rand(0, 9999), $file);
				mkdir ($dir);
			}
		}
	}
	return  $file;
}