<?php

require_once ('../../.env.api.remote.php');
require_once('../../../default/php/myRequireOnce.php');
myRequireOnce ('sql.php');
myRequireOnce ('writeLog.php');
myRequireOnce('dirMake.php');
$text = '';
$p = [];
$p['language_iso'] = 'amh';
$p['country_code'] = 'M2';
$p['folder_name'] = 'multiply2';
$sql = 'SELECT * FROM dbt_videos ORDER BY id';
$query = sqlMany($sql);
while($data = $query->fetch_array()){
    $video_array = JSON_DECODE($data['video_array']);
    $count = 0;
    foreach ($video_array as $video){
        $count++;
        $line = 'ffmpeg -user_agent "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_5) AppleWebKit/601.7.8 (KHTML, like Gecko) Version/9.1.3 Safari/537.86.7" -i ';
        $line .= '"'.  $video;
        $line .= '?v=4&key=1462b719-42d8-0874-7c50-905063472458" -c copy  "';
        $line .= $data['lesson'] .'-'.$data['id'] . '-'. $count . '.mp4"';
        $line .= "\n";
        writeLogAppend ('createDbtBatFile-23', $line);
        $text .= $line;
    }

}
$dir = ROOT_EDIT  . 'sites/' . SITE_CODE  . '/dbt/' . $p['country_code'] . '/' . $p['language_iso'] . '/';
dirMake($dir);
$filename=  $p['folder_name'] .  'video.bat';
$fh = fopen( $dir. $filename, 'w');
fwrite($fh, $text);
fclose($fh);
echo ($dir . $filename);