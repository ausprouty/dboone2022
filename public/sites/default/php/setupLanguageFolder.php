<?php
//myRequireOnce ('create.php');
//myRequireOnce ('getLatestContent.php');
//myRequireOnce ('setup.php');
//myRequireOnce ('copyGlobal.php');

myRequireOnce ('dirCreate.php');

function setupLanguageFolder($p){

$debug = ' Entered setupLanguageFolder'."\n";
	if (!isset($p['country_code'])){
		$message = 'country code not set in setupLanguageFolder';
        writeLogError('setupLanguage-13-p', $p);
		return false;

	}
	if (!isset($p['language_iso'])){
		$message = 'langauge iso not set in setupLanguageFolder';
        writeLogError('setupLanguage-19-p', $p);
		return false;
	}

	dirCreate('language', 'edit',  $p, 'images/standard');
	dirCreate('language', 'edit',  $p, 'images/custom');
	dirCreate('language', 'edit',  $p, 'templates/');
	dirCreate('language', 'edit',  $p, 'javascript/');
	return true;
}
