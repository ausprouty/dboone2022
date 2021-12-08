<?php
function imageStore($p){
	writeLog('image-3', ' I entered image Store');
	if (!isset($p['directory'] )){
		$out['error'] = true;
		$out['debug'] = "Directory not set in uploadImage";
		$out['message'] = $out['debug'];
		return $out;

	}
    $out['debug'] = 'I am in imageStore'. "\n";
    $out['debug'] .= 'filetype: '. $_FILES['file']['type'] . "\n";
    
	if ($_FILES['file']['type'] == 'image/png' || $_FILES['file']['type'] == 'image/jpeg'|| $_FILES['file']['type'] == 'image/gif'){
   
		$dir = ROOT_EDIT. $p['directory'];  // ROOT_EDIT = ROOT_EDIT .  ''
		$out['debug'] .= 'directory: '. $dir . "\n";
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
		$out['debug'] .= 'fname: '. $fname . "\n";
		if (move_uploaded_file($_FILES["file"]["tmp_name"], $fname)) {
			$out['error'] = false;
            $out['message'] = "Image Saved";
            $out['debug'] .= "Image Saved";
		}
		else{
			$out['error'] = true;
            $out['message'] = "Image NOT Saved";
            $out['debug'] .= "Image Saved";
		}
	}
	writeLog('image-49', $out['debug']);
	return $out;
}