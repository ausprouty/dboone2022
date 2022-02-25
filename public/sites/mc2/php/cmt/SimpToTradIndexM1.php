<?php
echo 'in Trad Index';
require_once ('../../.env.api.remote.mc2.php');
echo ROOT_LOG;
myRequireOnce ('sql.php');
myRequireOnce ('.env.cors.php');
myRequireOnce ('getLatestContent.php');
myRequireOnce ('create.php');
require_once  '../../vendor/autoload.php';
use Rny\ZhConverter\ZhConverter;
echo 'after use';

$fixing = 'multiply1';

$p = array(
    'scope'=> 'series',
    'country_code' => 'M2',
    'language_iso' => 'cmn',
    'folder_name' => 'multiply1',
);
$res = getLatestContent($p);
$new = $res['content'];
$text =  $new['text'];
$traditional = _fix($text);
$new['text'] = $traditional;
$new['language_iso'] = 'cmt';
$new['my_uid'] = 996; // done by computer
createContent($new);

 
 echo ($debug);
 _writeThisLog('SimptoTrad-'. time() , $debug);
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
