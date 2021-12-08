<?php
function getCurrentLanguages($p){
    $output = '[';
    $sql = "SELECT DISTINCT country_code, language_iso  
        FROM  content 
        WHERE language_iso != ''
        ORDER BY country_code, language_iso";
    $query = sqlMany($sql);
    while($data = $query->fetch_array()){
        $output .= '"' . $data['country_code'] . '/'. $data['language_iso'] . '",';
    }
    $p['content']= substr ($output, 0, -1) .']';
    return $p;
}