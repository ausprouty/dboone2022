<?php
echo 'in Import M2';
require_once ('../.env.api.remote.myfriends.php');
echo ROOT_LOG;
myRequireOnce ('sql.php');
myRequireOnce ('.env.cors.php');
myRequireOnce ('getLatestMc2Content.php');
myRequireOnce('create.php');


$fixing = 'multiply2';

$p = array(
    'scope'=> 'series',
    'country_code' => 'M2',
    'language_iso' => 'eng',
    'folder_name' => 'multiply2',
);
$res = getLatestMc2Content($p);
$new = $res['content'];
$new['country_code'] = 'AU';
$new['my_uid'] = 996; // done by computer
createContent($new);

 
 echo ($debug);
 _writeThisLog('ImportM2'. time() , $debug);
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
	$fh = fopen(ROOT_LOG . $filename . '.txt', 'w');
	fwrite($fh, $text);
    fclose($fh);
}
