<?php

function getCurrentBooks($p){
    $output = '[';
    $sql = "SELECT DISTINCT country_code, language_iso, folder_name
        FROM  content
        WHERE  filename = 'index' AND folder_name != ''
        ORDER BY country_code, language_iso, folder_name";
     $query = sqlMany($sql);
     while($data = $query->fetch_array()){
         $output .= '"/sites/' . $p['site'] . '/'.  $data['country_code'] . '/'. $data['language_iso'] .'/'. $data['folder_name'] . '",';
     }
     $p['content']= substr ($output, 0, -1) .']';
    return $p;
}