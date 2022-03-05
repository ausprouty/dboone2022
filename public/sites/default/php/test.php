<?php
myRequireOnce ('.env.api.remote.php');
myRequireOnce ('dirMake.php');

$files = dirlist('../');
echo $files .'<br>';
echo(ROOT_LOG).'<br>';

$files = dirlist(ROOT_LOG);
echo $files .'<br>';

function dirlist ($directory){
	if (file_exists($directory)){
		$results = '[';
		$handler = opendir ($directory);
		while ($mfile = readdir ($handler)){
			if ($mfile != '.' && $mfile != '..' ){
				$results.= '"'.  $mfile .'",';
			}
		}
		closedir ($handler);
		if (strlen($results) > 1){
			$results = substr($results,0, -1) . ']';
		}
		else{
			$results = null;
		}
	}
 return $results;
}
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
