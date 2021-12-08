<?php

//// add content to database
function createHelpContent($p){
	$out['debug'] = "\n\n\n\n\n" . 'In createContent'. "\n";
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
		
		$sql = "INSERT into help_content (version,edit_date,edit_uid,language_iso,
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
	$out['debug'] .= $sql . "\n";
	$out['debug'] .= $out['message'] . "\n";
	return $out;
}
