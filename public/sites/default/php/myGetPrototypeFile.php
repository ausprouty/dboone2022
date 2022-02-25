<?php

function myGetPrototypeFile($filename, $subdirectory = null){
     //define("ROOT_EDIT", '/home/globa544/edit.mc2.online/');
    // define("SITE_CODE", 'mc2');
    $filename =_cleanMyGetPrototypeFile($filename);
    if ($subdirectory){
        $subdirectory =_cleanMyGetPrototypeFile($subdirectory);
        $filename =$subdirectory . '/'.$filename;
    }
   $my_prototype = ROOT_EDIT . 'sites/' . SITE_CODE . '/prototype/' . $filename;
   if (file_exists($my_prototype)){
       _append('myGetPrototypeFile', 'Used ' . $my_prototype . "\n");
       return file_get_contents($my_prototype);
   }
   $prototype =  ROOT_EDIT . 'sites/default/prototype/' . $filename;
   if (file_exists($prototype)){
       return file_get_contents($prototype);
   }
   return null;
}

function _cleanMyGetPrototypeFile($page){
	$bad = array('.', '$', '/');
	$page = str_replace($bad, '', $page);
	$page .= '.php';
	return $page;
}