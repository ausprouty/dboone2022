<?php
function imageStore($p){
	writeLog('image-3', ' I entered image Store');
	if (!isset($p['directory'] )){
		$out['error'] = true;
		$debug = "Directory not set in uploadImage";
		$out['message'] = $debug;
		return $out;

	}
    $debug = 'I am in imageStore'. "\n";
    $debug .= 'filetype: '. $_FILES['file']['type'] . "\n";
    
	if ($_FILES['file']['type'] == 'image/png' || $_FILES['file']['type'] == 'image/jpeg'|| $_FILES['file']['type'] == 'image/gif'){
   
		$dir = ROOT_EDIT. $p['directory'];  // ROOT_EDIT = ROOT_EDIT .  ''
		$debug .= 'directory: '. $dir . "\n";
		if (!file_exists($dir)){
			dirMake ($dir);
		}
		$name = $_FILES["file"]["name"];
		if (isset($p['rename'])){
			switch ($_FILES['file']['type']){
				case 'image/png':
				  $name = $p['rename'] . '.png';
				break;
				case 'image/jpeg':
					$name = $p['rename'] . '.jpg';
				break;
				case 'image/gif':
					$name = $p['rename'] . '.gif';
				break;
			}

		}
		$fname = $dir. '/'. $name;
		$debug .= 'fname: '. $fname . "\n";
		if (move_uploaded_file($_FILES["file"]["tmp_name"], $fname)) {
			$out['error'] = false;
            $out['message'] = "Image Saved";
            $debug .= "Image Saved";
		}
		else{
			$out['error'] = true;
            $out['message'] = "Image NOT Saved";
            $debug .= "Image Saved";
		}
	}
	writeLog('image-49', $debug);
	return $out;
}