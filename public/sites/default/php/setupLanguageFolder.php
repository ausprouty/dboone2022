<?php
//myRequireOnce ('create.php');
//myRequireOnce ('getLatestContent.php');
//myRequireOnce ('setup.php');
//myRequireOnce ('copyGlobal.php');

function setupLanguageFolder($p){

$debug = ' Entered setupLanguageFolder'."\n";
	if (!isset($p['country_code'])){
		$debug = 'country code not set'."\n";
		return $out;
	}
	if (!isset($p['language_iso'])){
		$debug = 'language_iso not set'."\n";
		return $out;
	}

	$country_directory = ROOT_EDIT_CONTENT. $p['country_code'] .'/';
    // make country directory
	if (!file_exists($country_directory)){
		dirMake($country_directory);
	}
	$language_directory= $country_directory . '/' . $p['language_iso'] . '/';
	// make Image directory
	if (!file_exists($language_directory . 'images')){
		dirMake($language_directory . 'images');
	}
	$destination = $language_directory .'images/standard/';
	if (!file_exists($destination)){
		dirMake($destination);
	}
    $destination = $language_directory .'images/custom/';
	if (!file_exists($destination)){
		dirMake($destination);
	}
    $destination = $language_directory .'styles/';
	if (!file_exists($destination)){
		dirMake($destination);
	}
    $destination = $language_directory .'templates/';
	if (!file_exists($destination)){
		dirMake($destination);
	}
	$out = 'success';
	return $out;
}

function XsetupLanguageFolder($p){
	$debug = 'setupLanguageFolder'. "\n";
	if (!isset($p['language_iso'])){
		$debug = 'language_iso not set'."\n";
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

        if (!isset($existing)){
            $debug .= 'not in database' . "\n";
            $p['text'] = file_get_contents($source);
            $p['filename']= 'library';
            $p['filetype'] = 'json';
            $output['content'] = $p['text'];
			createContent($p);
			$p['scope'] = 'library';
			$existing = getLatestContent($p);
        }
        $out= $existing;

	}
	else{
		setupCountry($p);
	}
	// setup styles directory styles
	if (!file_exists($language_directory . 'styles')){
		dirMake($language_directory . 'styles');
	}

	//copy templates
	//$debug .= 'copy templates' . "\n";
	//setupTemplatesLanguage($p);
	return $out;
}