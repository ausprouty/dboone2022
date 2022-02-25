<?php
return;
echo 'in TOT2<br>';
require_once ('/home/globa544/edit.mc2.online/api/.env.api.remote.mc2.php');
echo ROOT_LOG;
myRequireOnceSetup(11);
myRequireOnce ('sql.php');
myRequireOnce ('.env.cors.php');
myRequireOnce ('getLatestContent.php');
myRequireOnce ('create.php');
echo '<br>after use';

$fixing = 'tot2';


$sql = 'SELECT DISTINCT filename FROM content 
    WHERE language_iso = "eng"
    AND country_code = "M2"
    AND folder_name = "tot2"
    AND filename != "index"
    ORDER BY filename';
 $query  = sqlMany($sql);
 while($data = $query->fetch_array()){
     $debug .= $data['filename'] . "<br>\n";
     $p = array(
         'scope'=> 'page',
         'country_code' => 'M2',
         'language_iso' => 'eng',
         'folder_name' => 'tot2',
         'filename' => $data['filename']
     );
    $res = getLatestContent($p);
    $new = $res['content'];
    
    $new['language_iso'] = 'fra';
    $new['my_uid'] = 995; // done by computer
    createContent($new);

 }
 echo ($debug);
 _writeThisLog('TOT2-'. time() , $debug);
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
