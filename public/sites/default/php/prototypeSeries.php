<?php

myRequireOnce ('prototypeORpublish.php');
myRequireOnce ('createSeries.php');

// returns $p[files_json] for use by prototypeSeriesandChapters
function prototypeSeries ($p){
    $p['status'] = 'prototype';
    if (!isset($p['debug'])){
        $p['debug'] = null;
    }
    $series_dir = ROOT_PROTOTYPE_CONTENT.  $p['country_code'] .'/'. $p['language_iso'] .'/'. $p['folder_name'] .'/';
    if (!file_exists($series_dir)){
        dirMake ($series_dir); 
    }
    //
    //find series data
    //
    $sql = "SELECT * FROM content 
        WHERE  country_code = '". $p['country_code'] ."' 
        AND  language_iso = '". $p['language_iso'] ."' 
        AND folder_name = '" .$p['folder_name'] ."'  AND filename = 'index' 
        ORDER BY recnum DESC LIMIT 1";
   // $p['debug'] .= $sql. "\n";
    $data = sqlArray($sql);
    //$p['debug'] .= $data['text'] . "\n"; 
    $text = json_decode($data['text']);
    if ($text){
        // create Series
        $result = createSeries($p, $data);
        $p = $result['p'];
        if ($result['text']){
            // find css
            $b['recnum'] = $p['recnum'];
            $b['library_code'] = $p['library_code'];
            $bm = bookmark($b);
            $bookmark = $bm['content'];
            $selected_css = isset($bookmark['book']->style) ? $bookmark['book']->style :STANDARD_CSS ;
            // publish files
            $fname = $series_dir . 'index.html';
            $result['text'] .= '<!--- Created by prototypeSeries-->' . "\n";
            publishFiles( 'prototype', $p, $fname, $result['text'],  STANDARD_CSS, $selected_css);
            $time = time();
            $sql = "UPDATE content 
                SET prototype_date = '$time', prototype_uid = '". $p['my_uid']. "' 
                WHERE  country_code = '". $p['country_code'] ."' AND  
                language_iso = '" . $p['language_iso'] ."' 
                AND folder_name = '" . $p['folder_name'] ."'  AND filename = 'index' 
                AND prototype_date IS NULL";
            //$p['debug'] .= $sql. "\n";
            sqlArray($sql, 'update');
        }
    }
    else{
        $p['debug'] .= 'No text found for the above query '.  "\n";
    
    }
    return $p;
}
