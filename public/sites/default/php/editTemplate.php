<?php

// use country and language
// this looks for template in the language/templates directory

function editTemplate($p){
	$debug = 'getTemplate'. "\n";
	if (!$p['language_iso']){
		$debug .= "language_iso not set\n";
		return $out;
	}
	if (!$p['template']){
		$debug .= "template not set\n";
		return $out;
    }
    if (!$p['text']){
		$debug .= "no text\n";
		return $out;
    }
    if (!$p['book_format']){
		$debug .= "book_format not set\n";
		return $out;
    }
    $template_dir = ROOT_EDIT_CONTENT . $p['country_code'] .'/'. $p['language_iso'] .'/templates/';
    //if ($p['book_format'] == 'series'){
    //    $template_dir.= $p['book_code'] .'/';
    //}
    // create any needed directories
    if (!file_exists($template_dir )){
        dirMake($template_dir);
    }
    // make sure this is an html file
    if (strpos($p['template'], '.html') === FALSE){
        $p['template'] .= '.html';
    }
    $destination = $template_dir . $p['template'];
	$debug .=' destination is '. $destination. "\n";
	//if (file_exists($destination)){
  //      $newname = str_ireplace('.html', '.bak'. time(), $destination);
//		rename ($destination, $newname);// already exists
//	}
    $fh = fopen($destination , 'w');
    fwrite($fh, $p['text']);
    fclose($fh);
	$out['error'] = false;
	return $out;
}