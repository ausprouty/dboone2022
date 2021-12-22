<?php
//myRequireOnce ('create.php');
//myRequireOnce ('copyGlobal.php');

// define("ROOT_EDIT_CONTENT", '../../../edit.mc2.online/sites/mc2/content/');
// define("ROOT_EDIT", '/home/globa544/edit.mc2.online');

function setupCountry($p){
	$debug = ' Entered setupCountry'."\n";
	if (!isset($p['country_code'])){
		$debug = 'country code not set'."\n";
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
	$out = 'success';
	return $out;
}