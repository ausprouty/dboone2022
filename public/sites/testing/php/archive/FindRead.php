<?php
require_once ('../.env.api.remote.mc2.php');
myRequireOnceSetup(11);
myRequireOnce ('sql.php');
myRequireOnce ('.env.cors.php');
$folders = array('multiply1','multiply2','multiply3');
foreach ($folders as $folder){
    $fixing = 'multiply1';
    $find = '<p class="up">';
    $debug = "In Find Video<br>\n";
    $sql = 'SELECT DISTINCT filename FROM content 
        WHERE language_iso = "cmn"
        AND country_code = "M2"
        AND folder_name = "'. $folder . '"
        AND filename != "index"
        ORDER BY filename';
    $query  = sqlMany($sql);
    while($data = $query->fetch_array()){
        echo  $data['filename'] . "<br>\n";
        $found = [];
        $sql2 = 'SELECT * FROM content 
            WHERE language_iso = "eng"
            AND country_code = "M2"
            AND folder_name = "'. $folder . '"
            AND filename = "'. $data['filename'] .'"
            ORDER BY recnum';
        $query2  = sqlMany($sql2);
        while ($res = $query2->fetch_array()){
            if (strpos($res['text'], $find) != FALSE){
                $count = substr_count($res['text'], $find);
                $pos_start = 0;
                for ($i = 1; $i <= $count; $i++){
                    $pos_start = strpos ($res['text'], $find, $pos_start);
                    $pos_end = strpos($res['text'], '</p>', $pos_start + 7);
                    $length = $pos_end - $pos_start;
                    $link = substr($res['text'], $pos_start, $length);
                    if (!in_array($link, $found)){
                        $found[] = $link;
                        echo  $link . "<br>";
                    }
                
                    $pos_start = $pos_end;
                }
            }
        }
    }
}
 return $output;
