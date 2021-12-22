<?php

myRequireOnce ('bookmark.php');
myRequireOnce ('copyGlobal.php');
myRequireOnce ('createDirectory.php');
myRequireOnce ('getTitle.php');
myRequireOnce ('languageSpecificJavascripts.php');
myRequireOnce ('makePathsRelative.php');
myRequireOnce ('modifyHeaders.php');
myRequireOnce ('modifyImages.php');
myRequireOnce ('publishCopyImagesAndStyles.php');
myRequireOnce ('publishLanguageFooter.php');
myRequireOnce ('publishRemoveDuplicateCSS.php');
myRequireOnce ('version2Text.php');
myRequireOnce ('writeLog.php');


// $scope must be 'publish' or 'publish'
function publishFiles( $scope , $p, $fname, $text, $standard_css, $selected_css){

    $debug = 'In publishFiles with: ' . $fname .  "\n";
    writeLog('publishFiles-22', $debug);
    // start with header
    $output = myGetPrototypeFile('header.html');
    //$debug .= "\n". 'publishFiles' . "\n";
     writeLog('publishFiles-26', $output);
    // add onload only if files are here
    $onload_note_js = '';
    if (strpos($text, '<form') !== false){
        $pos = strrpos($fname, '/') +1;
        $filename = substr($fname, $pos);
        $note_index = $p['country_code'] .'-'. $p['language_iso'] .'-'.$p['folder_name'] .'-'.$filename;
        $onload_note_js = ' onLoad= "showNotes(\'' . $note_index . '\')" ';
        $output .= '<!--- publishFiles added onLoad -->' ."\n";
        $debug =   $onload_note_js  ."\n";
        $debug =   $output  ."\n";
    }
    if (strpos($text, '<div class="header') !== false){
        $result = modifyHeaders($text);
        $text = $result['text'];
        $headers= $result['headers'];
    }
    else{
        $headers= '';
    }
    if ($scope != 'prototype'){
        // class="nobreak" need to be changed to class="nobreak-final" so color is correct
        $text = str_ireplace("nobreak", "nobreak-final", $text);
    }
    $title = WEBSITE_TITLE . getTitle($p['recnum']);
    $debug .= 'title is '. $title ."\n";
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
     writeLog('publishFiles-72', $output);
    // remove dupliate CSS
    $output = publishRemoveDuplicateCSS($output);
     writeLog('publishFiles-75', $output);
    // append footer
    $output .= myGetPrototypeFile('footer.html');
     writeLog('publishFiles-78', $output);
    // copy all images and styles to the publish directory
    //$response = publishCopyImagesAndStyles($output, $scope);
    $output = modifyImages($output, $scope);
    writeLog('publishFiles-82', $output);
    $output = makePathsRelative($output, $fname);
    writeLog('publishFiles-84', $output);

    // make sure we have all the necessary directories
    dirMake($fname);
    // write the file
    $fh = fopen($fname, 'w');
    if ($fh){
        $debug .= 'File Written to ' .  $fname . "\n";
        fwrite($fh, $output);
        fclose($fh);
    }
    else{
        $message = " 'NOT able to write' .  $fname";
        trigger_error( $message, E_USER_ERROR);

    }
    writeLog('publishFiles-100', $output);
    return $out;
}
