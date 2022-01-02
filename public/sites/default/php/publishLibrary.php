<?php

myRequireOnce ('createLibrary.php');
myRequireOnce ('publishDestination.php');
myRequireOnce ('publishFiles.php');


function publishLibrary($p){

    $debug = 'In publishLibrary '. "\n";
    //
    // get data for current library
    //
    $filename =  $p['library_code'];
    $sql = "SELECT text FROM content
        WHERE  country_code = '" . $p['country_code']. "'
        AND language_iso = '". $p['language_iso'] ."'
        AND folder_name = '' AND filename = '$filename'
        AND prototype_date IS NOT NULL
        ORDER BY recnum DESC LIMIT 1";
    $debug .= $sql. "\n";
    $data = sqlArray($sql);
    $text = json_decode($data['text']);
     // set style
     if (isset($text->format->style)){
        $selected_css = $text->format->style;
    }
    else{
        $selected_css = '/sites/default/styles/cardGLOBAL.css';
    }
    $res = createLibrary($p, $text);
    $body = $res['body'];
    $p['books'] = $res['books'];

    //
    // write file
    // if filename == 'library', switch to 'index' because it means there is no
    // LibraryIndex file
    //
    $dir  = publishDestination($p). $p['country_code'] . '/'. $p['language_iso'] .'/';
    if ($filename == 'library'){
        $filename = 'index';
    }
    $fname = $dir . $filename . '.html';
    $body .= '<!--- Created by publishLibrary-->' . "\n";
    publishFiles( $p['destination'], $p, $fname, $body, STANDARD_CARD_CSS, $selected_css);

     //
    // update records
    //
    $time = time();
    $sql = "UPDATE content
        SET publish_date = '$time', publish_uid = '". $p['my_uid'] . "'
        WHERE country_code = '". $p['country_code']. "'
        AND language_iso = '" . $p['language_iso'] ."'
        AND folder_name = ''
        AND prototype_date IS NOT NULL
        AND publish_date IS NULL";
    $debug .= $sql. "\n";
    sqlArray($sql,'update');
    return $p;
}
