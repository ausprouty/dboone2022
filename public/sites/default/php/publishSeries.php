<?php

myRequireOnce ('publishFiles.php');
myRequireOnce ('createSeries.php');

// returns $p[files_json] for use by prototypeSeriesandChapters
function publishSeries ($p){

    $debug = "In publishSeries\n";
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
    //$debug .= $data['text'] . "\n";
    $text = json_decode($data['text']);
    if ($text){
        // create Series
        $result = createSeries($p, $data);
        $p = $result['p'];
        if ($result['text']){
              // find css
              $b['recnum'] = $p['recnum'];
              $b['library_code'] = $p['library_code'];
              $bookmark  = bookmark($b);

              $selected_css = isset($bookmark['book']->style) ? $bookmark['book']->style :STANDARD_CSS ;
              //
            $dir = ROOT_PUBLISH_CONTENT.  $p['country_code'] .'/'. $p['language_iso'] .'/'. $p['folder_name'] .'/';
            // make sure folder exists
            if (!file_exists($dir)){
                dirMake ($dir);
            }
            $fname = $dir . 'index.html';
            $result['text'] .= '<!--- Created by publishSeries-->' . "\n";
            publishFiles( $p['destination'], $p, $fname, $result['text'],  STANDARD_CSS, $selected_css);
            $time = time();
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
    else{
        $debug .= 'No text found for the above query '.  "\n";

    }
    return $p;
}
