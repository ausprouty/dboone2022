<?php

myRequireOnce ('bookmark.php');
myRequireOnce ('copyGlobal.php');
myRequireOnce ('dirMake.php');
myRequireOnce ('createDirectory.php');
myRequireOnce('fileWrite.php');
myRequireOnce ('getTitle.php');
myRequireOnce ('languageSpecificJavascripts.php');
myRequireOnce ('makePathsRelative.php');
myRequireOnce ('modifyHeaders.php');
myRequireOnce ('modifyImages.php');
myRequireOnce ('publishCopyImagesAndStyles.php');
myRequireOnce ('publishDestination.php');
myRequireOnce ('publishFilesInPage.php');
myRequireOnce ('publishLanguageFooter.php');
myRequireOnce ('publishCSS.php');

myRequireOnce ('writeLog.php');


// destination must be 'staging', 'publish', or 'sdcard'
function publishFiles( $destination , $p, $fname, $text, $standard_css, $selected_css){

    $debug = 'In publishFiles with: ' . $fname .  "\n";
    //writeLog('publishFiles-22-fname', $debug);
    // start with header
    $output = myGetPrototypeFile('header.html', $p['destination']);
    //$debug .= "\n". 'publishFiles' . "\n";
     //writeLog('publishFiles-26-header', $output);
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
    if ($destination != 'staging'){
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
     //writeLog('publishFiles-72-text', $output);
    // remove dupliate CSS
    $output = publishCSS($output, $p);
     //writeLog('publishFiles-75-text', $output);
    // append footer
    $output .= myGetPrototypeFile('footer.html', $p['destination']);
     //writeLog('publishFiles-78-myGetPrototypeFile-text', $output);
    // copy all images and styles to the publish directory
    //$response = publishCopyImagesAndStyles($output, $destination);

    $d['destination'] =$destination;
    $output = modifyImages($output, $d);
    //writeLog('publishFiles-86-modifyImages-text', $output);
    // make sure  all files are copied to destination directory
    publishFilesInPage($output, $d);
    $output = makePathsRelative($output, $fname);
    fileWrite($fname, $output, $p['destination']);

    //writeLog('publishFiles-100', $output);
    return $output;
}
