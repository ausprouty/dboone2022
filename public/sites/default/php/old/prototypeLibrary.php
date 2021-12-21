<?php
myRequireOnce ('publishFiles.php');
myRequireOnce ('createLibrary.php');


function prototypeLibrary($p){

    $p['language_dir'] = ROOT_PROTOTYPE_CONTENT . $p['country_code'] . '/'. $p['language_iso'] .'/';
    $debug .= 'In prototypeLibrary and ROOT_PROTOTYPE_CONTENT is '. ROOT_PROTOTYPE_CONTENT ."\n";
    //
    // get data for current library
    //
    $filename =  $p['library_code'];
    $sql = "SELECT * FROM content
        WHERE  country_code = '" . $p['country_code']. "'
        AND language_iso = '". $p['language_iso'] ."'
        AND folder_name = '' AND filename = '$filename'
        ORDER BY recnum DESC LIMIT 1";
    $debug .= $sql. "\n";
    $data = sqlArray($sql);
    $debug .= isset($data['recnum']) ? $data['recnum'] : 'No Recnum Found';
    $debug .= $data['text']. "\n";
    $text = json_decode($data['text']);
    // set style
    if (isset($text->format->style)){
        $debug .= 'style is set '. "\n";
        $selected_css = $text->format->style;
        $debug .= $selected_css. "\n";
    }
    else{
        $debug .= 'style is NOT set '. "\n";
        $selected_css = '/sites/default/styles/cardGLOBAL.css';
    }

    $body = createLibrary($p, $text); // see createLibrary.php

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
    $sql = '';
    if ($p['destination'] == 'prototype'){
        $sql = "UPDATE content
            SET prototype_date = '$time', prototype_uid = '". $p['my_uid'] . "'
            WHERE country_code = '". $p['country_code']. "'
            AND language_iso = '" . $p['language_iso'] ."'
            AND folder_name = ''
            AND prototype_date IS NULL";
    }
    if ($p['destination'] == 'publish'){
        $sql = "UPDATE content
            SET publish_date = '$time', publish_uid = '". $p['my_uid'] . "'
            WHERE country_code = '". $p['country_code']. "'
            AND language_iso = '" . $p['language_iso'] ."'
            AND folder_name = ''
            AND publish_date IS NULL";
    }
    if ($sql){
       sqlArray($sql,'update');
    }

    writeLog('publishLibrary', $debug);

    return true;
}
