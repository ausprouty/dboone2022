<?php
function bibleUpdateABSJson($p){
    
    $total = 0;
    $debug = 'in bibleUpdateAbsJson' . "\n";
    $text = file_get_contents(ROOT_LOG .  'scripture.api.bible.json');
    $abs = json_decode($text);
    $volumes = $abs->data;
    foreach ($volumes as $volume){
        $total++;
        $source= 'abs';
        $dam_id= $volume->dblId;
        $volume_name= _makesafe($volume->name);
        $language_code= $volume->language->id;
        $language_name= _makesafe($volume->language->nameLocal);
        $language_english= _makesafe($volume->language->name) ;
        $language_iso= $volume->language->id;
        $version_code= $volume->abbreviation;
        $collection_code='FU';
        if ($volume->language->scriptDirection == 'LTR'){
            $right_to_left  = 'f';
        }
        else{
            $right_to_left = 't';
        }
        if ($volume->type == 'text'){
            $text = 'Y';
        }
        else{
            $text = 'N';
        }
        $audio = 'N';
        $web = 'Y';
        $mobile = 'Y';
        $sql = "INSERT into dbm_bible (source,dam_id, volume_name, 
            language_code, language_name, language_english, language_iso,
            version_code, collection_code, right_to_left, text, audio, mobile, web) values 
            ('$source','$dam_id','$volume_name',
            '$language_code','$language_name','$language_english','$language_iso',
            '$version_code','$collection_code','$right_to_left', '$text','$audio','$mobile','$web')";
        $result = sqlBibleInsert($sql);

        $debug .= $sql .  "\n";
 
    }
    $debug .= "\n\n\n\n" . $total ;
    return $out;
}
function bibleABSnew(){
    $found_count = 0;
    $tried_count = 0;
    $sql = "SELECT distinct language_iso FROM dbm_bible 
        WHERE source = 'abs'";
    $result = sqlBibleMany($sql);
    while($data = $result->fetch_array()){
        $tried_count++;
        $sql = "SELECT language_iso FROM dbm_bible 
            WHERE source != 'abs' 
            AND language_iso = '" . $data['language_iso'] ."'";
        $found = sqlBibleArray($sql);
        if (isset($found['language_iso'])){
            $found_count++;
        }
    }
    $out = $found_count . '/'. $tried_count;
    return $out;

}

function _makesafe($text){
    $out = str_replace("'", "\'", $text);
    return $out;

}