<?php
/*
  returns directories that can be used for content
  $p[country_code] and $p[language_iso] must be set

*/
function getContentFoldersForLanguage($p){
    $out = array();
    $out= array();
    $debug = 'getContentFoldersForLanguage' . "\n";
    if (!isset($p['language_iso'])){
        trigger_error("No language for  getContentFoldersForLanguage", E_USER_ERROR);
        $debug = "Language_iso not set \n";
        return $out;
    }
    if (!isset($p['country_code'])){
         trigger_error("No Country Code for  getContentFoldersForLanguage", E_USER_ERROR);
        $debug = "Country Code not set \n";
        return $out;
    }
    $sql = "SELECT distinct country_code FROM content
            WHERE language_iso ='" .  $p['language_iso'] . "'";
    $debug .= $sql . "\n";
    $result = sqlMany($sql);
    if ($result){
        while($data = $result->fetch_array()){
            $out[] =  '/sites/' . $p['site'] . '/content/'. $data['country_code'] .'/'. $p['language_iso'];
        }
    }
    $default =  '/sites/' . $p['site'] . '/content/'.  $p['country_code'] .'/'. $p['language_iso'];
    if (!in_array($default, $out)){
        $out[] = $default;
    }
    return $out;
}