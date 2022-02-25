<?php
myRequireOnce ('create.php');
myRequireOnce ('copyGlobal.php');
myRequireOnce ('dirCreate.php');
myRequireOnce ('writeLog.php');


function setupImageFolder($p){
	$debug = 'setupImageFolder'. "\n";
	if (!isset($p['language_iso'])){
		$debug = 'language_iso not set'."\n";
		return $out;
	}
	copyGlobal(dirCreate('country', 'edit',  $p, $folders = 'images/standard/'),
	           dirCreate('language', 'edit',  $p, $folders = 'images/standard/'));
	$out = 'success';
	return $out;
}

// only set up directory for styles;
// do NOT copy styles into here
// encouarage people to use the ZZ folder

function setupTemplatesLanguage($p){
	$count = 0;
	if (!isset($p['language_iso'])){
		$debug = 'language_iso not set'."\n";
		return $out;
	}
	$template_dir = dirCreate('country', 'edit',  $p, $folders = 'templates');
	$p['folder_name'] = array();
	$dir = new DirectoryIterator($template_dir);
	foreach ($dir as $fileinfo) {
		if ($fileinfo->isDir() && !$fileinfo->isDot()) {
			$folders = 'templates/'.  $fileinfo->getFilename() . '/';
			writeLogError('setupTemplatesLanguage-'. $count, $folders);
			$count++;
			copyGlobal(dirCreate('country', 'edit',  $p, $folders),
	                   dirCreate('language', 'edit',  $p, $folders));
	        $out = 'success';
		}
	}
	$out = 'success';
	return $out;
}