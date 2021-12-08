<?php
myRequireOnce('create.php');
myRequireOnce('getLatestContent.php');
myRequireOnce('setup.php');
myRequireOnce('copyGlobal.php');

function setupLanguageFolder($p){
	$out['debug'] = 'setupLanguageFolder'. "\n";
	if (!isset($p['language_iso'])){
		$out['debug'] = 'language_iso not set'."\n";
		return $out;
	}
	$setup_directory = ROOT_EDIT_CONTENT. $p['country_code'] .'/' ;
	if (!file_exists($setup_directory)){
		dirMake($setup_directory);
	}
	$language_directory = ROOT_EDIT_CONTENT. $p['country_code'] .'/'. $p['language_iso'] . '/' ;
	if (!file_exists($language_directory)){
		dirMake($language_directory);
	}
	
	// copy images
	if (!file_exists($language_directory . 'images')){
		dirMake($language_directory . 'images');
	}
	$source = $setup_directory . 'images/';
	if (file_exists($source)){
		$destination = $language_directory .'images/standard';
		copyGlobal($source, $destination);
	}
	else{
		setupCountry($p);
	}
	//copy library and also create database record
	$source = $setup_directory . 'templates/library.json';
	if (file_exists($source)){
		$destination = $language_directory .'library.json';
		if (!file_exists($destination)){
            copy($source, $destination);
        }
        // see if in database; if not then add
        $p['scope'] = 'library';
        $existing = getLatestContent($p);
       
        if (!isset($existing['content'])){
            $out['debug'] .= 'not in database' . "\n";
            $p['text'] = file_get_contents($source);
            $p['filename']= 'library';
            $p['filetype'] = 'json';
            $output['content'] = $p['text'];
			createContent($p);
			$p['scope'] = 'library';
			$existing = getLatestContent($p);
        }
        $output['content']= $existing;
       
	}
	else{
		setupCountry($p);
	}
	// setup styles directory styles
	if (!file_exists($language_directory . 'styles')){
		dirMake($language_directory . 'styles');
	}
	
	//copy templates
	//$out['debug'] .= 'copy templates' . "\n";
	//setupTemplatesLanguage($p);
	return $out;
}