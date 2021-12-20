<?php

myRequireOnce ('sql.php');
/* checks to see if all files in Series are in database and ready to be prototyped
   requires $p['recum'] of series index

   $text  (from series index):
    {
	"description": "",
	"download_now": "Offline verfÃ¼gbar machen",
	"download_ready": "Bereit fÃ¼r den Offline-Einsatz",
	"chapters": [{
		"id": 1,
		"title": "Der Garten",
		"desciption": "",
		"count": 1,
		"filename": "basics101",
		"prototype": true
	}, {
*/
function readytoPrototypeSeries($p){
    $p['status'] = 'prototype';
    $out['error'] = false;
    $out['data'] = false;
    $out['message'] = '';
    $out ['debug'] = 'readytoPrototypeSeries '. "\n";
    $series = objectFromRecnum($p['recnum']);
    if (!isset($series->text)){
        $out ['debug'] .=  'FATAL ERROR: No value for series->text' . "\n";
        $out['error'] = true;
        return $out;
    }
    $text = json_decode($series->text);
    $chapters = $text->chapters;
    foreach ($chapters as $chapter){
        if ($chapter->prototype){
            $sql = "SELECT recnum FROM content 
                WHERE  country_code = '". $series->country_code ."' 
                AND  language_iso = '". $series->language_iso ."' 
                AND folder_name = '" .$series->folder_name ."'  
                AND filename = '". $chapter->filename . "' 
                LIMIT 1";
            $found = sqlArray($sql);
            if ($found['recnum']){
               // $out['message'] .= $chapter->filename . ' ready'. "\n";
                $out['debug'] .= $chapter->filename . ' ready'. "\n";
            }
            else{
                $out['message'] .= $chapter->filename . ' NEEDS TO BE EDITED FIRST'. "\n";
                $out['debug'] .= $chapter->filename . ' NEEDS TO BE EDITED FIRST'. "\n";
                $out['error'] = true;
            }
        }
        else{

            $out['message'] .= $chapter->filename . ' NOT set to PUBLISH'. "\n";

        }

    }
    if (!$out['error']){
        $out['data'] = true;
    }
    return $out;
}