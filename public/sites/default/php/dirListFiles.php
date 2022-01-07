<?php
function dirListFiles ($directory){
	if (file_exists($directory)){
		$output = [];
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