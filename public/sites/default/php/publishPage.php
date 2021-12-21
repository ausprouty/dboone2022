<?php
myRequireOnce ('createPage.php');
myRequireOnce ('modifyPage.php');
myRequireOnce ('publishDestination.php');
myRequireOnce ('publishFiles.php');
myRequireOnce ('publishFindFilesInPage.php');



function publishPage ($p){

    $debug = 'In publishPage' . "\n";
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
    $text  = createPage($p, $data);
    //
    // find files in page for series json file
    //
    $result  = publishFindFilesInPage($text, $p['destination']);
    if (isset($result['files_in_page'])){
        foreach ($result['files_in_page'] as $file_found){
            $debug .=  $file_found . "\n";
        }
    }
    $p['files_in_page'] = isset($result['files_in_page']) ? $result['files_in_page'] : [];
    $p['files_in_page'] = array_merge($p['files_in_page'], $result['files_in_page']);


     // get bookmark for stylesheet
    $b['recnum'] =  $p['recnum'];
    $b['library_code'] = $p['library_code'];
    $bookmark = bookmark($b);

    $selected_css = isset($bookmark['book']->style)? $bookmark['book']->style: STANDARD_CSS;
    //
    // class="nobreak" need to be changed to class="nobreak-final" so color is correct
    $text = str_ireplace("nobreak", "nobreak-final", $text);
     //
    // modify the page for notes and links
    //
    $text = modifyPage($text, $p, $data, $bookmark);

    // write file
    $series_dir = publishDestination($p) .  $data['country_code'] .'/'.
        $data['language_iso'] .'/'. $data['folder_name'] .'/';
    $fname = $series_dir . $data['filename'] .'.html';
    $text .= '<!--- Created by publishPage-->' . "\n";
    // go to publishFiles
    publishFiles( $p['destination'], $p, $fname, $text,  STANDARD_CSS, $selected_css);
    $debug .= $response['debug'];
    //
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
        return $p;
    }
    //$debug .= $sql. "\n";

}
