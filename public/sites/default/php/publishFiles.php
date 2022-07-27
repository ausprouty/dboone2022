<?php

myRequireOnce ('bookmark.php');
myRequireOnce ('copyGlobal.php');
myRequireOnce ('dirMake.php');
myRequireOnce ('createDirectory.php');
myRequireOnce ('fileWrite.php');
myRequireOnce ('getTitle.php');
myRequireOnce ('languageHtml.php');

myRequireOnce ('makePathsRelative.php');
myRequireOnce ('modifyHeaders.php');
myRequireOnce ('modifyImages.php');
//myRequireOnce ('publishCopyImagesAndStyles.php');
myRequireOnce ('publishFilesInPage.php');
myRequireOnce ('publishLanguageFooter.php');
myRequireOnce ('publishCSS.php');
myRequireOnce ('writeLog.php');
myRequireOnce ('myGetPrototypeFile.php');

// destination must be 'staging', 'website', 'pdf'  or 'sdcard'
function publishFiles( $destination , $p, $fname, $text, $standard_css, $selected_css){
    myRequireOnce ('languageSpecificJavascripts.php', $p['destination']);
    $file_name_parts = explode('/', $fname);
    $fsname = array_pop ($file_name_parts);
    $fsname =str_replace('.html', '', $fsname);
   //// writeLogDebug('publishFile-24-'. $fsname, $text);
     // some libary indexes have a name of meet.html with then gets appended with another html
    if (strpos($fname, '.html.html') !== false){
        $fname = str_replace('.html.html', '.html', $filename);
    }
    // start with header
    $output = myGetPrototypeFile('header.html', $p['destination']);
    // add onload only if files are here
    $onload_note_js = '';
    if ($destination !='nojs' && $destination !='pdf'){
        if (strpos($text, '<form') !== false){
            $pos = strrpos($fname, '/') +1;
            $filename = substr($fname, $pos);
            $note_index = $p['country_code'] .'-'. $p['language_iso'] .'-'.$p['folder_name'] .'-'.$filename;
            $onload_note_js = ' onLoad= "showNotes(\'' . $note_index . '\')" ';
            $output .= '<!--- publishFiles added onLoad -->' ."\n";
        }
    }

    if (strpos($text, '<div class="header') !== false){
        $result = modifyHeaders($text);
        $text = $result['text'];
        $headers= $result['headers'];
    }
    else{
        $headers= ' ';
    }
    if ($destination != 'staging'){
        // class="nobreak" need to be changed to class="nobreak-final" so color is correct
        $text = str_ireplace("nobreak", "nobreak-final", $text);
    }
    $title= WEBSITE_TITLE;
    if (isset($p['recnum'])){
        $title .= ' '. getTitle($p['recnum']);
    }

    $language_google = languageHtml($p['language_iso']);
    $placeholders = array(
        '{{ language.google }}',
        '{{ title }}',
        '{{ standard.css }}',
        '{{ selected.css }}',
        '{{ headers }}',
        '{{ onload-note-js }}',
        '{{ language_iso }}',
        '</html>',
        '</body>');
    $replace = array(
        $language_google,
        $title,
        $standard_css,
        $selected_css,
        $headers,
        $onload_note_js,
        $p['language_iso'],
        '',
        '');
    $output = str_replace($placeholders, $replace,  $output);
   //// writeLogDebug('publishFile-82-'. $fsname, $output);
    // insert text
    $output .= $text;
    // remove dupliate CSS
    $output = publishCSS($output, $p);

    // append footer
    $footer= 'footer.html';
    $output .= myGetPrototypeFile($footer, $p['destination'], $p['language_iso'], );
    // copy all images and styles to the publish directory
    //$response = publishCopyImagesAndStyles($output, $destination);

    $output = modifyImages($output, $p);
    // make sure  all files are copied to destination directory
    publishFilesInPage($output, $p);
    // do not make relative for websites (debugging  download serirs)
    if ($destination != 'staging' && $destination != 'website'){
      $output = makePathsRelative($output, $fname);
    }
   //// writeLogDebug('publishFile-100-'. $fsname, $output);
    fileWrite($fname, $output, $p);
    return $output;
}