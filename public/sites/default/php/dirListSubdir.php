<?php

myRequireOnce('writeLog.php');
// list subdirectories
function dirListSubdir ($directory){
	$rand = random_int(0,9999);
	if (file_exists($directory)){
		$results = [];
		$handler = opendir ($directory);
		while ($mfile = readdir ($handler)){
			if ($mfile != '.' && $mfile != '..' ){
				if (is_dir($directory . $mfile)){
                    $results[] = $mfile;
                }
			}
		}
		closedir ($handler);
	}
 return $results;
}