<?php
myRequireOnce ('createPage.php');
myRequireOnce ('modifyPage.php');
myRequireOnce ('publishDestination.php');
myRequireOnce ('publishFiles.php');
myRequireOnce ('publishFindFilesInPage.php');
myRequireOnce ('//writeLog.php');



function publishPage ($p){

    $debug = 'In publishPage' . "\n";
    //writeLog ('publishPage-14-p', $p);
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
    //writeLog ('publishPage-30-debug', $debug);
    $text  = createPage($p, $data);
    //writeLog ('publishPage-32', $text);
    //writeLog ('publishPage-43-debug', $debug);
     // get bookmark for stylesheet
    $b['recnum'] =  $p['recnum'];
    $b['library_code'] = $p['library_code'];
    $bookmark = bookmark($b);
    //writeLog ('publishPage-52-bookmark', $bookmark);

    $selected_css = isset($bookmark['book']->style)? $bookmark['book']->style: STANDARD_CSS;
    //
    // class="nobreak" need to be changed to class="nobreak-final" so color is correct
    $text = str_ireplace("nobreak", "nobreak-final", $text);
     //
    // modify the page for notes and links
    //

     //writeLog ('publishPage-61-text', $text);
    $text = modifyPage($text, $p, $data, $bookmark);
    //writeLog ('publishPage-62', $text);
    // write file
    $series_dir = publishDestination($p) . 'content/'. $data['country_code'] .'/'.
        $data['language_iso'] .'/'. $data['folder_name'] .'/';
    $fname = $series_dir . $data['filename'] .'.html';
    $text .= '<!--- Created by publishPage-->' . "\n";
    //writeLog ('publishPage-69-text', $text);
    // go to publishFiles
    publishFiles( $p['destination'], $p, $fname, $text,  STANDARD_CSS, $selected_css);
    // make sure  all files are copied to destination directory
    publishFilesInPage($text, $p);
    //writeLog ('publishPage-72-debug', $debug);//
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
    if ($p['destination'] == 'staging'){
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
    //writeLog ('publishPage-98-debug', $debug);
    //writeLog ('publishPage-99-p', $p);
    return($p);
}
