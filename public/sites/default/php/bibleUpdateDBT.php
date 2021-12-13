<?php
// https://dbt.io/library/volume

define("KEY", '3d116e49d7d98c6e20bf0f4a9c88e4cc');
myRequireOnce ('vendor/dbt/dbt.inc');
function bibleUpdateDBT($p){
    $out = [];
    $out['debug'] = 'I was in Bible Update';
    $dbt = new Dbt (KEY);
   // $text = 'hi there';
    $text = $dbt->getLibraryVolume();
    $fh = fopen(ROOT_LOG . 'dbt.txt', 'w');
	fwrite($fh, $text);
    fclose($fh);
    return $out;
}

function bibleCheckDBTIndex($p){
    $out = [];
    $found = 0;
    $total = 0;
    $out['debug'] = 'in bibleCheckDBTIndex' . "\n";
    $text = file_get_contents(ROOT_LOG .  'dbt.txt');
    $volumes = json_decode($text);
    foreach ($volumes as $volume){
        $sql = "SELECT bid, volume_name FROM dbm_bible WHERE
            dam_id = '" .  $volume->dam_id ."'";
        $data = sqlBibleArray($sql);
        $total++;
        if (isset($data['bid'])){
            $found++;
           // $out['debug'] .= $volume->dam_id . ' -- ' . $data['bid'] ."\n";
        }
        else{
            $source= 'dbt';
            $dam_id= $volume->dam_id;
            $volume_name= clean($volume->volume_name);
            $language_code= $volume->language_code;
            $language_name= clean($volume->language_name);
            $language_english= clean($volume->language_english) ;
            $language_iso= $volume->language_iso;
            $version_code= $volume->version_code;
            $collection_code=$volume->collection_code;
            if ($volume->right_to_left == TRUE){
                $right_to_left  = 't';
            }
            else{
                $right_to_left = 'f';
            }
            if ($volume->media == 'text'){
                $text = 'Y';
            }
            else{
                $text = 'N';
            }
            if ($volume->media == 'audio'){
                $audio = 'Y';
            }
            else{
                $audio = 'N';
            }
            $delivery = $volume->delivery;
            if (in_array('web', $delivery)){
                $web = 'Y';
            }
            else{
                $web = 'N';
            }
            if (in_array('mobile', $delivery)){
                $mobile = 'Y';
            }
            else{
                $mobile = 'N';
            }
            $sql = "INSERT into dbm_bible (source,dam_id, volume_name,
                language_code, language_name, language_english, language_iso,
                version_code, collection_code, right_to_left, text, audio, mobile, web) values
                ('$source','$dam_id','$volume_name',
                '$language_code','$language_name','$language_english','$language_iso',
                '$version_code','$collection_code','$right_to_left', '$text','$audio','$mobile','$web')";
            $result = sqlBibleInsert($sql);

            $out['debug'] .= $sql .  "\n";
        }
    }
    $out['debug'] .= "\n\n\n\n" . $found .'/'. $total ;
    return $out;
}

function bibleCheckDBTDetail($p){
    $out = [];
    $out['content'] ='';
    $found = 0;
    $total = 0;
    $out['debug'] = 'in bibleCheckDBTDetail' . "\n";
    $text = file_get_contents(ROOT_LOG .  'dbt.txt');
    $volumes = json_decode($text);
    foreach ($volumes as $volume){
        if (isset($p['dam_id'])){
            if ($volume->dam_id == $p['dam_id']){
                $out['content'] .= json_encode($volume, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
            }
        }
        if (isset($p['language_iso'])){
            if ($volume->language_iso == $p['language_iso']){
                $out['content'] .= json_encode($volume, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
            }
        }

    }
    return $out;

}

function clean($text){
    $out = str_replace("'", "\'", $text);
    return $out;

}