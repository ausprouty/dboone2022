<?php

myRequireOnce('writeLog.php');
// get folders from both global and country

// get folders from global, country, and language
function getFoldersImages($p){
	$out['debug'] = 'getFoldersImages'. "\n";
    $content_directory = ROOT_EDIT . '/sites/' . $p['site'] .'/content/';
	$out['debug'] .= " checking $content_directory \n";
	$countries = array();
	$results = '[';
    //find all countries
	if (file_exists($content_directory)){
		$out['debug'] .= 'content directory exists' . "\n";
		$dir = new DirectoryIterator($content_directory);
		foreach ($dir as $fileinfo) {
			if ($fileinfo->isDir() && !$fileinfo->isDot()) {
				$name = $fileinfo->getFilename();
                $out['debug'] .= $name . "\n";
				if (strlen($name) == 2){
					$countries[] = $name;
				}
			}
		}
		foreach ($countries as $country){
			$check = $content_directory . $country .'/images';
             $out['debug'] .= "Checking $check \n";
			$out['debug'] .= $check . "\n";
			if (file_exists($check)){
				$dir = new DirectoryIterator($check);
				foreach ($dir as $fileinfo) {
					if ($fileinfo->isDir() && !$fileinfo->isDot()) {
						$name = $fileinfo->getFilename();
						$results.= '"/sites/' . $p['site'] . '/content/'. $country. '/images/'.  $name .'",';
					}
				}
			}
			// check for Langauges --  we assume only language names are 3 char long
			$check = $content_directory . $country ;
			$out['debug'] .= $check . "\n";
			if (file_exists($check)){
				$dir = new DirectoryIterator($check);
				foreach ($dir as $fileinfo) {
					if ($fileinfo->isDir() && !$fileinfo->isDot()) {
						$name = $fileinfo->getFilename();
						if (strlen($name) == 3){
							$language_iso = $name;
							$check = $content_directory . $country .'/'.$language_iso . '/images/';
							$out['debug'] .= $check . "\n";
							if (file_exists($check)){
								$dir = new DirectoryIterator($check);
								foreach ($dir as $fileinfo) {
									if ($fileinfo->isDir() && !$fileinfo->isDot()) {
										$name = $fileinfo->getFilename();
										$results.= '"/sites/' . $p['site'] . '/content/'. $country. '/' .$language_iso . '/images/'.  $name .'",';
									}
								}
							}

						}

					}
				}
			}
		}
		if (strlen($results) > 1){
			$results = substr($results,0, -1) . ']';
			$out['message'] = "Language folders found";
		}
		else{
			$results = null;
			$out['message'] = "NO Language FOLDERS";
		}
		$out['content'] = $results;
		$out['error'] = false;
	}
	else{
		$results = null;
		$out['debug'] .= $content_directory. " does not exist\n";
		$out['message'] =  "NO Language Folders";
	}
    writeLog('getFoldersImages', $out['debug']);
	return $out;

}