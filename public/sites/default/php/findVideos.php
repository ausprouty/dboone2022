<?php

myRequireOnce ('.env.api.remote.mc2.php');
myRequireOnce ('.env.cors.php');
myRequireOnce ('bibleDbtArray.php');
myRequireOnce ('getLatestContent.php');
myRequireOnce ('sql.php');
myRequireOnce ('writeLog.php');

$debug = "In Find VideO<br>\n";
$p = array(
    'scope'=> 'page',
    'country_code' => 'M2',
    'language_iso' => 'eng',
    'new_language_iso'=>'fra',
    'folder_name' => 'multiply2'
);

echo nl2br($debug);
$sql = "SELECT DISTINCT filename FROM  content
    WHERE country_code = '". $p['country_code'] . "'
    AND language_iso = '" . $p['language_iso'] . "'
    AND folder_name  = '" . $p['folder_name'] . "'
    ORDER BY filename";
$query = sqlMany($sql);

while($data = $query->fetch_array()){
    $video = '';
    $video_ref = '';
    $p['filename'] = $data['filename'];
    $page = getLatestContent($p);
    $text = $page['text'];

    if (strpos($text, '<div class="reveal bible">') !== FALSE){
        $debug .= _revealBible($text, $p['filename']);
    }

    // find videos
    // does not pick up readmore because the class occurs before the href
    if (strpos($text, '<a href="http') !== FALSE){
        /*
        <div class="reveal video">&nbsp;
        <hr /><a href="https://api.arclight.org/videoPlayerUrl?refId=2_529-Acts7322-0-0">Acts 8:34-40</a>
        */
        $count = substr_count($text,'<a href="http' );
        $video = array();
        $pos_start = 0;
        for ($i = 0; $i < $count; $i++){
            //find video
            $pos_start = mb_strpos($text,'<a href="http', $pos_start) + 9;
            $pos_end = mb_strpos($text, '>', $pos_start);
            $length = $pos_end - $pos_start -1;
            $video = mb_substr($text, $pos_start, $length);
            $video = _convertVideo($video);
            //find title
            $pos_title_end = mb_strpos($text, '<', $pos_end);
            $length = $pos_title_end - $pos_end -1;
            $title_start = $pos_end + 1;
            $video_ref = mb_substr($text, $title_start, $length);
            $video_ref = _convertTitle($video_ref, $p);
            $debug .=  $data['filename'] . ' || ' . $video . ' | ' . $video_ref. "\n";
        }
    }

}
//writeLog('findVideo-66', $debug);
echo nl2br($debug);
return;

function _convertVideo($video){
  if (strpos($video, 'English') !== FALSE){
      $video = str_replace('English', 'French', $video);
      $video = str_replace('6_529', '6_496', $video);
  }
  return $video;
}
function _convertTitle($passage, $p){
    $iso = $p['new_language_iso'];
    $p['passage'] = $text;
    $parts = explode(' ', $passage);
    $book = $parts[0];
    if ($book == 1 || $book == 2 || $book == 3){
        $book .= ' '. $parts[1];
    }
    $book_lookup = $book;
    if ($book_lookup == 'Psalm'){
        $book_lookup = 'Psalms';
    }
    // get valid bookId
    $conn = new mysqli(HOST, USER, PASS, DATABASE_BIBLE);
    $conn->set_charset("utf8");
    $sql = "SELECT $iso AS title FROM hl_online_bible_book
        WHERE  en  = '$book_lookup' LIMIT 1";
    $query = $conn->query($sql);
    $data = $query->fetch_object();
    $new =  $data->title;
    $passage = str_replace($book_lookup, $new, $passage);
    return $passage;
  }

 /* find Bible reference
    <div class="reveal bible">&nbsp;
    <hr />
    <p>Acts 8:34-40</p>
    */
function _revealBible($text, $filename){
    $out = '';
    $count = substr_count($text,'<div class="reveal bible">' );
    $pos_start = 1;
    for ($i = 0; $i < $count; $i++){
        $pos_start = mb_strpos($text,'<div class="reveal bible"', $pos_start);
        $pos_end = mb_strpos($text, '</p>', $pos_start);
        $length = $pos_end - $pos_start + 4;
        $old = mb_substr($text, $pos_start, $length);
        $word = strip_tags($old);
        $word = str_replace('&nbsp;', '', $word);
        $word = str_replace('  ', ' ', $word);
        $word = str_replace("\n", '', $word);
        $word = str_replace("\r", '', $word);
        $word = trim($word);
        $out .= $filename . '|'. $word . '||'. "\n";
        $pos_start = $pos_end;
    }

    return $out;

}
