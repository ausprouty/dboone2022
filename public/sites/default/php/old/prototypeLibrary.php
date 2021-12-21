<?php
myRequireOnce ('publishFiles.php');
myRequireOnce ('createLibrary.php');


function prototypeLibrary($p){
    $p['status'] = 'prototype';
   
    $p['language_dir'] = ROOT_PROTOTYPE_CONTENT . $p['country_code'] . '/'. $p['language_iso'] .'/';
    $p['debug'] .= 'In prototypeLibrary and ROOT_PROTOTYPE_CONTENT is '. ROOT_PROTOTYPE_CONTENT ."\n";
    //
    // get data for current library
    //
    $filename =  $p['library_code']; 
    $sql = "SELECT * FROM content 
        WHERE  country_code = '" . $p['country_code']. "'  
        AND language_iso = '". $p['language_iso'] ."' 
        AND folder_name = '' AND filename = '$filename' 
        ORDER BY recnum DESC LIMIT 1";
    $p['debug'] .= $sql. "\n";
    $data = sqlArray($sql);
    $p['debug'] .= isset($data['recnum']) ? $data['recnum'] : 'No Recnum Found';
    $p['debug'] .= $data['text']. "\n";
    $text = json_decode($data['text']);
    // set style
    if (isset($text->format->style)){
        $p['debug'] .= 'style is set '. "\n";
        $selected_css = $text->format->style;
        $p['debug'] .= $selected_css. "\n";
    }
    else{
        $p['debug'] .= 'style is NOT set '. "\n";
        $selected_css = '/sites/default/styles/cardGLOBAL.css';
    }

    $out = createLibrary($p, $text); // see createLibrary.php
    $p = $out['p'];
    $body = $out['body'];
    //
    // write file
    // if filename == 'library', switch to 'index' because it means there is no
    // LibraryIndex file
    if ($filename == 'library'){
        $filename = 'index';
    }

    $fname = $p['language_dir'] . $filename . '.html';
    $body .= '<!--- Created by prototypeLibrary-->' . "\n";
    publishFiles( $p['destination'], $p, $fname, $body, STANDARD_CARD_CSS, $selected_css);

     //
    // update records
    //
    $time = time();
    $sql = "UPDATE content 
        SET prototype_date = '$time', prototype_uid = '". $p['my_uid'] . "'
        WHERE country_code = '". $p['country_code']. "' 
        AND language_iso = '" . $p['language_iso'] ."' 
        AND folder_name = ''
        AND prototype_date IS NULL";
    $p['debug'] .= $sql. "\n";
    sqlArray($sql,'update');
    return $p;
}
