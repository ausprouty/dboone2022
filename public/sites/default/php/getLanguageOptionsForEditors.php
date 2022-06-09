<?php
myRequireOnce ('writeLog.php');

function getLanguageOptionsForEditors($p){
    $available = [];
    $sql = "SELECT distinct country_code FROM content";
    $query = sqlMany($sql);
    while($country = $query->fetch_array()){
        // get languages from each country
      $sql = "SELECT * FROM content
        WHERE  country_code = '". $country['country_code'] ."'
        AND filename = 'languages'
        ORDER BY recnum DESC LIMIT 1";
      $data = sqlArray($sql);
      $text = json_decode($data['text']);
      if (!isset($text->languages)){
          $message = '$text->languages not found for ' . $country['country_code'];
          writeLogError('getLanguageOptionsForEditors-18' , $message );
      }
      else{
          //writeLog('getLanguagesAvailable-44' , $text->languages );
          foreach ($text->languages as $language){
            $l = $language->iso;
            if (!isset($available[$l])){
               $available [$l] =  array(
                'code'=> '|'. $language->iso .'|',
                'display'=> $language->name,
              );
            }
          }
          writeLogDebug('getLanguageOptionsForEditorS-31' , $available );
          usort($available, '_sortByCode');
      }
    }
    $out= $available;
    writeLogDebug('getLanguageOptionsForEditors-36' , $out );
    return $out;
}

function _sortByCode($a, $b){
    if ($a['code'] = $b['code']){
        return 0;
    }
    if ($a['code'] > $b['code']){
        return 1;
    }
    return -1;
}
