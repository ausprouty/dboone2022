<?php
return;
echo 'in  Index';
require_once ('../.env.api.remote.mc2.php');
myRequireOnceSetup(11);
myRequireOnce ('sql.php');
myRequireOnce ('.env.cors.php');
myRequireOnce ('getLatestContent.php');
myRequireOnce ('create.php');

echo 'after use';

$fixing = 'tot2';

$p = array(
    'scope'=> 'series',
    'country_code' => 'M2',
    'language_iso' => 'eng',
    'folder_name' => 'tot2',
);
$res = getLatestContent($p);
$new = $res['content'];

$new['language_iso'] = 'fra';
$new['my_uid'] = 996; // done by computer
createContent($new);

 
 echo ($debug);
 _writeThisLog('TOT2FrenchCreator'. time() , $debug);
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
