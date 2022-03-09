<?php
// list subdirectories
function dirListSubdir ($directory){
	if (file_exists($directory)){
		$results = [];
		$handler = opendir ($directory);
		while ($mfile = readdir ($handler)){
			if ($mfile != '.' && $mfile != '..' ){
				if (is_dir($mfile)){
                    $results[] = $mfile;
                }
			}
		}
		closedir ($handler);
	}
 return $results;
}