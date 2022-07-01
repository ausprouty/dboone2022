<?php
require_once ('../../.env.api.remote.php');
require_once('../../../default/php/myRequireOnce.php');
myRequireOnce ('sql.php');
myRequireOnce ('writeLog.php');
myRequireOnce('bibleBrainGetVideoPlaylist.php');
$output = 'starting|';
$p = [];
 writeLogAppend('checkLumoForGospels-9', 'running');

//$p['fileset'] = 'INDNTV';
$sql = 'SELECT * FROM dbt_bible_recordings
    WHERE video_date != 0
    AND lumo_matthew IS NULL
    LIMIT 15';
$result = sqlMany($sql);
while($data = $result->fetch_array()){
    $p['language_iso'] = $data['iso'];
    $fileset = bibleBrainGetVideoFilesetForLanguage($p);
    writeLogAppend('checkLumoForGospels-19', $fileset);
    $output .= $fileset . ' | ';
    $matthew =  _hasLumo('MAT', $fileset);
    $mark =  _hasLumo('MRK', $fileset);
    $luke =  _hasLumo('LUK', $fileset);
    $john =  _hasLumo('JHN', $fileset);
    $id = $data['id'];
    $sql = "UPDATE dbt_bible_recordings
        SET lumo_matthew = $matthew, lumo_mark = $mark, lumo_luke = $luke, lumo_john = $john
        WHERE id = $id";
    sqlArray($sql, 'update');

}
echo $output;

function _hasLumo($gospel, $fileset){
    $p = [];
    $p['bookId'] = $gospel;
    $p['fileset'] = $fileset;
    $p['chapterId'] = 1;
    $p['verseStart'] = 1;
    $p['verseEnd'] = 2;
    $resp = bibleBrainGetVideoPlaylist($p);
    $found = 0;
    foreach ($resp as $r){
        if ($r != NULL){
          $found = 1;
        }
    }
    return $found;
}