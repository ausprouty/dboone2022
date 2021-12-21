<?php
myRequireOnce ('writeLog.php');

// get images from folder (in /content) so it can transfer from edit to prototype
//also used to return list of images for selet
function getImagesInContentDirectories($p){

	$results = '[';
	$debug = 'in getImagesInContentDirectory' . "\n";
    foreach ($p['image_dirs'] as $directory){
		$dir = ROOT_EDIT . $directory;
		$dir= str_ireplace('//', '/', $dir);
		$debug .= 'dir:' .  $dir . "\n";
		if (file_exists($dir)){
			$handler = opendir ($dir);
			while ($mfile = readdir ($handler)){
				if ($mfile != '.' && $mfile != '..' ){
						$results.= '"' . $directory . '/'.  $mfile .'",';
				}

			}
			closedir ($handler);
		}
	}
	if (strlen($results) > 1){
		$results = substr($results,0, -1) . ']';
		$debug .= "Images found";
	}
	else{
		$message = "NO images found ";
        trigger_error( $message, E_USER_ERROR);
		return NULL;
	}
	$out = $results;
    writeLog('getImagesInContentDirectories',$debug );
	return $out;

}