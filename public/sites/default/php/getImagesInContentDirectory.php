<?php
myRequireOnce('writeLog.php');

// get images from folder (in /content) so it can transfer from edit to prototype
//also used to return list of images for selet
function getImagesInContentDirectory($p){

	$results = '[';
	$debug = 'in getImagesInContentDirectory' . "\n";
    $dir = ROOT_EDIT . $p['image_dir'];
	$dir= str_ireplace('//', '/', $dir);
	$debug .= 'dir:' .  $dir . "\n";
	if (file_exists($dir)){
		$handler = opendir ($dir);
		while ($mfile = readdir ($handler)){
			if ($mfile != '.' && $mfile != '..' ){
					$results.= '"'. $p['image_dir'] . '/'.  $mfile .'",';
			}

		}
		closedir ($handler);
	}
	if (strlen($results) > 1){
		$results = substr($results,0, -1) . ']';
		$out['message'] = "Images found";
	}
	else{
		$results = null;
		$out['message'] = "NO images found";
	}
	$out = $results;
	$out['error'] = false;
    writeLog('getImagesInContentDirectory',$debug );
	return $out;

}