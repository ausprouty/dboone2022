<?php
myRequireOnce ('writeLog.php');

function findLibraries($p){
    // find library names
   $library_codes = [];
   $sql = "SELECT DISTINCT filename  FROM content
    WHERE  country_code = '" . $p['country_code'] . "'
    AND language_iso = '" . $p['language_iso'] ."' AND folder_name = ''";
    $query = sqlMany($sql, 'query');
    if (!$query){
        $message = "No library found\n$p";
        writeLogError('findLibraries', $message);
        return $library_codes;
    }
    while($data = $query->fetch_array()){
        $library_codes[] = $data['filename'];
    }

    return $library_codes;
}