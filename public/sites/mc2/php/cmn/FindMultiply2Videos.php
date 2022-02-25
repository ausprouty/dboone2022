<?php
require_once ('../../.env.api.remote.mc2.php');
//require_once ('../../.env.api.local.mc2.php');
myRequireOnce ('sql.php');
myRequireOnce ('.env.cors.php');
myRequireOnce ('getLatestContent.php');
myRequireOnce ('create.php');

$find = 'href="https://api.arclight.org/';

$debug =  "In Find Multiply 2 Videos<br>\n";
$sql = 'SELECT DISTINCT filename FROM content 
    WHERE language_iso = "eng"
    AND country_code = "M2"
    AND folder_name = "multiply2"
    AND filename != "index"
    ORDER BY filename';
 $query  = sqlMany($sql);
 while($data = $query->fetch_array()){
     $debug .= $data['filename'] . "<br>\n";
     $p = array(
         'scope'=> 'page',
         'country_code' => 'M2',
         'language_iso' => 'eng',
         'folder_name' => 'multiply2',
         'filename' => $data['filename']
     );
    $response = getLatestContent($p);
    $res = $response['content'];
    $found = [];
    $count = substr_count($res['text'], $find);
    $pos_start = 0;
    for ($i = 1; $i <= $count; $i++){
        $pos_start = strpos ($res['text'], $find, $pos_start);
        $pos_end = strpos($res['text'], '"', $pos_start + 7);
        $length = $pos_end - $pos_start;
        $link = substr($res['text'], $pos_start, $length);
        if (!in_array($link, $found)){
            $found[] = $link;
            echo  $link . "<br>";
        }
        $pos_start = $pos_end;
    }
    $new['my_uid'] = 999; // done by computer
 }
 echo ($debug);
 _writeThisLog('FindMultiply2Videos-' , $debug);
 return;

 
 function _writeThisLog($filename, $content){
	if (!is_array($content)){
		$text = $content;
	}
	else{
		$text = '';
		foreach ($content as $key=> $value){
			$text .= $key . ' => '. $value . "\n";
		}
	}
	$fh = fopen('log/'. $filename . '.txt', 'w');
	fwrite($fh, $text);
    fclose($fh);
}
