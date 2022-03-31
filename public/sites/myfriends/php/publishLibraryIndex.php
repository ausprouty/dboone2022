<?php

myRequireOnce ('publishDestination.php');
myRequireOnce ('publishFiles.php');
myRequireOnce ('writeLog.php');


function publishLibraryIndex($p){

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
    $body = '<div class="page_content">'. "\n";
    $body .= $text->page . "\n";
    writeLogDebug('publishLibraryIndex-29', $body);
    $body = str_replace('/preview/library/AU/eng/friends.html', 'friends.html', $body);
    $body = str_replace('/preview/library/AU/eng/meet.html', 'meet.html', $body);
    $body = str_replace('/preview/series/AU/eng/family/youth-basics', 'youth-basics/index.html', $body); 
    $body = str_replace('/preview/series/AU/eng/current/current', 'current/index.html', $body);

    // see if anyone is bypassing the library (there is only one book in this language)
    if (strpos($body, '/preview/series/')){
        $body = bypassLibrary($body);
    }
    $body = $body . $footer;
    writeLogDebug('publishLibraryIndex-34', $body);
    $language_dir = publishDestination($p) .'content/'.  $p['country_code'] . '/'. $p['language_iso'];
    $fname = $language_dir . '/index.html';
    // write  file
    $body .= '<!--- Created by publishLibrary-->' . "\n";
    publishFiles( $p['destination'], $p, $fname, $body,   STANDARD_CSS,  $selected_css);
    // Australia is the current owner of this site, so their file goes to root
    if ($fname  ==  publishDestination($p) .'content/AU/eng/index.html'){
        $fname = publishDestination($p). 'index.html'; // add as root directory index
        publishFiles( $p['destination'], $p, $fname, $body,   STANDARD_CSS,  $selected_css);
    }
    // update records
    //
    
    $time = time();
    $sql = null;
      writeLogDebug('publishLibraryIndex-51', $p);
    if ($p['destination'] == 'staging'){
       
        $sql = "UPDATE content
            SET prototype_date = '$time', prototype_uid = '". $p['my_uid'] ."'
            WHERE  country_code = '" . $p['country_code'] ."'
            AND folder_name = '' AND filename = 'index'
            AND prototype_date IS NULL";
        
    }
    if ($p['destination'] == 'website'){
        $sql = "UPDATE content
            SET publish_date = '$time', publish_uid = '". $p['my_uid'] ."'
            WHERE  country_code = '" . $p['country_code'] ."'
            AND folder_name = '' AND filename = 'index'
            AND prototype_date IS NOT NULL
            AND publish_date IS NULL";
    }
    writeLogDebug('publishLibraryIndex-66', $sql);
    if ($sql){
      sqlArray($sql, 'update');
    }

    return true;
}


/* experimental area to see if we can bypass library
  'preview/series/AU/eng/family/youth-basics'
 needs to be changed to
 'content/AU/eng/youth-basics/index.html'
*/
function bypassLibrary ($body){
    $count = substr_count($body, '/preview/series/');
    for ($i = 1; $i <= $count; $i++){
        $start = strpos($body, '/preview/series/');
        $end = strpos($body, '"', $start);
        $len = $end - $start;
        $link1 = substr($body, $start,$len ); //preview/series/AU/eng/family/youth-basics
        $parts = explode('/',  $link1);
        $link2 =   '/content/' . $parts[3] .'/'. $parts[4] .'/' .$parts[6] ;
        $body = str_replace($link1, $link2, $body);
    }
    return $body;
}