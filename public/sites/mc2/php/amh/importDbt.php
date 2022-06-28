<?php
require_once ('../../.env.api.remote.php');
require_once('../../../default/php/myRequireOnce.php');
myRequireOnce ('sql.php');
myRequireOnce ('writeLog.php');
myRequireOnce ('sql.php');
myRequireOnce('bibleDbtArray.php');
$output = '';
return;
$p = [];
$p['language_iso'] = 'eng';
$file ='sites/mc2/php/eng/Multiply2Passages.txt';
$text_file = file_get_contents(ROOT_EDIT .  $file);
$lines = explode("\n", $text_file);
foreach ($lines as $line){
    $items = explode ("\t", $line);
    if (isset($items[1])){
        $lesson= $items[0];
        $p['entry'] = trim($items[1]);
        if (strpos($p['entry'], ':') !== FALSE){
            $dbt = JSON_ENCODE(createBibleDbtArrayFromPassage($p));
            $sql = "INSERT INTO dbt_videos (lesson, passage,dbt_array) VALUES
                ('". $lesson . "','" . $p['entry'] . "','".  $dbt . "')";
            writeLogDebug('importDbt-23', $sql);
            sqlArray($sql, $update = 'update');
        }
    }
}
echo 'check file';
return;