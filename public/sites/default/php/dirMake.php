<?php
// make directory if not found. No directory can have words .html
function dirMake($file){
	$dir = '';
	$parts = explode('/', $file);
	foreach ($parts as $part){
		if (strpos($part, '.html') == FALSE){
			$dir .= $part . '/';
			if (!file_exists($dir)){
				mkdir ($dir);
			}
		}	
	}
}