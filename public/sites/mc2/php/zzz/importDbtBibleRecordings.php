<?php
require_once ('../../.env.api.remote.php');
require_once('../../../default/php/myRequireOnce.php');
myRequireOnce ('sql.php');
myRequireOnce ('writeLog.php');
myRequireOnce ('sql.php');
$output = '';

$file ='sites/mc2/php/zzz/bible_recordings_tab.txt';
$text_file = file_get_contents(ROOT_EDIT .  $file);
$lines = explode("\n", $text_file);
foreach ($lines as $line){
    $items = explode ("\t", $line);
    if (isset($items[1])){

        $iso = $items[1];
        $lang_name = $items[2];
        $bible_name = $items[3];
        $bible_id = $items[4];
        $population = $items[5];
        $country = $items[6];
        $video_date = _myYear($items[7]);
        $audio_date = _myYear($items[8]);
        $text_date = _myYear($items[9]);
 $sql = "INSERT INTO dbt_bible_recordings (iso, lang_name, bible_name, bible_id, `population`, country, video_date, audio_date,text_date) VALUES
            ('$iso', '$lang_name', '$bible_name', '$bible_id', $population, '$country', $video_date, $audio_date, $text_date)";
        writeLogDebug('importDbt-23', $sql);
        sqlArray($sql, $update = 'insert');

    }
}

echo 'check file';
return;


function _myYear($item){
    if ($item == 'NULL'){
        return 0;
    }
    $year = substr($item, 0,4);
    return $year;
}