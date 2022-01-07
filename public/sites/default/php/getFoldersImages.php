<?php

myRequireOnce ('writeLog.php');
// get folders from both global and country

// get folders from global, country, and language
function getFoldersImages($p){
	$debug = 'getFoldersImages'. "\n";
    $content_directory = ROOT_EDIT . '/sites/' . $p['site'] .'/content/';
	$debug .= " checking $content_directory \n";
	$countries = array();
	$results = '[';
    //find all countries
	if (file_exists($content_directory)){
		$debug .= 'content directory exists' . "\n";
		$dir = new DirectoryIterator($content_directory);
		foreach ($dir as $fileinfo) {
			if ($fileinfo->isDir() && !$fileinfo->isDot()) {
				$name = $fileinfo->getFilename();
                $debug .= $name . "\n";
				if (strlen($name) == 2){
					$countries[] = $name;
				}
			}
		}
		foreach ($countries as $country){
			$check = $content_directory . $country .'/images';
             $debug .= "Checking $check \n";
			$debug .= $check . "\n";
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
			$debug .= $check . "\n";
			if (file_exists($check)){
				$dir = new DirectoryIterator($check);
				foreach ($dir as $fileinfo) {
					if ($fileinfo->isDir() && !$fileinfo->isDot()) {
						$name = $fileinfo->getFilename();
						if (strlen($name) == 3){
							$language_iso = $name;
							$check = $content_directory . $country .'/'.$language_iso . '/images/';
							$debug .= $check . "\n";
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
			$debug .= "Language folders found";
		}
		else{

			$message = "NO Language FOLDER";
            trigger_error( $message, E_USER_ERROR);
		    return NULL;
		}
		$out = $results;

	}
	else{
        $message = $content_directory. " does not exist";
        trigger_error( $message, E_USER_ERROR);
		return NULL;
	}
    //writeLog('getFoldersImages', $debug);
	return $out;

}