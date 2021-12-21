<?php

//// add content to database
function createContent($p){
	$debug = "\n\n\n\n\n" . 'In createContent'. "\n";
	$text = isset($p['text']) ? $p['text'] :NULL;
	if (!$text){
		$out['error'] = true;
		$out['message'] = '$p[text] can not be null';
		return $out;
	}
	else{

		$version = isset($p['version']) ? $p['version'] : VERSION;
		$edit_date = time();
		$my_uid = isset($p['my_uid']) ? $p['my_uid'] : NULL;
		$language_iso = isset($p['language_iso']) ? $p['language_iso'] : NULL;
		$country_code = isset($p['country_code']) ? $p['country_code'] : NULL;
		$folder_name = isset($p['folder_name']) ? $p['folder_name'] :'';
		$filetype = isset($p['filetype']) ? $p['filetype'] : NULL;
		$title = isset($p['title']) ? $p['title'] : NULL;
		$filename = isset($p['filename']) ? $p['filename'] : NULL;
		$page = isset($p['page']) ? $p['page'] : NULL;
		
		$conn = new mysqli(HOST, USER, PASS, DATABASE_CONTENT);
		$text = $conn->real_escape_string($text);
		
		$sql = "INSERT into content (version,edit_date,edit_uid,language_iso,
			country_code,folder_name,filetype,title,filename, page, text) values 
			('$version','$edit_date','$my_uid','$language_iso',
			'$country_code','$folder_name','$filetype','$title','$filename','$page','$text')";
		$query = $conn->query($sql);
		if($query){
			$out['message'] = "Content Added Successfully";
		}
		else{
			$out['error'] = true;
			$out['message'] = "Could not add Content";
		}
	}
	$debug .= $sql . "\n";
	$debug .= $out['message'] . "\n";
	return $out;
}

// create directory
function createContentFolder($p){
	$debug = 'createContentFolder'. "\n";
	
	$dir = ROOT_EDIT_CONTENT . $p['country_code']. '/'. $p['language_iso'] . '/'. $p['$folder_name'];
	$debug .= 'dir: ' . $dir ."\n";
	if (!file_exists($dir)){
		dirMake ($dir);
	}
	return $out;
}
function createDirectoryLanguages($p){
	$debug = 'createDirectoryLanguages'. "\n";
	$dir_country = ROOT_EDIT_CONTENT . $p['country_code'] .'/';
	$languages = json_decode ($p['text']);
	foreach ($langauges as $language_iso){
		$dir = $dir_country . $language_iso;
		$debug .= 'dir: ' . $dir ."\n";
		if (!file_exists($dir)){
			dirMake ($dir);
		}
	}
	return $out;
}

// create directory
function createDir($p){
	$debug = 'createDir'. "\n";
	switch ($scope){
		case 'country':
			$dir = ROOT_EDIT_CONTENT . $p['country_code'];
			break;
		case 'language':
		$dir = ROOT_EDIT_CONTENT . $p['country_code'] . '/'. $p['language_iso'];
			break;
		case 'folder':
			break;
	}
	$debug .= 'dir: ' . $dir ."\n";
	if (!file_exists($dir)){
		dirMake ($dir);
	}
	return $out;
	
}
// create series index; I can not see any reason to do this.
function createSeriesIndex($p){
	$debug = "I could not see any reason to createSeriesIndex\n";
	return $out;

	$debug = 'createSeriesIndex'. "\n";
	if (!isset($p['folder_name'])){
		$debug = 'Folder Name not set'. "\n";
		$out['error'] = true;
		return $out;
	}
	$content = '[]';
	$file_index = ROOT_EDIT_CONTENT . $p['country_code'] . '/'. $p['language_iso'] . '/'. $p['folder_name'] .'/index.html';
	$debug .= 'index: ' . $file_index  ."\n";
	if (!file_exists($file_index )){
		dirMake($file_index);
		$fh = fopen($file_index , 'w');
		fwrite($fh, $content);
		fclose($fh);
	}
	return $out;
}
function createStyle($p){
	if (!isset($p['country_code'] )){
		$out['error'] = true;
		$debug = "Country code not set in create Style";
		$out['message'] = $debug;
		return $out;

	}
	$debug = 'createStyle'. "\n";
	switch ($_FILES['file']['type']){
		case 'text/css':
		   $type = '.css';
		   $valid = true;
		   break;
		default:
			$valid = false;
    }
	if ($valid){
		$dir = ROOT_EDIT_CONTENT . $p['country_code'] . '/styles/';
		$debug .= 'directory: '. $dir . "\n";
		if (!file_exists($dir)){
			dirMake ($dir);
		}
		$fname = $dir. $_FILES["file"]["name"];
		$debug .= 'fname: '. $fname . "\n";
		if (move_uploaded_file($_FILES["file"]["tmp_name"], $fname)) {
			$out['error'] = false;
			$out['message'] = "Style Saved";
		}
		else{
			$out['error'] = true;
			$out['message'] = "Style NOT Saved";
		}
	}
	return $out;
}

function createTemplate ($p){
	$debug = 'createTemplate'. "\n";
	switch ($_FILES['file']['type']){
		case 'text/html':
		   $type = '.html';
		   $valid = true;
		   break;
		default:
			$valid = false;
	}
	if ($valid){
		if (isset($p['$folder_name'])){
			$dir = ROOT_EDIT_CONTENT . $p['country_code'] . '/'.  $p['language_iso'] . '/templates/'. $p['$folder_name'] ;
			if (!file_exists($dir)){
				dirMake ($dir);
			}
			$fname = $dir . '/'. $_FILES["file"]["name"];

			if (move_uploaded_file($_FILES["file"]["tmp_name"], $fname)) {
				$out['error'] = false;
				$out['message'] = "Style Saved";
			}
			else{
				$out['error'] = true;
				$out['message'] = "Style NOT Saved";
			}
		}
		
	}
	return $out;
}
