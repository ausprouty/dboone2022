<?php
echo 'in Trad Index';
require_once ('../../.env.api.remote.php');
require_once('../../../default/php/myRequireOnce.php');
echo ROOT_LOG;
myRequireOnce ('sql.php');
myRequireOnce ('.env.cors.php');
myRequireOnce ('getLatestContent.php');
myRequireOnce ('create.php');
myRequireOnce ('writeLog.php');


$fixing = 'multiply1';
$sql = 'SELECT DISTINCT filename FROM content
    WHERE language_iso = "cmt"
    AND country_code = "M2"
    AND folder_name = "multiply1"
    AND filename != "index"
    ORDER BY filename';
$query  = sqlMany($sql);
while($data = $query->fetch_array()){
     $debug = $data['filename'] . "<br>\n";
	 echo ($debug);
     $p = array(
         'scope'=> 'page',
         'country_code' => 'M2',
         'language_iso' => 'cmt',
         'folder_name' => 'multiply1',
         'filename' => $data['filename']
     );
	$new = getLatestContent($p);
	$text =  $new['text'];
	$traditional = _fix($text);
	$new['text'] = $traditional;
	$new['my_uid'] = 996; // done by computer
	createContent($new);
}
writeLogDebug('FixOutgoingBibleLinks'. time() , $debug);
 return;

 function  _fix($text){
	 $old = 'version=CUVMPS';
	 $new = 'version=CUVMPT';
	 if (strpos($text, ) !== FALSE){
		 $text= str_replace($old, $new, $text);
	 }
    return $text;
 }
