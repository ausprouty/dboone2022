<?php

// this will transfer only latest data to content_current

myRequireOnce ('getLatestContent.php');

function flushContent(){
    flushContentCountries();
    $sql = 'SELECT distinct country_code FROM content';
    $query = sqlMany($sql);
    while($data = $query->fetch_array()){
        flushContentCountry($data['country_code']);
    }
    return TRUE;
}

function flushContentCountries(){
    $p = [];
    $p['scope'] = 'countries';
    $countries = getLatestContent($p);
    flushContentInsert($countries);
}
function flushContentInsert($data){

    $sql = "INSERT INTO content_current (version, edit_date, edit_uid, language_iso, country_code, folder_name, filetype, title, filename, text)
     VALUES
          (1, '$edit_date', '$edit_uid', '$destination_language_iso', '$destination_country_code', '$folder_name', '$filetype', '$title', '$filename', '$text')";
    $done = sqlInsert($sql);

}