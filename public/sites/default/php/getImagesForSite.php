<?php
myRequireOnce ('writeLog.php');

// get images from site and  default directories
function getImagesForSite($p){
	$debug = 'getImages'. "\n";
	$results = '[';
	$debug = 'in get Images for Site' . "\n";
    //define("SITE_CODE", 'mc2');
    // define("ROOT_EDIT", '/home/globa544/edit.mc2.online/');

    $check['default'] =ROOT_EDIT . 'sites/default/'. $p['image_dir'];
    $check[SITE_CODE]  = ROOT_EDIT . 'sites/'. SITE_CODE . '/' .$p['image_dir'];
	foreach ($check as $key=> $dir){
		$dir = str_ireplace('//', '/', $dir);
		$debug .= 'dir:' .  $dir . "\n";
		if (file_exists($dir)){
			$handler = opendir ($dir);
			while ($mfile = readdir ($handler)){
				if ($mfile != '.' && $mfile != '..' ){
						$results.= '"/sites/'. $key . '/'. $p['image_dir'] .'/'. $mfile .'",';
				}

			}
			closedir ($handler);
		}
	}
	if (strlen($results) > 1){
		$results = substr($results,0, -1) . ']';
		$debug  .= "Images found";
	}
	else{
		 $message = "NO images found";
        trigger_error( $message, E_USER_ERROR);
		return NULL;
	}
	$out = $results;
    //writeLog('getImagesForSite',$debug );
	return $out;

}