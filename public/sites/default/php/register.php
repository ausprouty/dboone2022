<?php

myRequireOnce ('sql.php');

function registerUser($p){
    $out = array();
    $debug = 'Register User' ."\n";
    if (isset($p['authorizer'])){
        $sql = "SELECT countries FROM members WHERE uid = '" . $p['authorizer'] . "' LIMIT 1";
        $debug .= $sql. "\n";
        $check = sqlArray($sql);
        if ($check['countries'] == '*' ){
            $sql = "SELECT uid FROM members WHERE username = '". $p['username'] . "' LIMIT 1";
            $debug .= $sql. "\n";
            $content =sqlArray($sql);
            if (!$content){
                $hash = password_hash($p['password'], PASSWORD_DEFAULT);
                $debug .= $hash. "\n";
                $sql = "INSERT INTO members ( username, password,firstname, lastname, scope_countries, scope_languages, start_page) VALUES
                    ('". $p['username'] . "','" . $hash . "','".
                    $p['firstname'] . "','". $p['lastname'] . "','" . $p['scope'] .  "','".
                    $p['languages'] . "','" . $p['start_page']. "')";

                $debug .= $sql. "\n";
                sqlInsert($sql);
                $out = 'registered';

            }
            else{
                $out = "Username already in use";
            }
        }
        else{
            $out= "Not Authorized to add Editors";

        }
    }
    else{
        $out = "Not Registered";
    }
    return $out;
}
