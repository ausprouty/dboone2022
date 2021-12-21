<?php
// get styles from default, site  and country
function getStyles($p){
	$debug = 'getStyles'. "\n";
	if (!$p['country_code']){
		$debug .= "country_code not set\n";
		return $out;
	}
	$debug = 'getStyles'. "\n";
	$global_directory = '/sites/default/styles/' ;
    $site_directory = '/sites/' . $p['site'] . '/styles/';
	$country_directory = ROOT_EDIT. '/sites/'.  $p['site'] . '/'. $p['country_code'] . '/styles/';
	$results = '[';
	$debug = $country_directory . "\n";
	$debug .= $global_directory . "\n";
    $debug .= $country_directory . "\n";
	if (file_exists( ROOT_EDIT. $global_directory)){
		$handler = opendir (ROOT_EDIT. $global_directory);
		while ($mfile = readdir ($handler)){
			if ($mfile != '.' && $mfile != '..' ){
				$results.= '"' . $global_directory . $mfile .'",';
			}
		}
		closedir ($handler);
	}
    if (file_exists(ROOT_EDIT. $site_directory)){
		$handler = opendir (ROOT_EDIT. $site_directory);
		while ($mfile = readdir ($handler)){
			if ($mfile != '.' && $mfile != '..' ){
				$results.= '"' . $site_directory . $mfile .'",';
			}
		}
		closedir ($handler);
	}
	if (file_exists(ROOT_EDIT. $country_directory)){
		$handler = opendir (ROOT_EDIT. $country_directory);
		while ($mfile = readdir ($handler)){
			if ($mfile != '.' && $mfile != '..' ){
				$results.= '"'. $country_directory.  $mfile .'",';
			}
		}
		closedir ($handler);
	}
	if (strlen($results) > 1){
		$results = substr($results,0, -1) . ']';
		$debug .= "Styles found";
	}
	else{
		$message = "NO styles found";
        trigger_error( $message, E_USER_ERROR);
		return NULL;
	}
	$out = $results;

	return $out;

}