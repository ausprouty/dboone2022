<?php

require_once ('../../.env.api.remote.mc2.php');
myRequireOnce ('.env.cors.php');
myRequireOnce ('sql.php');
$codes = [];

$files = array(
    '/api/chinese/M1cmn.txt',
    '/api/chinese/M2cmn.txt',
    '/api/chinese/M3cmn.txt',
);
foreach ($files as $file){
    $text_file = file_get_contents(ROOT_EDIT .  $file);
    $lines = explode("\n", $text_file);
    foreach ($lines as $line){
        if (strpos($line, '<') !== FALSE){
            $instruction = _getInstruction($line);
            $code[ $instruction] = $instruction;
        }
    }
}
sort($code);
foreach ($code as $c){
    echo nl2br($c . "\n");
}
return;


function _getInstruction($line){
    $end = mb_strpos($line, '>');
    $instruction = trim(mb_substr($line, 0 , $end + 1));
    return $instruction;
}
