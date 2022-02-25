<?php
return;
require_once ('../.env.api.remote.mc2.php');
myRequireOnce ('sql.php');
myRequireOnce ('.env.cors.php');
myRequireOnce ('getLatestContent.php');
myRequireOnce ('create.php');

$debug = "In Fix Multiply Praise<br>\n";
$sql = 'SELECT DISTINCT filename FROM content 
    WHERE language_iso = "eng"
    AND country_code = "M2"
    AND folder_name = "multiply2"
    AND filename != "index"
    AND filename != "loc00"
    AND filename NOT LIKE "locp%"
    ORDER BY filename';
 $query  = sqlMany($sql);
 while($data = $query->fetch_array()){
     $debug .= $data['filename'] . "<br>\n";
     $p = array(
         'scope'=> 'page',
         'country_code' => 'M2',
         'language_iso' => 'eng',
         'folder_name' => 'multiply2',
         'filename' => $data['filename']
     );
    $res = getLatestContent($p);
    $new = $res['content'];
    $new['text'] = _fix($new['text']);
    
    $new['my_uid'] = 999; // done by computer
    createContent($new);

 }
 _writeThisLog('fixPraise', $debug);
 echo $debug;
 return;

 function  _fix($text){
    $bad= '<p class="up">Discovery Discussion (Everyone answers)</p>';
    $good = '<h2 class="up">Discovery Discussion (Everyone answers)</h2>';
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
