<?php
/*[0]=>
  array(7) {
    ["filename"]=>
    string(11) "multiply208"
    ["new_name"]=>
    string(3) "208"
    ["title"]=>
    string(12) "John 1:19-34"
    ["download_name"]=>
    string(21) "lumo/LUMOJohn1134.mp4"
    ["url"]=>
    string(10) "GOJohn2201"
    ["start_time"]=>
    int(213)
    ["end_time"]=>
    int(384)
  }
  [1]=>
  array(7) {
    ["filename"]=>
    string(11) "multiply208"
    ["new_name"]=>
    string(5) "208-1"
    ["title"]=>
    string(12) "John 1:35-51"
    ["download_name"]=>
    string(23) "lumo/LUMOJohn135222.mp4"
    ["url"]=>
    string(10) "GOJohn2202"
    ["start_time"]=>
    int(0)
    ["end_time"]=>
    int(160)
  }
*/
//["url"]=> "Acts7312-0-0"

function videoFollows($previous_url, $url){
    if (!$previous_url){
        return NULL;
    }
    $previous_url_clean = str_replace('-0-0', '',  $previous_url);
    $url_clean = str_replace('-0-0', '',  $url);
    $previous_number= substr($previous_url_clean, -4);
    $this_number= substr($url_clean, -4);
    $message= " for $previous_url, $url we have $previous_number and $this_number";
    if (!is_numeric($previous_number)){
        return NULL;
    }
    if ($previous_number + 1 != $this_number){
        return NULL;
    }
    $length = strlen($url_clean);
    $video ='';
    for ($i = 0; $i < $length; $i++){
        $char = substr($url_clean, $i, 1);
        if (is_numeric($char)){
            if (substr($url_clean,0, $i) == substr($previous_url_clean,0, $i)){
                return $previous_url;
            }
            else{
                return NULL;
            }
        }
    }
    return NULL;
}
// you need to change the previous title phrase to include the entire passage this video shows
function videoFollowsChangeVideoTitle($previous_title_phrase, $text, $bookmark){
    $pos_title_phrase= mb_strpos($text, $previous_title_phrase);
     if ( $pos_title_phrase === FALSE){
        writeLogAppend('ERROR- videoFollowsChangeVideoTitle-75', $previous_title_phrase);
       return $text;
    }
    $minus_title_phrase = 0 - $pos_title_phrase;
    $find = 'collapsible bible">';
    $pos_read_start = mb_strpos($text, $find);
    if ($pos_read_start === FALSE){
        writeLogError('videoFollowsChangeVideoTitle-84', $find);
        return $text;
    }
    $pos_read_start = $pos_read_start + strlen($find);
    $pos_read_end = mb_strpos($text, '</button>',  $pos_read_start);
    $length =  $pos_read_end- $pos_read_start;
    $reference = mb_substr($text, $pos_read_start, $length);
    // from https://stackoverflow.com/questions/10066647/multibyte-trim-in-php
    // did not work
    //$reference = preg_replace('~^\s+|\s+$~us', '', $reference);
    $read_phrase = $bookmark['language']->read;
    $read_phrase = trim(str_replace ( '%', '', $read_phrase ));
    $reference =str_replace ($read_phrase, '', $reference);
    $watch_phrase= $bookmark['language']->watch_offline;
    $new_title_phrase = str_replace('%', $reference, $watch_phrase );
    $debug= array(
        'previous_title_phrase'=> $previous_title_phrase,
        'pos_title_phrase' => $pos_title_phrase,
        'pos_read_start'=> $pos_read_start,
        'pos_read_end'=>$pos_read_end,
        'length'=> $length,
        'reference'=> $reference,
        'new_title_phrase'=>$new_title_phrase
    );
    writeLogAppend('videoFollowsChangeVideoTitle-101', $debug );
    $text =str_replace($previous_title_phrase, $new_title_phrase, $text);
    return $text;
}