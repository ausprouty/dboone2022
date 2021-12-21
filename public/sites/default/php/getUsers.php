<?php

myRequireOnce ('sql.php');

function getUsers($params){
    $sql = 'SELECT * FROM members ORDER BY firstname ASC';
    $query = sqlMany($sql);
    $json = '[';
    while($member = $query->fetch_array()){
        $json .= '{ "firstname": "'. $member['firstname'] . '",';
        $json .= ' "lastname": "'. $member['lastname'] . '",';
        $json .= ' "scope_countries": "'. $member['scope_countries'] . '",';
        $json .= ' "scope_languages": "'. $member['scope_languages'] . '",';
        $json .= ' "start_page": "'. $member['start_page'] . '",';
        $json .= ' "uid": "'. $member['uid'] . '"},';
     }
     $json = substr($json, 0, -1) . ']';
     $out = $json;
    return $out;
}
