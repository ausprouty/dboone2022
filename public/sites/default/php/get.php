<?php
myRequireOnce ('writeLog.php');

function getFoldersContent($p){
	$debug = 'getFoldersContent'. "\n";
	if (!$p['language_iso']){
		writeLogError('getFoldersContent', 'language_iso not set' );
		return false;
	}
	$exclude = array('images', 'styles', 'templates');
	$path = ROOT_EDIT_CONTENT. $p['country_code'] .'/'. $p['language_iso'] .'/' ;
	$results = null;
	if (file_exists($path)){
		$results = '[';
		$dir = new DirectoryIterator($path);
		foreach ($dir as $fileinfo) {
			if ($fileinfo->isDir() && !$fileinfo->isDot()) {
				$name = $fileinfo->getFilename();
				if (!in_array($name, $exclude)){
					$results .= '"'. $name .'",';
				}
			}
		}
		if (strlen($results) > 1){
			$results = substr($results,0, -1) . ']';
		}
		else{
			$results = makeFoldersContent($path);
		}
	}
	return $results;
}

function makeFoldersContent($path){
    if (STANDARD_SERIES == NULL){
		writeLogError('getFoldersContent', 'STANDARD_SERIES not set' );
		return null;
	}
	$results = '[';
    $folders = explode(',', STANDARD_SERIES );
	foreach($folders as $folder){
		dirMake($path . $folder);
		$results .= '"'. $folder .'",';
	}
	if (strlen($results) > 1){
			$results = substr($results,0, -1) . ']';
	}
	else{
        $results = null;
		writeLogError('getFoldersContent', 'STANDARD_SERIES has no items' );
	}
	return $results;
}


// use country and language
// this looks for template in the country/language/templates directory
// and then returns as content
function getTemplate($p){
	$debug = 'getTemplate'. "\n";
	if (!$p['language_iso']){
		$debug .= "language_iso not set\n";
		return $out;
	}
	if (!$p['template']){
		$debug .= "template not set\n";
		return $out;
	}

	$language_dir = ROOT_EDIT_CONTENT . $p['country_code'] .'/'. $p['language_iso'] ;
	$template = $language_dir .'/templates/'. $p['template'];
	$debug =' template is '. $p['template']. "\n";
	if (file_exists($template)){
		$debug .= "Template Found: $template". "\n";
		$debug.= file_get_contents($template) . "\n";
		$out = file_get_contents($template);
	}
	else{
		$message = "NO Templates found ";
        trigger_error( $message, E_USER_ERROR);
		return NULL;
	}

	return $out;
}
// use country and language
function getTemplates($p){
	$debug = 'getTemplates'. "\n";
	if (!$p['language_iso']){
		$debug .= "language_iso not set\n";
		return $out;
	}

    $results = '[';
	$template_directory = ROOT_EDIT_CONTENT. $p['country_code'] .'/'. $p['language_iso'] .'/templates/';
	$debug .= $template_directory . "\n";
	// find folders
	if (!file_exists($template_directory)){
        $include = 'setup.php';
		myRequireOnce ('setup.php');
		$debug .= ' template directory does not exist so going to Setup Templates' . "\n";
		setupTemplatesLanguage ($p);
    }
	if (file_exists($template_directory)){
        $results = '[';
	    $folders =  array();
		$handler = opendir ($template_directory);
		while ($mfile = readdir ($handler)){
			if ($mfile != '.' && $mfile != '..' ){
				$folders[] =  $mfile;
			}
		}
		closedir ($handler);
		foreach ($folders as $folder){
			if (is_dir($template_directory . $folder)){
				$handler = opendir ($template_directory . $folder);
				while ($mfile = readdir ($handler)){
					if ($mfile != '.' && $mfile != '..' ){
						$results.= '"'. $folder . '/' . $mfile .'",';
					}
				}
				closedir ($handler);
			}
			else{ // there are some files in the root directory
				$results.= '"'. $folder  .'",';
			}
		}
		if (strlen($results) > 1){
			$results = substr($results,0, -1) . ']';
		}
		else{
			$debug .= ' No templates so going to Setup Templates' . "\n";
			myRequireOnce ('setup.php');
			setupTemplatesLanguage($p);
			$handler = opendir ($template_directory);
			while ($mfile = readdir ($handler)){
				if ($mfile != '.' && $mfile != '..' ){
					$foldernames[] =  $mfile;
				}
			}
			closedir ($handler);
			foreach ($folders as $folder){
				$handler = opendir ($template_directory . $folder);
				while ($mfile = readdir ($handler)){
					if ($mfile != '.' && $mfile != '..' ){
						$results.= '"'. $folder . '/' . $mfile .'",';
					}
				}
				closedir ($handler);
			}
			if (strlen($results) > 1){
				$results = substr($results,0, -1) . ']';
				$debug .= "Templates found";

			}
		}
		$out = $results;
	}
	return $out;

}