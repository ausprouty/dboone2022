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
    writeLogAppend('videoMakeBatFileForApkFollows-334', $message);
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