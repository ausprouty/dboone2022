<?php

myRequireOnce ('publishFiles.php');
myRequireOnce('modifyPage.php');

function publishPage ($p){
    $p['status'] = 'publish';
    if (!isset($p['debug'])){
        $p['debug'] = null;
    }
    $p['debug'] .= 'In publish Page' . "\n";
    if (!isset($p['recnum'])){
        $p['debug'] .= 'FATAL ERROR IN PUBLISH PAGE.  No recnum' . "\n";
        return $p;
    }
     // can you find record in database?
     if (isset($p['recnum'])) {
        $sql = 'SELECT * FROM content
            WHERE  recnum  = '. $p['recnum'];
        $p['debug'] .= $sql. "\n";
        $data = sqlArray($sql);
    }
    else{
        $p['debug'] .= 'FATAL ERROR. No recnum in PrototypPage'. "\n";
        return $p;
    }
    //
    // create page
    //
    foreach ($data as $key=>$value){
        $p['debug'] .= $key . ' => '. $value . "\n";
    }
    $result = createPage($p, $data);
    $p = $result['p'];
    $text = $result['text'];
    $p['debug'] .= isset($result['debug'])? $result['debug'] . "\n" : null;
    //
    // find files in page for series json file
    //
    $result  = prototypeFindFilesInPage($text, 'prototype');
    if (isset($result['files_in_page'])){
        foreach ($result['files_in_page'] as $file_found){
            $p['debug'] .=  $file_found . "\n";
        }
    }
    $p['files_in_page'] = isset($result['files_in_page']) ? $result['files_in_page'] : [];
    $p['files_in_page'] = array_merge($p['files_in_page'], $result['files_in_page']);
    if (isset($result['message'])){
        $p['debug'] .= $result['message'];
    }

     // get bookmark for stylesheet
    $b['recnum'] =  $p['recnum'];
    $b['library_code'] = $p['library_code'];
    $bm = bookmark($b);
    $bookmark = $bm['content'];
    $selected_css = isset($bookmark['book']->style)? $bookmark['book']->style: STANDARD_CSS;
    //
    // class="nobreak" need to be changed to class="nobreak-final" so color is correct
    $text = str_ireplace("nobreak", "nobreak-final", $text);
     //
    // modify the page for notes and links
    //
    $response = modifyPage($text, $p, $data, $bookmark);
    $text = $response['content'];
    $p['debug'] .= $response['debug'];
    // write file
    $series_dir = ROOT_PUBLISH_CONTENT.  $data['country_code'] .'/'.
        $data['language_iso'] .'/'. $data['folder_name'] .'/';
    $fname = $series_dir . $data['filename'] .'.html';
    $text .= '<!--- Created by publishPage-->' . "\n";
    // go to publishFiles
    publishFiles( 'publish', $p, $fname, $text,  STANDARD_CSS, $selected_css);
    $p['debug'] .= $response['debug'];
    //
    // update records
    //
    $time = time();
    $sql = "UPDATE content
        SET publish_date = '$time', publish_uid = '". $p['my_uid']. "'
        WHERE  country_code = '". $data['country_code'] ."' AND
        language_iso = '" . $data['language_iso'] ."'
        AND folder_name = '" . $data['folder_name'] ."'
        AND filename = '". $data['filename'] . "'
        AND publish_date IS NULL";
    //$p['debug'] .= $sql. "\n";
    sqlArray($sql, 'update');
    return $p;
}
