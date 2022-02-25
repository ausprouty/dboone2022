<?php
myRequireOnce ('//writeLog.php');
// removes readmore from text; used in SD Cards
function modifyReadMore($text, $bookmark){
    //writeLog('modifyReadMore-6-text', $text);
    $debug = '';
    $find = array();
    $find[] = '<a class="readmore"';
    $find[] = '<a class="bible-readmore"';
    $read_more = $bookmark['language']->read_more;
    $read_more_online = $bookmark['language']->read_more_online;
    //writeLog('modifyReadMore-12-bookmark',  $bookmark);
    //writeLog('modifyReadMore-13-readmore', $read_more . '|'. $read_more_online);
    $new = '';
    foreach ($find as $find_now){
        $count = substr_count($text, $find_now);
        $pos_start = 0;
        for ($i = 0; $i < $count; $i++){
            $debug .= "\n\n\nCount: $i \n\n";
            $pos_start = strpos($text, $find_now, $pos_start );
            $debug .= "\n\nPos Start: $pos_start \n";
            $pos_end =  strpos($text, '</a>', $pos_start );
            $length=$pos_end- $pos_start+4;
            $old = substr($text, $pos_start, $length);
             //writeLog('modifyReadMore-24-old',  $old);
            $new =str_ireplace($read_more, $read_more_online, $old);
            //writeLog('modifyReadMore-26-new',  $new);
            $text = substr_replace($text, $new, $pos_start, $length);
            $pos_start = $pos_end;
        }
    }

    //writeLog('modifyReadMore-21-text', $text);
    return $text;
}