<?php
myRequireOnce ('writeLog.php');
// get images from folder (in /content) so it can transfer from edit to prototype
//also used to return list of images for selet
// assumes$p[ímage_dir] ='M2/eng/images'
function getImagesInContentDirectory($p){
    //writeLog('getImagesInContentDirectory-8','I got here' );
	$out = '[';
	$debug = 'in getImagesInContentDirectory' . "\n";
    $dir = ROOT_EDIT . $p['image_dir'];
	$dir= str_ireplace('//', '/', $dir);
	$debug .= 'dir:' .  $dir . "\n";
	//writeLog('getImagesInContentDirectory-14',$debug );
	if (file_exists($dir)){
		$handler = opendir ($dir);
		while ($mfile = readdir ($handler)){
			if ($mfile != '.' && $mfile != '..' ){
					$out.= '"'. $p['image_dir'] . '/'.  $mfile .'",';
			}
		}
		closedir ($handler);
	}
	if (strlen($out) > 1){
		$out = substr($out,0, -1) . ']';
	}
	else{
		$message = "NO images found";
		return NULL;
	}
    //writeLog('getImagesInContentDirectory-38',$debug );
	return $out;

}