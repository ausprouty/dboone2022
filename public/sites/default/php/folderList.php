<?php
function folderList ($directory){
	$results = [];
	if (file_exists($directory)){
		$handler = opendir ($directory);
		while ($mfile = readdir ($handler)){
			if ($mfile != '.' && $mfile != '..' ){
				if (is_dir($directory . '/'. $mfile)){
					$results[]=  $mfile;
				}
			}
		}
		closedir ($handler);
	}
 return $results;
}