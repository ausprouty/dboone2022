<?php

/*
/    // copies all files in directory and removes GLOBAL from the name
      //  but does NOT overwrite existing files
*/
function copyGlobal($source, $destination){
	$out = array();

	if (file_exists($source)){
		$handler = opendir ($source);
		while ($mfile = readdir ($handler)){
			if ($mfile != '.' && $mfile != '..' ){
				$setup_file = $source . $mfile;
				if (!is_dir($setup_file)){
					$newfile = str_replace('GLOBAL', '', $mfile);
					$destination_file = $destination . $newfile;
					if (!file_exists($destination_file)){
						if (!is_dir($destination_file)){
							if (strpos($setup_file, '.') !== FALSE){
								copy ($setup_file, $destination_file);

							}
						}
					}
				}
			}
		}
	}
	return $out;
}
