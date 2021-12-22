<?php
myRequireOnce ('createPage.php');
myRequireOnce ('modifyPage.php');
myRequireOnce ('publishDestination.php');
myRequireOnce ('publishFiles.php');
myRequireOnce ('publishFindFilesInPage.php');
myRequireOnce ('writeLog.php');



function publishPage ($p){

    $debug = 'In publishPage' . "\n";
    writeLog ('publishPage-14', $debug);
    if (!isset($p['recnum'])){
       $message = "in PublishPage no value for recnum ";
        trigger_error( $message, E_USER_ERROR);
        return ($p);
    }
    $sql = 'SELECT * FROM content
        WHERE  recnum  = '. $p['recnum'];
    $debug .= $sql. "\n";
    $data = sqlArray($sql);
    //
    // create page
    //
    foreach ($data as $key=>$value){
        $debug .= $key . ' => '. $value . "\n";
    }
    writeLog ('publishPage-30', $debug);
    $text  = createPage($p, $data);
    writeLog ('publishPage-32', $text);
    //
    // find files in page for series json file
    //
    $result  = publishFindFilesInPage($text, $p['destination']);
     writeLog ('publishPage-37', $result);
    if (isset($result['files_in_page'])){
        foreach ($result['files_in_page'] as $file_found){
            $debug .=  $file_found . "\n";
        }
    }
    writeLog ('publishPage-43', $debug);
    $p['files_in_page'] = isset($result['files_in_page']) ? $result['files_in_page'] : [];
    $p['files_in_page'] = array_merge($p['files_in_page'], $result['files_in_page']);


     // get bookmark for stylesheet
    $b['recnum'] =  $p['recnum'];
    $b['library_code'] = $p['library_code'];
    $bookmark = bookmark($b);
    writeLog ('publishPage-52', $bookmark);

    $selected_css = isset($bookmark['book']->style)? $bookmark['book']->style: STANDARD_CSS;
    //
    // class="nobreak" need to be changed to class="nobreak-final" so color is correct
    $text = str_ireplace("nobreak", "nobreak-final", $text);
     //
    // modify the page for notes and links
    //
     writeLog ('p61ublishPage-', $text);
    $text = modifyPage($text, $p, $data, $bookmark);
    writeLog ('publishPage-62', $text);
    // write file
    $series_dir = publishDestination($p) .  $data['country_code'] .'/'.
        $data['language_iso'] .'/'. $data['folder_name'] .'/';
    $fname = $series_dir . $data['filename'] .'.html';
    $text .= '<!--- Created by publishPage-->' . "\n";
    writeLog ('publishPage-69', $text);
    // go to publishFiles
    publishFiles( $p['destination'], $p, $fname, $text,  STANDARD_CSS, $selected_css);
    writeLog ('publishPage-72', $debug);//
    // update records
    //
    $time = time();
    $sql = null;
    if ($p['destination'] == 'publish'){
        $sql = "UPDATE content
        SET publish_date = '$time', publish_uid = '". $p['my_uid']. "'
        WHERE  country_code = '". $data['country_code'] ."' AND
        language_iso = '" . $data['language_iso'] ."'
        AND folder_name = '" . $data['folder_name'] ."'
        AND filename = '". $data['filename'] . "'
        AND publish_date IS NULL";
    }
    if ($p['destination'] == 'prototype'){
        $sql = "UPDATE content
        SET prototype_date = '$time', prototype_uid = '". $p['my_uid']. "'
        WHERE  country_code = '". $data['country_code'] ."' AND
        language_iso = '" . $data['language_iso'] ."'
        AND folder_name = '" . $data['folder_name'] ."'
        AND filename = '". $data['filename'] . "'
        AND protype_date IS NULL";
    }
    if ($sql){
        sqlArray($sql, 'update');
    }
    writeLog ('publishPage-93', $debug);
    return($p);
}
