<?php

function folderArray($path){
	if (file_exists($path)){
		$results =[];
		$dir = new DirectoryIterator($path);
		foreach ($dir as $fileinfo) {
			if ($fileinfo->isDir() && !$fileinfo->isDot()) {
				$results[] = $fileinfo->getFilename();
			}
		}
	}
 return $results;
}