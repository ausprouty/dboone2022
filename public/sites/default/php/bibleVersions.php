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
    $output = [];
    $language_iso = $p['language_iso'];
    $testament = $p['testament'];
    $conn = new mysqli(HOST, USER, PASS, DATABASE_BIBLE);
    $conn->set_charset("utf8");
    $sql = "SELECT bid, volume_name FROM dbm_bible WHERE language_iso = '$language_iso'
        AND (collection_code = '$testament' OR collection_code = 'FU')
        AND (source = 'dbt'  OR source = 'bible_gateway') AND text = 'Y'
        ORDER BY volume_name";
    $debug = $sql . "\n";
    $query = $conn->query($sql);
    $count = 0;
     while ($data = $query->fetch_object()){
        $bible = new stdClass();
        $bible->bid =  $data->bid;
        $bible->volume_name=  utf8_encode($data->volume_name);
        $out[] = $bible;
        $count++;
    }
    if ($count <1){
        $message = "No Bibles for  ". $p['language_iso'];
        writeLogError('getBibleVersions-36', $message);
        $out= [];
    }
    $conn->close();
    return $out;
}