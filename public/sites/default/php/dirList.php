<?php
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