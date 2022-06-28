<?php
require_once ('../../.env.api.remote.php');
require_once('../../../default/php/myRequireOnce.php');
myRequireOnce ('sql.php');
myRequireOnce ('writeLog.php');
myRequireOnce('bibleBrainGetVideo.php');
$output = '';
$p = [];
$p['language_iso'] = 'amh';
$sql = 'SELECT * FROM dbt_videos ORDER BY id';
$query = sqlMany($sql);
while($data = $query->fetch_array()){
    $dbt = JSON_DECODE($data['dbt_array']);
    $pObject = $dbt[0];
    $pObject->language_iso = $p['language_iso'];
    $p = (array) $pObject;
    $video= bibleBrainGetVideo($p);
    writeLogAppend('dbtVideoFind-18', $video);
    $video_array = JSON_ENCODE($video);
    writeLogAppend('dbtVideoFind-18', $video_array);
    $sql = "UPDATE dbt_videos SET video_array = '". $video_array . "'
        WHERE id = " . $data['id'] ;
    writeLogAppend('dtbVideoFind-20', $sql);
    sqlArray($sql, $update = 'update');
}
echo JSON_ENCODE($video_array);
return;