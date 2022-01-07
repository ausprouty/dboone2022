<?php
myRequireOnce ('writeLog.php');

function modifyJavascript($text){
    $debug = '';
    $bad = ['<div class="javascript">', '<pre>', '</pre>', '</div>', '<p>','</p>','<br>', '<br/>', '<br />'];
    $output = array();
    $find = '<div class="javascript';
    $count = substr_count($text, $find);
    $debug .= $count . "\n";
    for ($i = 0; $i < $count; $i++){
        $script = '<script type="text/javascript">';
        $pos_start = strpos($text,$find);
        $pos_end = strpos($text, '</div>', $pos_start);
        $length = $pos_end - $pos_start + 6;  // add 6 because last item is 6 long
        $old = substr($text, $pos_start, $length);
        $script .= $old;
         // clean javascript
        $script = str_ireplace($bad, '', $script);
        $script = str_ireplace('&nbsp;', ' ', $script);
        $script = str_ireplace('&#39;', "'", $script);
        $script = str_ireplace('&quot;', '"', $script);
        $script = str_ireplace('&lt;', '<', $script);
        $script = str_ireplace('&gt;', '>', $script);
        $script = str_ireplace('&amp;', '&', $script);

        $script .= '</script>';
        $debug .= $old . "\n";
        // replace javascript
        $text = substr_replace($text, $script, $pos_start, $length);

    }
    //writeLog('modifyJavascript-25', $debug);
    return $text;

}