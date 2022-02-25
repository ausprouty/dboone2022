<?php
require_once ('../.env.api.remote.mc2.php');
myRequireOnce ('sql.php');
myRequireOnce ('.env.cors.php');
myRequireOnce ('getLatestContent.php');
myRequireOnce ('create.php');

$fixing = 'multiply1';

$debug = "In Fix Video<br>\n";
$sql = 'SELECT DISTINCT filename FROM content 
    WHERE language_iso = "cmn"
    AND country_code = "M2"
    AND folder_name = "multiply3"
    AND filename != "index"
    ORDER BY filename';
 $query  = sqlMany($sql);
 while($data = $query->fetch_array()){
     $debug .= $data['filename'] . "<br>\n";
     $p = array(
         'scope'=> 'page',
         'country_code' => 'M2',
         'language_iso' => 'cmn',
         'folder_name' => 'multiply3',
         'filename' => $data['filename']
     );
    $res = getLatestContent($p);
    $new = $res['content'];
    $new['text'] = _fix($new['text']);
    
    $new['my_uid'] = 999; // done by computer
    createContent($new);

 }
 echo ($debug);
 _writeThisLog('fixBible-' , $debug);
 return;

 function  _fix($text){
    $bad= '<div class="reveal video">' ;
    $good = '<div class="reveal video lumo">';
    $text = str_replace($bad, $good, $text);

    return $text;
 }
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
	$fh = fopen(ROOT_LOG . $filename . '.txt', 'w');
	fwrite($fh, $text);
    fclose($fh);
}
