<?php
function dirlist ($directory){
	$results = [];
	if (file_exists($directory)){
		$handler = opendir ($directory);
		while ($mfile = readdir ($handler)){
			if ($mfile != '.' && $mfile != '..' ){
				$results[]=  $mfile;
			}
		}
		closedir ($handler);
	}
 return $results;
}