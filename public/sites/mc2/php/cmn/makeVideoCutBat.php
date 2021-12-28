<?php

require_once ('../../.env.api.remote.mc2.php');
myRequireOnce ('.env.cors.php');

$template = 'ffmpeg  -accurate_seek -i [old_name].mp4 -ss [start] -to [end]  -c copy [new_name].mp4' . "\n";
$source = file_get_contents( 'CutIsoSort.txt');

$lines = explode("\n", $source);
$index = 0;
$output = '';
$last_session = '';
foreach ($lines as $line){
    $item = explode("\t", $line);
    $session = trim($item[0]);
    $new_name = substr($session, -3);
    if ($new_name == $last_session){
        $new_name = $new_name . 'B';
    }
    $last_session = $new_name;
    $reference = $item[1];
    $url = $item[2];
    $old_name = $item[3];
    $start = _format_time($item[4]);
    $end = _format_time(trim($item[5]));

    $placeholders = array(
        '[old_name]',
        '[start]',
        '[end]',
        '[new_name]'
    );
    $replace = array(
        $old_name,
        $start,
        $end,
        $new_name
    );
    $output .= str_replace($placeholders, $replace,  $template);
}
echo $output;

function _format_time($str){
    if ($str == 'end'){
        return '01:00:00';
    }
    $format= 'H:i:s';
    $timestamp = strtotime('00:'. $str);
    return date($format,$timestamp);
}





function _file_get_contents_utf8($fn) {
    $content = file_get_contents($fn);
     return mb_convert_encoding($content, 'UTF-8',
         mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true));
}
function _file_get_contents_single($fn) {
    $content = file_get_contents($fn);
     return mb_convert_encoding($content, 'ISO-8859-1',
         mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true));
}
