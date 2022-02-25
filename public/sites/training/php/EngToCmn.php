<?php
echo 'in EngToCmn';
require_once ('../.env.api.remote.train.php');
echo ROOT_LOG;
myRequireOnce ('sql.php');
myRequireOnce ('.env.cors.php');
myRequireOnce ('getLatestContent.php');
myRequireOnce ('create.php');


$debug = "Import Train<br>\n";
$sql = 'SELECT DISTINCT filename FROM content 
    WHERE language_iso = "eng"
    AND country_code = "A1"
    AND folder_name = "train"
    ORDER BY filename';
 $query  = sqlMany($sql);
 while($data = $query->fetch_array()){
     $debug .= $data['filename'] . "<br>\n";
     $p = array(
         'scope'=> 'page',
         'country_code' => 'A1',
         'language_iso' => 'eng',
         'folder_name' => 'train',
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
