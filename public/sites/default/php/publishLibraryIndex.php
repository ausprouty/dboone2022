<?php

myRequireOnce ('publishDestination.php');
myRequireOnce ('publishFiles.php');


function publishLibraryIndex($p){

    $debug = 'in prototypeLibraryIndex' . "\n";
    //$selected_css = '/content/AU/styles/AU-freeform.css';
     //
    //find country page from recnum
    //
    $sql = 'SELECT * FROM content  WHERE  recnum = "'.  $p['recnum'] . '"';
    //$debug .= $sql. "\n";
    $data = sqlArray($sql);
    if (!$data){
        $message = "in PublishLibraryIndex with no data from  recnum ";
        trigger_error( $message, E_USER_ERROR);
        return ($p);
    }
    $text = json_decode($data['text']);
    $selected_css = $text->style;
     // get language footer in prototypeOEpublish.php
    $footer = publishLanguageFooter($p);
    // replace placeholders
    $body = '<div class="content">'. "\n";
    $body .= $text->page . "\n";
    $body = str_replace('/preview/library', '/content', $body);
    // see if anyone is bypassing the library (there is only one book in this language)
    if (strpos($body, '/preview/series/')){
        $debug.= 'I bypassed library at least once' ."\n";
        $body = bypassLibrary($body);
    }
    $body = $body .  $footer  ;
    // set file name
    $language_dir = publishDestination($p) . $p['country_code'] . '/'. $p['language_iso'];
    $fname = $language_dir . '/index.html';
    // write  file
    $body .= '<!--- Created by publishLibrary-->' . "\n";
    publishFiles( $p['destination'], $p, $fname, $body,   STANDARD_CSS,  $selected_css);
    //TODO: make this a variable
    // Australia is the current owner of this site, so their file goes to root
    if ($fname  ==  publishDestination($p) .'AU/eng/index.html'){
        $fname = publishDestination($p). 'index.html';
        $debug .= 'I am sending Australian index to  ' . $fname;
        publishFiles( $p['destination'], $p, $fname, $body,   STANDARD_CSS,  $selected_css);
        $fname = publishDestination($p). 'index.html';
        $debug .= 'I am sending Australian index to  ' . $fname;
        publishFiles( $p['destination'], $p, $fname, $body,   STANDARD_CSS,  $selected_css);
    }
    // update records
    //
    $time = time();
    $sql = null;
    if ($p['destination'] == 'prototype'){
        $sql = "UPDATE content
            SET prototype_date = '$time', prototype__uid = '". $p['my_uid'] ."'
            WHERE  country_code = '" . $p['country_code'] ."'
            AND folder_name = '' AND filename = 'index'
            AND prototype_date IS NOT NULL";
    }
    if ($p['destination'] == 'publish'){
        $sql = "UPDATE content
            SET publish_date = '$time', publish_uid = '". $p['my_uid'] ."'
            WHERE  country_code = '" . $p['country_code'] ."'
            AND folder_name = '' AND filename = 'index'
            AND prototype_date IS NOT NULL
            AND publish_date IS NULL";
    }
    if ($sql){
      sqlArray($sql, 'update');
    }
    writeLog('publishLibraryIndex', $debug);
    return true;
}


/* experimental area to see if we can bypass library
  'preview/series/AU/eng/family/youth-basics'
 needs to be changed to
 'content/AU/eng/youth-basics/index.html'
*/
function bypassLibrary ($body){
    $debug = 'In _bypassLibrary' . "\n";
    $count = substr_count($body, '/preview/series/');
    $debug .=   'Count is ' . $count  . "\n";
    for ($i = 1; $i <= $count; $i++){
        $start = strpos($body, '/preview/series/');
        $end = strpos($body, '"', $start);
        $len = $end - $start;
        $link1 = substr($body, $start,$len ); //preview/series/AU/eng/family/youth-basics
        $parts = explode('/',  $link1);
        $link2 =   '/content/' . $parts[3] .'/'. $parts[4] .'/' .$parts[6] ;
        $res['debug'] .= $link1 . ' replaceD by '. $link2 . "\n";
        $body = str_replace($link1, $link2, $body);
    }
    return $body;
}