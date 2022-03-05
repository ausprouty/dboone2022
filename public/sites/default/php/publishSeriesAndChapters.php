<?php
myRequireOnce ('create.php');
myRequireOnce ('dirMake.php');
myRequireOnce ('fileWrite.php');
myRequireOnce ('publishFiles.php');
myRequireOnce ('publishSeries.php');
myRequireOnce ('publishPage.php');
myRequireOnce ('writeLog.php');


function publishSeriesAndChapters ($p){
    $debug = '';
    // first prototype the Series Index
    $out = publishSeries ($p);
    $files_json = $out['files_json']; // this starts file for download of series
    $files_in_pages = [];
    // find the list of chapters that are ready to publish
    $series = contentArrayFromRecnum($p['recnum']);
    $series_dir = dirCreate('series', $p['destination'],  $p, $folders = null);// make sure folder exists
    $text = json_decode($series['text']);
    $chapters = $text->chapters;
    foreach ($chapters as $chapter){
        if ($chapter->publish){
            $sql = "SELECT recnum FROM  content
                WHERE  country_code = '" . $series['country_code'] ."'
                AND language_iso = '" . $series['language_iso'] ."'
                AND folder_name = '" . $series['folder_name'] ."'
                AND filename = '" . $chapter->filename ."'
                AND prototype_date IS NOT NULL
                ORDER BY recnum DESC LIMIT 1";
            $data = sqlArray($sql);
            if ($data){
                $p['recnum'] = $data['recnum'];
                // need to find latest record for recnum
                $result =  publishPage ($p);
                writeLogError('publishSeriesAndChapters-36-result', $result);
                if (is_array($result)){
                    if (isset($result['files_in_page'])){
                        $files_in_pages = array_merge($files_in_pages,$result['files_in_page']);
                    }
                }
            }
            else{
                // find file and add to database
                $file =   $series_dir .  $chapter->filename . '.html';
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
                }
                else{
                    $debug .= 'NO RESULT for ' . $file. "\n";
                }
            }

        }
    }

    //
    // Create files.json with list of files to download of offline use.
    //list of html files is created in createSeries near line 125
    $clean_files_in_pages = [];
    foreach ($files_in_pages as $f){
         $clean_files_in_pages[$f] = $f;
    }
    foreach ($clean_files_in_pages as $json){
        $files_json.= '{"url":"'. $json .'"},' ."\n";
    }
    $files_json = substr($files_json, 0, -2) . "\n" . ']' . "\n" ;
    $filename =  $series_dir . 'files.json';

    fileWrite($filename, $files_json, $p);
    return true;
}