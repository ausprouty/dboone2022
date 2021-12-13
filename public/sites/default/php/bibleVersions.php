<?php
/* requires:
   $p['language_iso'];
   $p['testament'];

 returns:
    array of objects
       bid (Bible ID from database)
       volume_name (Name of Bible)
 */

function getBibleVersions($p){
    $out = [];
    $out['content'] = [];
    $output = [];
    $language_iso = $p['language_iso'];
    $testament = $p['testament'];
    $conn = new mysqli(HOST, USER, PASS, DATABASE_BIBLE);
    $conn->set_charset("utf8");
    $sql = "SELECT bid, volume_name FROM dbm_bible WHERE language_iso = '$language_iso'
        AND (collection_code = '$testament' OR collection_code = 'FU')
        AND (source = 'dbt'  OR source = 'bible_gateway') AND text = 'Y'
        ORDER BY volume_name";
    $out['debug'] = $sql . "\n";
    $query = $conn->query($sql);
    $count = 0;
     while ($data = $query->fetch_object()){
        $bible = new stdClass();
        $bible->bid =  $data->bid;
        $bible->volume_name=  utf8_encode($data->volume_name);
        $out['content'][] = $bible;
        $out['debug'] .= $data->volume_name . "\n";
        $out['debug'] .= json_encode($bible, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES); . "\n\n";
        $count++;
    }
    if ($count > 0){
        $out['error'] = false;
        $out['debug'] .= 'encode of all';
        $out['debug'] .= json_encode( $out['content'], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES). "\n";
    }
    else{
        $out['error'] = true;
        $out['message'] = "No Bibles for that langauge\n";
        $out['content']= [];
    }
    $conn->close();
    return $out;
}