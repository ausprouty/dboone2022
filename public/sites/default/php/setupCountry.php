<?php
//myRequireOnce('create.php');
//myRequireOnce('copyGlobal.php');

// define("ROOT_EDIT_CONTENT", '../../../edit.mc2.online/sites/mc2/content/');
// define("ROOT_EDIT", '/home/globa544/edit.mc2.online');

function setupCountry($p){
	$out['debug'] = ' Entered setupCountry'."\n";
	if (!isset($p['country_code'])){
		$out['debug'] = 'country code not set'."\n";
		return $out;
	}

	$country_directory = ROOT_EDIT_CONTENT. $p['country_code'] .'/';
    // make country directory
	if (!file_exists($country_directory)){
		dirMake($country_directory);
	}
	// make Image directory
	if (!file_exists($country_directory . 'images')){
		dirMake($country_directory . 'images');
	}
	$destination = $country_directory .'images/standard/';
	if (!file_exists($destination)){
		dirMake($destination);
	}
    $destination = $country_directory .'images/custom/';
	if (!file_exists($destination)){
		dirMake($destination);
	}
    $destination = $country_directory .'styles/';
	if (!file_exists($destination)){
		dirMake($destination);
	}
    $destination = $country_directory .'templates/';
	if (!file_exists($destination)){
		dirMake($destination);
	}
	$out['content'] = 'success';
	return $out;
}
function XsetupImageFolder($p){
	$out['debug'] = 'setupImageFolder'. "\n";
	if (!isset($p['language_iso'])){
		$out['debug'] = 'language_iso not set'."\n";
		return $out;
	}
	$source = ROOT_EDIT_CONTENT. $p['country_code'] .'/images/standard/' ;
	$out['debug'] .= "source is $source\n";
	$destination = ROOT_EDIT_CONTENT. $p['country_code'] .'/' .$p['language_iso'] .'/images/standard/' ;
	$out['debug'] .= "destination is $destination\n";
	if (!file_exists($destination)){
		dirMake($destination);
		$out['debug'] .= "making $destination\n";
	}
	copyGlobal($source, $destination);
	$out['content'] = 'success';
	return $out;
}

// only set up directory for styles;
// do NOT copy styles into here
// encouarage people to use the ZZ folder
function XsetupStylesCountry($p){
	if (!isset($p['country_code'])){
		$out['debug'] = 'country_code not set'."\n";
		return $out;
	}
	$country_directory = ROOT_EDIT_CONTENT. $p['country_code'] . '/' ;
	if (!file_exists($country_directory . 'styles')){
		dirMake($country_directory . 'styles');
	}
	$out['content'] = 'success';
}
function XsetupTemplatesCountry($p){
	if (!isset($p['country_code'])){
		$out['debug'] = 'country_code not set'."\n";
		return $out;
	}
	$setup_directory = ROOT_EDIT. 'sites/default/templates' ;
	$country_directory = ROOT_EDIT_CONTENT. $p['country_code'] . '/' ;
	$out['debug'] = 'setupTemplatesCountrY' . "\n";

	if (!file_exists($country_directory . 'templates')){
		dirMake($country_directory . 'templates');
	}
	$template_dir = $setup_directory . 'templates/';
	$p['folder_name'] = array();
	$dir = new DirectoryIterator($template_dir);
	foreach ($dir as $fileinfo) {
		if ($fileinfo->isDir() && !$fileinfo->isDot()) {
			$dir = $fileinfo->getFilename();
			if (!file_exists($country_directory .'templates/'. $dir)){
				dirMake($country_directory .'templates/'. $dir);
			}
			$source = $setup_directory .'templates/'. $dir . '/';
			$out['debug'] .= "Source is " . $source . "\n";
			if (file_exists($source)){
				$destination = $country_directory .'templates/'. $dir . '/';
				$out['debug'] .= "Destination is " . $destination . "\n";
				$o = copyGlobal($source, $destination);
				$out['debug'] .= $o['debug'];

			}
		}
	}
	$out['content'] = 'success';
	return $out;
}
function XsetupTemplatesLanguage($p){
	if (!isset($p['language_iso'])){
		$out['debug'] = 'language_iso not set'."\n";
		return $out;
	}
	$setup_directory = ROOT_EDIT_CONTENT. $p['country_code'] .'/' ;
	$language_directory = ROOT_EDIT_CONTENT. $p['country_code'] .'/'. $p['language_iso'] . '/' ;
	$out['debug'] = 'setupTemplatesLanguage' . "\n";
	if (!file_exists($language_directory . 'templates')){
		dirMake($language_directory . 'templates');
	}
	$template_dir = $setup_directory . 'templates/';
	$p['folder_name'] = array();
	$dir = new DirectoryIterator($template_dir);
	foreach ($dir as $fileinfo) {
		if ($fileinfo->isDir() && !$fileinfo->isDot()) {
			$dir = $fileinfo->getFilename();
			if (!file_exists($language_directory .'templates/'. $dir)){
				dirMake($language_directory .'templates/'. $dir);
			}
			$source = $setup_directory .'templates/'. $dir . '/';
			$out['debug'] .= 'Source is '. $source . "\n";
			if (file_exists($source)){
				$destination = $language_directory .'templates/'. $dir . '/';
				$out['debug'] .= 'Destination is '. $destination . "\n";
				copyGlobal($source, $destination);
			}
		}
	}
	$out['content'] = 'success';
	return $out;
}