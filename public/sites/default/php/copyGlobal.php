<?php

/*
/    // copies all files in directory and removes GLOBAL from the name
*/
function copyGlobal($source, $destination){
	
	$out = array();
	$debug = "Asked to copy $source to $destination \n";
	
	if (file_exists($source)){
		//$debug = 'Source exists: '. $source . "\n";
		$handler = opendir ($source);
		while ($mfile = readdir ($handler)){
			if ($mfile != '.' && $mfile != '..' ){
				$setup_file = $source . $mfile;
				if (!is_dir($setup_file)){
					$newfile = str_replace('GLOBAL', '', $mfile);
					$destination_file = $destination . $newfile;
					//$debug .= 'Destination File: '. $destination_file . "\n";
					if (!file_exists($destination_file)){
						//$debug .= 'Does not exist'. "\n";
						if (!is_dir($destination_file)){
								//$debug .= 'Is not directory: ' . "\n";
							if (strpos($setup_file, '.') !== FALSE){
								//$debug .= 'Has a dot: ' . "\n";
								copy ($setup_file, $destination_file);
								$debug .=  ' copied ' .  $setup_file . ' to ' . $destination_file . "\n\n";
							}
						}
					}
					else{
						$debug = 'Destination exists: '. $destination_file . "\n\n";
					}
				}
			}
		}
	}
	return $out;
}
