<?php
myRequireOnce ('dirCreate.php');
myRequireOnce ('fileWrite.php');
myRequireOnce ('writeLog.php');

// use country and language
// this looks for template in the language/templates directory

function editTemplate($p){
	$debug = 'getTemplate'. "\n";
	if (!$p['language_iso']){
		$message = "language_iso not set";
    writeLogError('editTemplate-11', $message );
		return false;
	}
	if (!$p['template']){
			$message = "template not set";
      writeLogError('editTemplate-11', $message );
      return false;
  }
  if (!$p['text']){
    $message = "text not set";
    writeLogError('editTemplate-11', $message );
    return false;
  }
  if (!$p['book_format']){
    $message = "book_format not set";
    writeLogError('editTemplate-11', $message );
    return false;
  }
  // $template_dir = ROOT_EDIT_CONTENT . $p['country_code'] .'/'. $p['language_iso'] .'/templates/';
  $template_dir= dirCreate('language', 'edit',  $p,  'templates/') ;

  // make sure this is an html file
  if (strpos($p['template'], '.html') === FALSE){
      $p['template'] .= '.html';
  }
  $filename = $template_dir . $p['template'];
  fileWrite($filename, $p['text'], $p);
	return 'success';
}