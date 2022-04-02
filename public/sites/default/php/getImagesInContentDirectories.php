<?php
myRequireOnce ('writeLog.php');

// get images from folder (in /content) so it can transfer from edit to prototype
//also used to return list of images for selection by Series Edit
// sometimes the data is damaged like this:string(58) "M2/eng/multiply1,/sites/mc2/content/M2/eng/images/standard"
function getImagesInContentDirectories($p){

	$out = [];
	$html = '.html';
	$json = '.json';
	//writeLog('getImagesInContentDirectories-9-dir',$p['image_dirs']);
	$image_dirs= explode(',', $p['image_dirs']);
    foreach ($image_dirs as $directory){
		if (strpos($directory, '/sites') == FALSE){
               $directory ='/sites/'. SITE_CODE .'/content/'. $directory;
			   //writeLog('getImagesInContentDirectories-15-dirfix',$directory);
		}
		$dir = ROOT_EDIT . $directory;
		$dir= str_ireplace('//', '/', $dir);
		if (file_exists($dir)){
			$handler = opendir ($dir);
			while ($mfile = readdir ($handler)){
				if ($mfile != '.' && $mfile != '..' &&  $mfile != '' ){
					if(strpos($mfile, $html) == FALSE && strpos($mfile, $json == FALSE)){
						$out[] =  $directory . '/'.  $mfile ;
				    }
				}
			}
			closedir ($handler);
		}
	}

   // //writeLog('getImagesInContentDirectories-36-out',$out);
	return $out;

}