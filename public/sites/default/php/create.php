<?php
myRequireOnce('dirCreate.php');

//// add content to database
function createContent($p){
	$debug = "\n\n\n\n\n" . 'In createContent'. "\n";
	$text = isset($p['text']) ? $p['text'] : NULL;
	if (!$text){
		$message = "in createContent $p[text] can not be null";
        trigger_error( $message, E_USER_ERROR);

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
		$result = $conn->query($sql);
		if(!$result){
			$message = "Could not add Content";
        	trigger_error( $message, E_USER_ERROR);

		}
		else{
			$message = "Success";
		}
	}
	return $message;
}

// create directory
function createContentFolder($p){
	//$dir = ROOT_EDIT_CONTENT . $p['country_code']. '/'. $p['language_iso'] . '/'. $p['$folder_name'];
    $dir =dirCreate('series', 'edit',  $p);

	return "Success";
}
function createDirectoryLanguages($p){
	$languages = json_decode ($p['text']);
	foreach ($langauges as $language_iso){
		$dir =dirCreate('language', 'edit',  $p);
	}
	return "Success";
}

//
// create series index; I can not see any reason to do this.
// called by Library Edit
function createSeriesIndex($p){
	$debug = "I could not see any reason to createSeriesIndex\n";
	return TRUE;

	$debug = 'createSeriesIndex'. "\n";
	if (!isset($p['folder_name'])){
		$message = "Folder Name not set";
        trigger_error( $message, E_USER_ERROR);
		return NULL;
	}
	$content = '[]';
	$file_index = dirCreate('series', 'edit',  $p) .'index.html';
	$debug .= 'index: ' . $file_index  ."\n";
	if (!file_exists($file_index )){
		$fh = fopen($file_index , 'w');
		fwrite($fh, $content);
		fclose($fh);
	}
	return $out;
}
function createStyle($p){
	if (!isset($p['country_code'] )){
		$message =  "Country code not set in create Style";
		writeLogError('createStyle', $message);
        trigger_error( $message, E_USER_ERROR);
		return NULL;

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
		$dir = dirCreate('country', 'edit',  $p) . 'styles/';

		$fname = $dir. $_FILES["file"]["name"];
		$debug .= 'fname: '. $fname . "\n";
		if (move_uploaded_file($_FILES["file"]["tmp_name"], $fname)) {
		   $message = "Style Saved";
		}
		else{
			$message = "Style NOT Saved";
            trigger_error( $message, E_USER_ERROR);
			return NULL;
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
			$dir = dirCreate('language', 'edit',  $p) . 'templates/'. $p['$folder_name'] ;
			$fname = $dir . '/'. $_FILES["file"]["name"];

			if (move_uploaded_file($_FILES["file"]["tmp_name"], $fname)) {

				$debug .= "Style Saved";
			}
			else{
				$message = "Style NOT Saved";
                trigger_error( $message, E_USER_ERROR);
			}
		}
	}
	return $out;
}
