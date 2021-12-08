<?php
return;
myRequireOnce ('sql.php');
myRequireOnce ('.env.api.remote.mc2.php');
myRequireOnce ('.env.cors.php');
myRequireOnce ('getLatestContent.php');
myRequireOnce('create.php');

$debug = "in ImportSeriesFromOtherUsers<br>\n";
$sql = 'SELECT DISTINCT filename FROM content 
    WHERE language_iso = "eng"
    AND country_code = "M2"
    AND folder_name = "tc"
    ORDER BY filename';
 $query  = sqlMany($sql);
 while($data = $query->fetch_array()){
     $debug .= $data['filename'] . "<br>\n";
     $p = array(
         'scope'=> 'page',
         'country_code' => 'M2',
         'language_iso' => 'eng',
         'folder_name' => 'tc',
         'filename' => $data['filename']
     );
    $res = getLatestContent($p);
    $new = $res['content'];
    $new['country_code'] = 'A2';
    $new['my_uid'] = 999; // done by computer
    $res = createContent($new);

 }
 writeThisLog('importSeries.txt', $debug);
 echo $debug;
 return;



 function writeThisLog($filename, $content){
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