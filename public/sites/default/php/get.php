<?php

function getFoldersContent($p){
	$out['debug'] = 'getFoldersContent'. "\n";
	if (!$p['language_iso']){
		$out['debug'] .= "language_iso not set\n";
		return $out;
	}
	$exclude = array('images', 'styles', 'templates');
	$path = ROOT_EDIT_CONTENT. $p['country_code'] .'/'. $p['language_iso'] ;
	$out ['debug'] = $path . "\n";
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
			$results = null;
		}
	}
	$out['content'] = $results;
	return $out;
}


// use country and language
// this looks for template in the country/language/templates directory
// and then returns as content
function getTemplate($p){
	$out['debug'] = 'getTemplate'. "\n";
	if (!$p['language_iso']){
		$out['debug'] .= "language_iso not set\n";
		return $out;
	}
	if (!$p['template']){
		$out['debug'] .= "template not set\n";
		return $out;
	}

	$language_dir = ROOT_EDIT_CONTENT . $p['country_code'] .'/'. $p['language_iso'] ;
	$template = $language_dir .'/templates/'. $p['template'];
	$out['debug'] =' template is '. $p['template']. "\n";
	if (file_exists($template)){
		$out['message'] = "Template Found: $template";
		$out['debug'] .= "Template Found: $template". "\n";
		$out['debug'].= file_get_contents($template) . "\n";
		$out['content'] = file_get_contents($template);
	}
	else{
		$out['content'] = null;
		$out['debug'] .= "NO template found". "\n";
		$out['message'] = "NO Templates found";
	}
	$out['error'] = false;
	return $out;
}
// use country and language
function getTemplates($p){
	$out['debug'] = 'getTemplates'. "\n";
	if (!$p['language_iso']){
		$out['debug'] .= "language_iso not set\n";
		return $out;
	}

    $results = '[';
	$template_directory = ROOT_EDIT_CONTENT. $p['country_code'] .'/'. $p['language_iso'] .'/templates/';
	$out['debug'] .= $template_directory . "\n";
	// find folders
	if (!file_exists($template_directory)){
        $include = 'setup.php';
		myRequireOnce('setup.php');
		$out['debug'] .= ' template directory does not exist so going to Setup Templates' . "\n";
		$out2 = setupTemplatesCountry ($p);
		$out3 = setupTemplatesLanguage ($p);
		$out['debug'] .= $out2 ['debug'] . $out3 ['debug'];
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
			$out['message'] = "Templates found";
			$out['error'] = false;
		}
		else{
			$out['debug'] .= ' No templates so going to Setup Templates' . "\n";
			myRequireOnce('setup.php');
			$out2 = setupTemplatesCountry ($p);
			$out3 = setupTemplatesLanguage($p);
			$out['debug'] .= $out2 ['debug'] . $out3 ['debug'];
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
				$out['message'] = "Templates found";
				$out['error'] = false;
			}
		}
		$out['content'] = $results;
	}
	return $out;

}