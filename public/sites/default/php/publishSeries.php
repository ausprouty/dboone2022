<?php

myRequireOnce ('createSeries.php');
myRequireOnce ('dirCreate.php');
myRequireOnce ('publishFiles.php');


// returns $p[files_json] for use by prototypeSeriesandChapters
function publishSeries ($p){
    // when coming in with only book information the folder_name is not yet set

    if (!isset($p['folder_name'])){
        if (isset($p['code'])){
            $p['folder_name'] = $p['code'];
        }
    }
    //
    //find series data
    //
    if ($p['destination'] == 'staging'){
        $sql = "SELECT * FROM content
            WHERE  country_code = '". $p['country_code'] ."'
            AND  language_iso = '". $p['language_iso'] ."'
            AND folder_name = '" .$p['folder_name'] ."'  AND filename = 'index'
            ORDER BY recnum DESC LIMIT 1";
    }
    else{
         $sql = "SELECT * FROM content
            WHERE  country_code = '". $p['country_code'] ."'
            AND  language_iso = '". $p['language_iso'] ."'
            AND folder_name = '" .$p['folder_name'] ."'  AND filename = 'index'
            AND prototype_date IS NOT NULL
            ORDER BY recnum DESC LIMIT 1";
    }
   // $debug .= $sql. "\n";
    $data = sqlArray($sql);
    if (!$data){
       $message = 'No data found for: ' . $sql;
       writeLogError('publishSeries-29', $message);
       return $p;
    }
    //$debug .= $data['text'] . "\n";
    $text = json_decode($data['text']);

    if ($text){
        // create Series
        $result = createSeries($p, $data);

        $p = $result['p']; // this gives us $p['files_json']
        if ($result['text']){
            writeLogAppend('publishSeries-48', $result['text']);
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
            writeLogAppend('publishSeries-65', $result['text']);
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
        $message ='No text found for '.  $query ."\n";
        writeLogAppend('ERROR- publishSeries-93', $message);

    }
    return $p;
}
