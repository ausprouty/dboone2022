<?php

require_once ('../../.env.api.remote.mc2.php');
myRequireOnce ('sql.php');
myRequireOnce ('.env.cors.php');
myRequireOnce ('getLatestContent.php');
myRequireOnce ('create.php');
myRequireOnce ('videoLinksFind.php');
$list = '';
$debug = "In Find Video Links<br>\n";
$sql = 'SELECT DISTINCT filename FROM content 
    WHERE language_iso = "cmn"
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
         'language_iso' => 'cmn',
         'folder_name' => 'multiply2',
         'filename' => $data['filename']
     );
    $res = getLatestContent($p);
    $new = $res['content'];
    $text = $new['text'];
    if (strpos($text, '<div class="reveal film"') !== FALSE){
        $list .=  videoLinksFind($text, $data['filename']);
    }
 }
 writeThisLog('findVideoMultiply2', $list);
 //echo $debug;
 echo $list;
 return;




 function writeThisLog($filename, $content){
	if (!is_array($content)){
		$text = $content;
	}
	else{
		$text = '';
		foreach ($content as $key=> $value){
			$text .= $key . ' => '. $value . "\n";
		}
	}
	$fh = fopen(ROOT_LOG . $filename . '.txt', 'w');
	fwrite($fh, $text);
    fclose($fh);
}