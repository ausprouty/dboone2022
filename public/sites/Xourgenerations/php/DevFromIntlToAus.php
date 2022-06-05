<?php
echo 'in Dev';
require_once ('../.env.api.remote.4G.php');
echo ROOT_LOG;
myRequireOnce ('sql.php');
myRequireOnce ('.env.cors.php');
myRequireOnce ('getLatestContent.php');
myRequireOnce ('create.php');


$fixing = 'develop';

$sql = 'SELECT DISTINCT filename FROM content 
    WHERE language_iso = "eng"
    AND country_code = "A2"
    AND folder_name = "develop"
    AND filename != "index"
    ORDER BY filename';
 $query  = sqlMany($sql);
 while($data = $query->fetch_array()){
     $debug .= $data['filename'] . "<br>\n";
     $p = array(
         'scope'=> 'page',
         'country_code' => 'A2',
         'language_iso' => 'eng',
         'folder_name' => 'develop',
         'filename' => $data['filename']
     );
    $res = getLatestContent($p);
    $new = $res['content'];
    $new['folder_name'] = 'dev';
    $new['my_uid'] = 996; // done by computer
    createContent($new);

 }
 echo ($debug);
 _writeThisLog('Dev-'. time() , $debug);
 return;

 function  _fix($text){
   // see https://github.com/peterolson/hanzi-tools (for javascript)
   // see https://github.com/rny/ZhConverter (for php)
    $text = ZhConverter::zh2hant($text);
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
