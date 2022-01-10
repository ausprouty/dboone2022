<?php
myRequireOnce('dirCreate.php');

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
   // $template_dir = ROOT_EDIT_CONTENT . $p['country_code'] .'/'. $p['language_iso'] .'/templates/';
    $template_dir= dirCreate('language', 'edit',  $p,  'templates/') ;

    // make sure this is an html file
    if (strpos($p['template'], '.html') === FALSE){
        $p['template'] .= '.html';
    }
    $destination = $template_dir . $p['template'];

    $fh = fopen($destination , 'w');
    fwrite($fh, $p['text']);
    fclose($fh);

	return $out;
}