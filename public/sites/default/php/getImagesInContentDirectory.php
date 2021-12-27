<?php
myRequireOnce ('writeLog.php');

// get images from folder (in /content) so it can transfer from edit to prototype
//also used to return list of images for selet
// assumes$p[ímage_dir] ='M2/eng/images'
function getImagesInContentDirectory($p){
    //writeLog('getImagesInContentDirectory-8','I got here' );
	$results = '[';
	$debug = 'in getImagesInContentDirectory' . "\n";
    $dir = ROOT_EDIT . $p['image_dir'];
	$dir= str_ireplace('//', '/', $dir);
	$debug .= 'dir:' .  $dir . "\n";
	//writeLog('getImagesInContentDirectory-14',$debug );
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
		$debug .= "Images found";
	}
	else{

		$message = "NO images found";
		//writeLog('getImagesInContentDirectory-32', $message);
        trigger_error( $message, E_USER_ERROR);
		return NULL;
	}
	$out = $results;

    //writeLog('getImagesInContentDirectory-38',$debug );
	return $out;

}