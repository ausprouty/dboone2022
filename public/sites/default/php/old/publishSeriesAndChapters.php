<?php

myRequireOnce ('publishFiles.php');
myRequireOnce ('publishSeries.php');
myRequireOnce ('publishPage.php');
myRequireOnce ('create.php');

function publishSeriesAndChapters ($p){
    $p['status'] = 'publish';
    // first prototype the Series Index
    $out = publishSeries ($p);
    $files_json = $out['files_json']; // this starts file for download of series
    $files_in_pages = [];
    //$out['debug'] .= 'In prototypeSeriesAndChapters '. "\n";
   
    // find the list of chapters that are ready to publish
    $series = objectFromRecnum($p['recnum']);
    $series_dir = ROOT_PUBLISH_CONTENT .  $series->country_code . '/'. $series->language_iso . '/'. $series->folder_name . '/';
    // make sure folder exists
    if (!file_exists( $series_dir)){
        dirMake ($series_dir); 
    }
    $text = json_decode($series->text);
    $chapters = $text->chapters;
    foreach ($chapters as $chapter){
        if ($chapter->publish){
            $sql = "SELECT recnum FROM  content 
                WHERE  country_code = '" . $series->country_code ."' 
                AND language_iso = '" . $series->language_iso ."' 
                AND folder_name = '" . $series->folder_name ."' 
                AND filename = '" . $chapter->filename ."' 
                AND prototype_date IS NOT NULL
                ORDER BY recnum DESC LIMIT 1";
            //$p['debug'] .= $sql. "\n";
            $data = sqlArray($sql);
            if ($data){
                $p['recnum'] = $data['recnum'];
                // need to find latest record for recnum
                $result =  publishPage ($p);
                $out['debug'] .= $result['debug'];
                if (is_array($result)){
                    $files_in_pages = array_merge($files_in_pages,$result['files_in_page']);
                }
            }
            else{
                // find file and add to database
                $file =  ROOT_EDIT. $series_dir .  $chapter->filename . '.html';
               // $out['debug'] .= 'looking for ' . $file . "\n";
                if (file_exists($file)){
                    $p['text'] = file_get_contents($file);
                    $p['filename'] = $chapter->filename;
                    createContent($p);
                    $data = sqlArray($sql);
                    $p['recnum'] = $data['recnum'];
                    $result =  publishPage ($p);
                    if (is_array($result['files_in_page'])){
                        $files_in_pages = array_merge($files_in_pages,$result['files_in_page']);
                    }
                    //$out['debug'] .= $result['debug'];
                }
                else{
                    $out['debug'] .= 'NO RESULT for ' . $file. "\n";
                }
            }
           
        }
    }

    //
    // Create files.json with list of files to download of offline use.
    //
    foreach ($files_in_pages as $json){
        $files_json.= '{"url":"'. $json .'"},' ."\n";
    }
    $files_json = substr($files_json, 0, -2) . "\n" . ']' . "\n" ; 
    $fh = fopen( $series_dir . 'files.json', 'w');
    fwrite($fh, $files_json);
    fclose($fh);     
    $out['data'] = 'Finished';
    return $out;
}