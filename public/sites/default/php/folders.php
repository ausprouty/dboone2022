<?php
/* 
/  returns a json array of all folders in a given path
*/
function folders($path){
	if (file_exists($path)){
		$results = '[';
		$dir = new DirectoryIterator($path);
		foreach ($dir as $fileinfo) {
			if ($fileinfo->isDir() && !$fileinfo->isDot()) {
				$name = $fileinfo->getFilename();
				$results .= '"'. $name .'",';
			}
		}
		if (strlen($results) > 1){
			$results = substr($results,0, -1) . ']';
		}
		else{
			$results = null;
		}
	}
 return $results;
}