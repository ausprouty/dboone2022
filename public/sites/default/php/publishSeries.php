<?php

myRequireOnce ('createSeries.php');
myRequireOnce ('dirCreate.php');
myRequireOnce ('publishFiles.php');


// returns $p[files_json] for use by prototypeSeriesandChapters
function publishSeries ($p){
    // when coming in with only book information the folder_name is not yet set
    $debug = "In publishSeries\n";
    if (!isset($p['folder_name'])){
        if (isset($p['code'])){
            $p['folder_name'] = $p['code'];
        }
    }
    //
    //find series data
    //
    $sql = "SELECT * FROM content
        WHERE  country_code = '". $p['country_code'] ."'
        AND  language_iso = '". $p['language_iso'] ."'
        AND folder_name = '" .$p['folder_name'] ."'  AND filename = 'index'
        AND prototype_date IS NOT NULL
        ORDER BY recnum DESC LIMIT 1";
   // $debug .= $sql. "\n";
    $data = sqlArray($sql);
    if (!$data){
       $message = 'No data found for: ' . $sql;
       writeLogError('publishSeries-29', $message);
       trigger_error( $message, E_USER_ERROR);
    }
    //$debug .= $data['text'] . "\n";
    $text = json_decode($data['text']);
    writeLogDebug('publishSeries-30-text', $text);
     writeLogDebug('publishSeries-31-p', $p);
    if ($text){
        // create Series
        $result = createSeries($p, $data);
        $p = $result['p'];
        if ($result['text']){
            // find css
            if (isset($p['recnum'])){
                $b['recnum'] = $p['recnum'];
                $b['library_code'] = $p['library_code'];
            }
            else{
                $b = $p;
            }
            $bookmark  = bookmark($b);
            $selected_css = isset($bookmark['book']->style) ? $bookmark['book']->style :STANDARD_CSS ;
            $dir = dirCreate('series', $p['destination'],  $p, $folders = null);
            $fname = $dir . 'index.html';
            $result['text'] .= '<!--- Created by publishSeries-->' . "\n";
            publishFiles( $p['destination'], $p, $fname, $result['text'],  STANDARD_CSS, $selected_css);
            $time = time();
            if ($p['destination'] == 'staging'){
                $sql = "UPDATE content
                    SET prototype_date = '$time', prototype_uid = '". $p['my_uid']. "'
                    WHERE  country_code = '". $p['country_code'] ."' AND
                    language_iso = '" . $p['language_iso'] ."'
                    AND folder_name = '" . $p['folder_name'] ."'  AND filename = 'index'
                    AND prototype_date IS NULL";
                //$debug .= $sql. "\n";
                sqlArray($sql, 'update');
           }
            if ($p['destination'] == 'website'){
                $sql = "UPDATE content
                    SET publish_date = '$time', publish_uid = '". $p['my_uid']. "'
                    WHERE  country_code = '". $p['country_code'] ."' AND
                    language_iso = '" . $p['language_iso'] ."'
                    AND folder_name = '" . $p['folder_name'] ."'  AND filename = 'index'
                    AND prototype_date IS NOT NULL
                    AND publish_date IS NULL";
                //$debug .= $sql. "\n";
                sqlArray($sql, 'update');
           }
        }
    }
    else{
        $debug .= 'No text found for the above query '.  "\n";

    }
    return $p;
}
