<?php
myRequireOnce('create.php');
myRequireOnce('copyGlobal.php');


function setupImageFolder($p){
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

function setupTemplatesLanguage($p){
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