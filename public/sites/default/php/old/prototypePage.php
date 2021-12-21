<?php

myRequireOnce ('publishFiles.php');
myRequireOnce('modifyPage.php');
myRequireOnce('writeLog.php');

function prototypePage ($p){
    $p['status'] = 'prototype';
    if (!isset($p['debug'])){
        $p['debug'] = null;
    }
    $p['debug'] = 'In prototypePage February' . "\n";
    if (!isset($p['recnum'])){
        $p['debug'] .= 'FATAL ERROR IN PROTOTYPE PAGE.  No recnum' . "\n";
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
    //show data values for page
    foreach ($data as $key=>$value){
        $p['debug'] .= $key . ' => '. $value . "\n";
    }
    writeLog('prototypePage-32', $p['debug']);
     //
    // create page
    //
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

    /*$p['debug'] .= 'Begin bookmark'. "\n";// what is bookmark?
    foreach ($bookmark as $key => $value){
        $p['debug'] .= $key . "\n";
        foreach ($value as $key2 => $value2){
            $p['debug'] .= '    '. $key2 . "\n";
         //   $p['debug'] .= '        '. $value2 . "\n";
        }
    }
    $p['debug'] .= 'end of bookmark'. "\n";
*/
    //
    // modify the page for notes and links
    //
     writeLog('prototypePage-75', $text);
    $text = modifyPage($text, $p, $data, $bookmark);

    // write file
    $series_dir = ROOT_PROTOTYPE_CONTENT.  $data['country_code'] .'/'.
        $data['language_iso'] .'/'. $data['folder_name'] .'/';
    $fname = $series_dir . $data['filename'] .'.html';
    $text .= '<!--- Created by Stanard prototypePage-->' . "\n";
    // go to publishFiles
    //writeLog('prototypePage-84', $p['debug']);
    writeLog('prototypePage-86', $text);
    $response = publishFiles( $p['destination'], $p, $fname, $text,  STANDARD_CSS, $selected_css);
    $p['debug'] .= $response['debug'];
    //
    // update records
    //
    //writeLog('prototypePage-90', $p['debug']);
    $time = time();
    $sql = "UPDATE content
        SET prototype_date = '$time', prototype_uid = '". $p['my_uid']. "'
        WHERE  country_code = '". $data['country_code'] ."' AND
        language_iso = '" . $data['language_iso'] ."'
        AND folder_name = '" . $data['folder_name'] ."'
        AND filename = '". $data['filename'] . "'
        AND prototype_date IS NULL";
    //$p['debug'] .= $sql. "\n";
    sqlArray($sql, 'update');
    return $p;
}
