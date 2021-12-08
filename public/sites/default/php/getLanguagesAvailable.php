<?php


function getLanguagesAvailable($p){
    $available = [];
    $out = [];
    $out['debug'] = "\n\n\n\n\n". 'In getLanguagesAvailable '. "\n";
    // flags
    $sql = "SELECT * FROM content 
                WHERE filename = 'countries'  
                ORDER BY recnum DESC LIMIT 1";
    $out['debug'] .= "$sql \n";
    $data = sqlArray($sql);
    $countries_array = json_decode($data['text']);
    //find prototype countries data
    //
    $sql = "SELECT distinct country_code FROM content 
        WHERE  prototype_date != ''
        AND country_code != '' ";
    $query = sqlMany($sql);
    while($country = $query->fetch_array()){
        // get prototyped languages from each prototyped country
        $sql = "SELECT * FROM content 
            WHERE  country_code = '". $country['country_code'] ."' 
            AND filename = 'languages'  AND prototype_date != '' 
            ORDER BY recnum DESC LIMIT 1";
        $data = sqlArray($sql);
        $text = json_decode($data['text']);
        if (!isset($text->languages)){
            $out['debug'] .= '$text->languages not found for ' . $country['country_code']. "\n";
            $out['error'] = true;
        }
        else{
            // look for flag
            $flag = 'unknown';
            foreach ($countries_array as $country_object){
                if ($country_object->code == $country['country_code']){
                    $flag = '../images/country/'. $country_object->image;
                }
            }
            $out['debug'] .= "$flag is flag for " .  $country['country_code']. " \n";
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
            usort($available, '_sortByIso');

        }

    }
    $out['content']= $available;
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
