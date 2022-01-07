<?php
myRequireOnce ('writeLog.php');

function modifyHeaders($text){
    $debug = 'I am in modifyHeaders'."\n";
    $debug .= $text ."\n\n\n";
    $headers = '';
    $bad = ['<div class="header"></div>', '<pre>', '</pre>', '</div>', '<p>','</p>','<br>', '<br/>', '<br />'];
    $output = array();
    $find = '<div class="header';
    $pos_start = strpos($text,$find);
    $pos_end = strpos($text, '</div>', $pos_start);
    $length = $pos_end - $pos_start + 6;  // add 6 because last item is 6 long
    $headings = substr($text, $pos_start, $length);
    $debug .= 'Headings: ' .$headings ."\n\n\n";
    // remove heading from text
    $text = substr_replace($text, '', $pos_start, $length);
    // clean headings
    $headings= str_ireplace($bad, '', $headings);
    $lines = explode("\n", $headings);
    foreach ($lines as $line){
        $debug .= 'Line: ' .$line ."\n\n\n";
        $part = explode('|', $line);
        if ($part[0] == 'script'){
            $headers .='<script src="' . $part[1] . '"></script>'."\n";

        }
        if ($part[0] == 'stylesheet'){
             $headers .= '<link rel="stylesheet" href="'. $part[1]. '" type="text/css" />'. "\n";
        }
    }
    $debug .= $headers;
    //writeLog('modifyHeaders', $debug);
    $output['headers']= $headers;
    $output['text']= $text;
    $output['debug'] = $debug;
    return $output;

}