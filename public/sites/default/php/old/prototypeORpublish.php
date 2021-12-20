<?php

myRequireOnce('bookmark.php');
myRequireOnce('copyGlobal.php');
myRequireOnce('createDirectory.php');
myRequireOnce('getTitle.php');
myRequireOnce('languageSpecificJavascripts.php');
myRequireOnce('makePathsRelative.php');
myRequireOnce('modifyHeaders.php');
myRequireOnce('modifyImages.php');
myRequireOnce('prototypeCopyImagesAndStyles.php');
myRequireOnce('prototypeLanguageFooter.php');
myRequireOnce('prototypeRemoveDuplicateCSS.php');
myRequireOnce('version2Text.php');
myRequireOnce('writeLog.php');


// $scope must be 'publish' or 'prototype'
function publishFiles( $scope , $p, $fname, $text, $standard_css, $selected_css){

    // start with header
    $output = myGetPrototypeFile('header.html');
    //$p['debug'] .= "\n". 'publishFiles' . "\n";
    $out['debug'] = 'In publishFiles with: ' . $fname .  "\n";
    // add onload only if files are here
    $onload_note_js = '';
    if (strpos($text, '<form') !== false){
        $pos = strrpos($fname, '/') +1;
        $filename = substr($fname, $pos);
        $note_index = $p['country_code'] .'-'. $p['language_iso'] .'-'.$p['folder_name'] .'-'.$filename;
        $onload_note_js = ' onLoad= "showNotes(\'' . $note_index . '\')" ';
        $output .= '<!--- PrototypeOrpublish added onLoad -->' ."\n";
        $out['debug'] =   $onload_note_js  ."\n";
        $out['debug'] =   $output  ."\n";
    }
    if (strpos($text, '<div class="header') !== false){
        $result = modifyHeaders($text);
        $text = $result['text'];
        $headers= $result['headers'];
    }
    else{
        $headers= '';
    }
    if ($scope == 'publish'){
        // class="nobreak" need to be changed to class="nobreak-final" so color is correct
        $text = str_ireplace("nobreak", "nobreak-final", $text);
    }
    $result = getTitle($p['recnum']);
    $out['debug'] .= $result['debug'] ."\n";
    $title = WEBSITE_TITLE . $result['content'];
    $out['debug'] .= 'title is '. $title ."\n";
    $local_js = '<script> This is my script</script>';
    $placeholders = array(
        '{{ title }}',
        '{{ standard.css }}',
        '{{ selected.css }}',
        '{{ headers }}',
        '{{ onload-note-js }}',
        '</html>',
        '</body>');
    $replace = array(
        $title,
        $standard_css,
        $selected_css,
        $headers,
        $onload_note_js,
        '',
        '');
    $output = str_replace($placeholders, $replace,  $output);
    // insert text
    $output .= $text;
    // remove dupliate CSS
    $output = prototypeRemoveDuplicateCSS($output);
    // append footer
    $output .= myGetPrototypeFile('footer.html');
    // copy all images and styles to the prototype directory
    $response = prototypeCopyImagesAndStyles($output, $scope);
    $output = modifyImages($output, $scope);
    if (isset($p['usb'])){
      $output = makePathsRelative($output, $fname);
    }
    // make sure we have all the necessary directories
    dirMake($fname);
    // write the file
    $fh = fopen($fname, 'w');
    if ($fh){
        $out['debug'] .= 'File Written to ' .  $fname . "\n";
        fwrite($fh, $output);
        fclose($fh);
    }
    else{
        $out['debug'] .= 'NOT able to write' .  $fname . "\n";
        $out['error'] = true;
    }
    return ($out);
}
function prototypeCopyDir($source, $destination){
    if (!file_exists($destination)){
        dirMake ($destination);
    }
    copyGlobal($source, $destination);
    return $out;
}
