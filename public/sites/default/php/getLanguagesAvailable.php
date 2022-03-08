<?php
myRequireOnce ('writeLog.php');

function getLanguagesAvailable($p){
    $available = [];
    // flags
    $sql = "SELECT * FROM content
                WHERE filename = 'countries'
                ORDER BY recnum DESC LIMIT 1";
    $data = sqlArray($sql);
    $countries_array = json_decode($data['text']);
    //find prototype countries data
    //
    $sql = "SELECT distinct country_code FROM content
        WHERE  prototype_date != '' AND publish_date != ''
        AND country_code != '' ";

    if (isset($p['destination'])){
        if ($p['destination']=='staging'){
                $sql = "SELECT distinct country_code FROM content
                WHERE  prototype_date != ''
                AND country_code != '' ";
            }
    }
    $query = sqlMany($sql);
    while($country = $query->fetch_array()){
        // get prototyped languages from each prototyped country
         if ($p['destination'] =='staging'){
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
            $message = '$text->languages not found for ' . $country['country_code'];
            writeLogError('getLanguagesAvailable-33' , $message );
        }
        else{
            // look for flag
            $flag = 'unknown';
            foreach ($countries_array as $country_object){
                if ($country_object->code == $country['country_code']){
                    $flag = DIR_DEFAULT_SITE . 'images/country/'. $country_object->image;
                }
            }
            //writeLog('getLanguagesAvailable-44' , $text->languages );
            foreach ($text->languages as $language){
                if (isset($language->publish)){
                    if ($language->publish){
                        $library = 'previewLibrary';
                        if (isset($language->custom)){
                            if ($language->custom =='true'){
                                $library = 'previewLibraryIndex';
                            }
                        }
                        $available [] = array(
                            'language_iso'=> $language->iso,
                            'language_name'=> $language->name,
                            'country_code' => $country['country_code'],
                            'folder'=> $language->folder,
                            'library'=> $library,
                            'flag'=> $flag
                        );
                    }
                }
            }
            //writeLog('getLanguagesAvailable-65' , $available );
            usort($available, '_sortByIso');
            //writeLog('getLanguagesAvailable-67' ,$available );

        }

    }
    $out= $available;
    //writeLog('getLanguagesAvailable-71' , $out );
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

function _flag($country_code){
    $flag = '';
    $sql = "SELECT * FROM content
            AND filename = 'countries'  AND prototype_date != ''
            ORDER BY recnum DESC LIMIT 1";
    $countries = sqlArray($sql);
    foreach ($countries as $country){
        if ($country['code'] == $country_code){
            $flag = '../images/country/'. $country['image'];
        }
    }
    return $flag;
}
