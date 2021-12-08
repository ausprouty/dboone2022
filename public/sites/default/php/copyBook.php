<?php

function copyBook($p){
    $out = [];
    $out['debug']= '';
    if (!$p['source'] || !$p['destination']){
        return;
    }
    $edit_date = time();
    $edit_uid = $p['my_uid'];
    $destination = explode('/', $p['destination']);
    $destination_country_code = $destination[0];
    $destination_language_iso = $destination[1];
    $source = explode('/',$p['source']);
    $country_code = $source[0];
    $language_iso = $source[1];
    $folder_name = $source[2];
    $sql = "SELECT DISTINCT  filename 
        FROM  content 
        WHERE country_code = '$country_code' 
        AND language_iso = '$language_iso' 
        AND folder_name = '$folder_name'";
    $query = sqlMany($sql);
    while($data = $query->fetch_array()){
        $filename = $data['filename'];
        $out['debug'] .= 'filename is ' . $filename ."\n";
        $sql2 = "SELECT * 
            FROM  content 
            WHERE country_code = '$country_code' 
            AND language_iso = '$language_iso' 
            AND folder_name = '$folder_name'
            AND filename = '$filename'
            ORDER BY recnum DESC
            LIMIT 1";
       
        $d = sqlArray($sql2);
        $filetype = $d['filetype'];
        $out['debug'] .=  $d['filename']  . '-- '. $d['recnum'] ."\n";
        $title = $d['title'];
        $text = $d['text'];
        $sql3 = "INSERT INTO content (version,edit_date, edit_uid, language_iso, country_code, folder_name, filetype, title, filename, text) VALUES
          (1, '$edit_date', '$edit_uid', '$destination_language_iso', '$destination_country_code', '$folder_name', '$filetype', '$title', '$filename', '$text')";
        $done = sqlInsert($sql3);
    }
    return $out;


}
