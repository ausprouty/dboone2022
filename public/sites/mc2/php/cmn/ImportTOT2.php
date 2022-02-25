<?php
echo 'in TOT';
require_once ('../../.env.api.remote.mc2.php');
echo ROOT_LOG;
myRequireOnce ('sql.php');
myRequireOnce ('.env.cors.php');
myRequireOnce ('getLatestContent.php');
myRequireOnce ('create.php');


$debug = "Import TOT2<br>\n";
$sql = 'SELECT DISTINCT filename FROM content 
    WHERE language_iso = "eng"
    AND country_code = "M2"
    AND folder_name = "tot2"
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
    $new['language_iso'] = 'cmn';
    $new['my_uid'] = 996; // done by computer
    createContent($new);

 }
 echo ($debug);
 _writeTOTLog('Import TOT2-'. time() , $debug);
 return;

 
 function _writeTOTLog($filename, $content){
	if (!is_array($content)){
		$text = $content;
	}
	else{
		$text = '';
		foreach ($content as $key=> $value){
			$text .= $key . ' => '. $value . "\n";
		}
	}
	$fh = fopen($filename . '.txt', 'w');
	fwrite($fh, $text);
    fclose($fh);
}
