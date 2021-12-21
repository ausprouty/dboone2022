<?php

myRequireOnce ('publishFiles.php');

function publishLibraryIndex($p){
    $p['status'] = 'publish';
    $p['debug'] = 'in prototypeLibraryIndex' . "\n";
    //$selected_css = '/content/AU/styles/AU-freeform.css';
     //
    //find country page from recnum
    //
    $sql = 'SELECT * FROM content  WHERE  recnum = "'.  $p['recnum'] . '"';
    //$p['debug'] .= $sql. "\n";
    $data = sqlArray($sql);
    if (!$data){return $p;}
    $text = json_decode($data['text']);
    $selected_css = $text->style;
     // get language footer in prototypeOEpublish.php
    $footer = prototypeLanguageFooter($p);
    // replace placeholders
    $body = '<div class="content">'. "\n";
    $body .= $text->page . "\n";
    $body = str_replace('/preview/library', '/content', $body);
    // see if anyone is bypassing the library (there is only one book in this language)
    if (strpos($body, '/preview/series/')){
        $p['debug'].= 'I bypassed library at least once' ."\n";
        $res = bypassLibrary($body);
        $body = $res['body'];
        $p['debug'] .= $res['debug'];
    }
    $body = $body .  $footer  ;
    // set file name
    $language_dir = ROOT_PUBLISH_CONTENT . $p['country_code'] . '/'. $p['language_iso'];
    $fname = $language_dir . '/index.html';
    // write  file
    $body .= '<!--- Created by publishLibrary-->' . "\n";
    publishFiles( $p['destination'], $p, $fname, $body,   STANDARD_CSS,  $selected_css);
    //TODO: make this a variable
    // Australia is the current owner of this site, so their file goes to root
    if ($fname  ==  ROOT_PUBLISH_CONTENT .'AU/eng/index.html'){
        $fname = ROOT_PUBLISH. 'index.html';
        $p['debug'] .= 'I am sending Australian index to  ' . $fname;
        publishFiles( $p['destination'], $p, $fname, $body,   STANDARD_CSS,  $selected_css);
        $fname = ROOT_PUBLISH_CONTENT. 'index.html';
        $p['debug'] .= 'I am sending Australian index to  ' . $fname;
        publishFiles( $p['destination'], $p, $fname, $body,   STANDARD_CSS,  $selected_css);
    }
    // update records
    //
    $time = time();
    $sql = "UPDATE content 
        SET publish_date = '$time', publish_uid = '". $p['my_uid'] ."'
        WHERE  country_code = '" . $p['country_code'] ."' 
        AND folder_name = '' AND filename = 'index'
        AND prototype_date IS NOT NULL
        AND publish_date IS NULL";
    //$p['debug'] .= $sql. "\n";
    sqlArray($sql, 'update');
    return $p;
}


/* experimental area to see if we can bypass library
  'preview/series/AU/eng/family/youth-basics'
 needs to be changed to 
 'content/AU/eng/youth-basics/index.html'
*/
function bypassLibrary ($body){
    $res['debug'] = 'In _bypassLibrary' . "\n";
    $count = substr_count($body, '/preview/series/');
    $res['debug'] =   'Count is ' . $count  . "\n";
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
    $res['body'] = $body;
    return $res;
}