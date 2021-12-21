<?php

myRequireOnce ('sql.php');

function getUser($params){
    $sql = 'SELECT * FROM members WHERE uid = ' . $params['uid'];
    $member = sqlArray($sql);
    $json = '';
    $json .= '{ "firstname": "'. $member['firstname'] . '",';
    $json .= ' "lastname": "'. $member['lastname'] . '",';
    $json .= ' "scope_countries": "'. $member['scope_countries'] . '",';
    $json .= ' "scope_languages": "'. $member['scope_languages'] . '",';
    $json .= ' "start_page": "'. $member['start_page'] . '",';
    $json .= ' "username": "'. $member['username'] . '",';
    $json .= ' "uid": "'. $member['uid'] . '"}';
     $out = $json;
    return $out;
}
