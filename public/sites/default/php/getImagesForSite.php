<?php
myRequireOnce('writeLog.php');

// get images from folder (in /content) so it can transfer from edit to prototype
function getImagesForSite($p){
	$out['debug'] = 'getImages'. "\n";
	$results = '[';
	$out['debug'] = 'in get Images for Site' . "\n";
    $dir = ROOT_EDIT_CONTENT . $p['image_dir'];
	$out['debug'] .= 'dir:' .  $dir . "\n";
	if (file_exists($dir)){
		$handler = opendir ($dir);
		while ($mfile = readdir ($handler)){
			if ($mfile != '.' && $mfile != '..' ){
					$results.= '"' .  $mfile .'",';
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
	$out['content'] = $results;
	$out['error'] = false;
    writeLog('getImages',$out['debug'] );
	return $out;

}