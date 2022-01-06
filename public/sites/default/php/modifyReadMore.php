
<?php
myRequireOnce('writeLog.php');
// removes readmore from text; used in SD Cards
function modifyReadMore($text){
    writeLog('modifyReadMore-6-text', $text);
    $find = '<a class="readmore"';
    $new = '';
    $count = substr_count($text, $find);
    $pos_start = 0;
    for ($i = 0; $i < $count; $i++){
        $debug .= "\n\n\nCount: $i \n\n";
        $pos_start = strpos($text, $find, $pos_start );
        $debug .= "\n\nPos Start: $pos_start \n";
        $pos_end =  strpos($text, '</a>', $pos_start );
        $length=$pos_end- $pos_start+4;

        $text = substr_replace($text, $new, $pos_start, $length);
        $pos_start = $pos_end;
    }
    writeLog('modifyReadMore-21-text', $text);
    return $text;
}