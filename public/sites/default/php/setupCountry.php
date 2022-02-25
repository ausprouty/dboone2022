<?php
myRequireOnce ('dirCreate.php');



function setupCountry($p){
	if (!isset($p['country_code'])){
		$message = 'country code not set in setupCountry';
        writeLogError('setupCountry-9-p', $p);
		return false;
	}
	dirCreate('country', 'edit',  $p, 'images/standard');
	dirCreate('country', 'edit',  $p, 'images/custom');
	dirCreate('country', 'edit',  $p, 'templates/');
	dirCreate('country', 'edit',  $p, 'javascript/');

	$out = 'success';
	return $out;
}