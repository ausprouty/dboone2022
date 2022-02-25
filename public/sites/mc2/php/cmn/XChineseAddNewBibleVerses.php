<?php
require_once ('../../.env.api.remote.mc2.php');
myRequireOnce ('sql.php');
myRequireOnce ('.env.cors.php');
myRequireOnce ('getLatestContent.php');
myRequireOnce ('create.php');
myRequireOnce ('bibleGetPassageBiblegateway.php');

$fixing = 'multiply1';
$debug = "In Fix Bible Reference<br>\n";
$sql = 'SELECT DISTINCT filename FROM content 
    WHERE language_iso = "cmn"
    AND country_code = "M2"
    AND folder_name = "multiply1"
    AND filename != "index"
    ORDER BY filename';
$query  = sqlMany($sql);
while($data = $query->fetch_array()){
    $debug .= $data['filename'] . "<br>\n";
    $p = array(
        'scope'=> 'page',
        'country_code' => 'M2',
        'language_iso' => 'cmn',
        'folder_name' => 'multiply1',
        'filename' => $data['filename']
    );
   $res = getLatestContent($p);
   $new = $res['content'];
   $new['text'] = _fix($new['text']);
  // append ($data['filename'] , $new['text'] ."\n");
  // echo nl2br($new['text'] . "\n\n\n");
   $new['my_uid'] = 998; // done by computer
   createContent($new);
 }
 return;

 function _fix($text){
    $p = [];
    $p['version_code'] = 'CUVMPS';
    $count = substr_count($text,'<div class="reveal bible">' );
    $pos_start = 0;
    for ($i = 0; $i < $count; $i++){
        $pos_start = strpos($text,'<div class="reveal bible">', $pos_start);
        $pos_end = strpos($text, '<hr /></div>', $pos_start);
        $length = $pos_end - $pos_start + 12;
        $start = $pos_start + 25;
        $block = substr($text, $start, $length);
        $p['entry'] = _getHref($block);
        $reference = str_replace( '%20', ' ' , $p['entry']);
        $res = bibleGetPassageBiblegateway($p);
        $bible_text = _deleteH3( $res['content']['text']);
        $new = '<div class="reveal bible">&nbsp;
        <hr />
        <p class="reference">' . $reference . '</p>';
        $new .= $bible_text. "\n"; // <being Bible> <End Bible>
        $new .= '<p><a class="readmore" href="https://biblegateway.com/passage/?search='. $p['entry'] . '&amp;version=CUVMPS" target="_blank">更多经文</a></p>
        <hr /></div>' . "\n";
        $text = substr_replace($text, $new, $pos_start, $length);
        $pos_start = $pos_end;
    }
    return $text;
 }
 function _deleteH3($text){
    $count = substr_count($text,'<h3>' );
    $pos_start = 0;
    for ($i = 0; $i < $count; $i++){
        $pos_start = strpos($text,'<h3>', $pos_start);
        $pos_end = strpos($text, '</h3>', $pos_start);
        $length = $pos_end - $pos_start + 5;
        $text = substr_replace($text, '', $pos_start, $length);
        $pos_start = $pos_end;
    }
    return $text;

 }
 function _getHref($text){
    $pos_start = mb_strpos($text,'?search=');
    $pos_end = mb_strpos($text, '&', $pos_start + 8);
    $length = $pos_end - $pos_start - 8;
    $start = $pos_start + 8;
    $reference = mb_substr($text, $start, $length);
    return $reference;
 }

 function append($filename, $content){
    $root_log = '';
    if (!is_array($content)){
        $text = $content;
    }
    else{
        $text = '';
        foreach ($content as $key=> $value){
            $text .= $key . ' => '. $value . "\n";
        }
    }
    $fh = $root_log . $filename . '.txt';
    file_put_contents($fh, $text,  FILE_APPEND | LOCK_EX );
}
