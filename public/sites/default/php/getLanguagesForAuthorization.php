<?php

function getLanguagesForAuthorization($p){
    $available = [];

    if(!isset($p['destination'])){
        $p['destination']= 'staging';
    }
    // flags
    $sql = "SELECT * FROM content
                WHERE filename = 'countries'
                ORDER BY recnum DESC LIMIT 1";
    $debug .= "$sql \n";
    $data = sqlArray($sql);
    $countries_array = json_decode($data['text']);
    //find prototype countries data
    //
    if ($p['destination']=='staging'){
        $sql = "SELECT distinct country_code FROM content
            WHERE  prototype_date != ''
            AND country_code != '' ";
    }
    else{
         $sql = "SELECT distinct country_code FROM content
            WHERE  prototype_date != '' AND publish_date != ''
            AND country_code != '' ";
    }
    $query = sqlMany($sql);
    while($country = $query->fetch_array()){
        // get prototyped languages from each prototyped country
        if ($p['destination']=='staging'){
            $sql = "SELECT * FROM content
                WHERE  country_code = '". $country['country_code'] ."'
                AND filename = 'languages'  AND prototype_date != ''
                ORDER BY recnum DESC LIMIT 1";
        }
        else{
            $sql = "SELECT * FROM content
                WHERE  country_code = '". $country['country_code'] ."'
                AND filename = 'languages'  AND prototype_date != ''
                AND publish_date != ''
                ORDER BY recnum DESC LIMIT 1";
        }
        $data = sqlArray($sql);
        $text = json_decode($data['text']);
        if (!isset($text->languages)){
            $message = 'in getLanguagesForAuthorization $text->languages not found for ' . $country['country_code'];
            writeLogError('getLanguagesForAuthorization-46' , $message );
        }
        else{
            foreach ($text->languages as $language){
                $available [$language->iso] = array(
                    'language_iso'=> $language->iso,
                    'language_name'=> $language->name,
                );
            }
        }
    }
    usort($available, '_sortByName');
    $out= $available;
    return $out;



}
function _sortByIso($a, $b){
    if ($a['language_iso'] = $b['language_iso']){
        return 0;
    }
    if ($a['language_iso'] > $b['language_iso']){
        return 1;
    }
    return -1;
}
function _sortByName($a, $b){
    if ($a['language_name'] = $b['language_name']){
        return 0;
    }
    if ($a['language_name'] > $b['language_name']){
        return 1;
    }
    return -1;
}
