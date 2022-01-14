<?php
function dirListFiles ($directory){
	$output = [];
	if (file_exists($directory)){
		$handler = opendir ($directory);
		while ($mfile = readdir ($handler)){
			if ($mfile != '.' && $mfile != '..' ){
				$output[] = $mfile;
			}
		}
		closedir ($handler);
	}
	return $output;
}