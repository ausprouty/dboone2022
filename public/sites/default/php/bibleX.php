<?php

function getBibleVersions($p){
    
    $output = [];
    $language_iso = $p['language_iso'];
    $testament = $p['testament'];
    $conn = new mysqli(HOST, USER, PASS, DATABASE_BIBLE);
    $conn->set_charset("utf8");
    $sql = "SELECT bid, volume_name FROM dbm_bible WHERE language_iso = '$language_iso'
        AND (collection_code = '$testament' OR collection_code = 'FU')
        AND text = 'Y'";
    $debug = $sql . "\n";
    $query = $conn->query($sql);
    $json = '[';
    $count = 0;
     while ($data = $query->fetch_object()){
        $json .= '{"bid":"' . $data->bid . '","volume_name":"'. $data->volume_name . '"},';
        $debug .= $data->volume_name . "\n";
        $count++;
    }
    if ($count > 0){
        $json = substr($json, 0, -1);
        $json .= ']';
        $out= json_decode($json);
	    $out['error'] = false;
    }
    else{
        $out['error'] = true;
        $out['message'] = "No Bibles for that langauge\n";
        $out= [];
    }
    $conn->close();
    return $out;
}