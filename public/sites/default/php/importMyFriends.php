<?php
return;

myRequireOnce ('.env.api.remote.mc2.php');
myRequireOnce ('.env.cors.php');
myRequireOnce ('create.php');
myRequireOnce ('getLatestContent.php');
myRequireOnce ('sql.php');

$debug = "in ImportSeriesFromOtherUsers<br>\n";
$sql = 'SELECT DISTINCT filename FROM myfriends
    WHERE language_iso = "eng"
    AND country_code = "AU"
    AND folder_name = "multiply"
    ORDER BY filename';
 $query  = sqlMany($sql);
 while($data = $query->fetch_array()){
     $debug .= $data['filename'] . "<br>\n";
     $p = array(
         'scope'=> 'page',
         'country_code' => 'AU',
         'language_iso' => 'eng',
         'folder_name' => 'multiply',
         'filename' => $data['filename']
     );
     $sql = "SELECT * from myfriends
        WHERE country_code = '". $p['country_code'] . "'
        AND language_iso = '" . $p['language_iso'] . "'
        AND folder_name = '" . $p['folder_name'] . "'
        AND  filename = '" . $p['filename'] . "'
        ORDER BY recnum DESC LIMIT 1";
    $new = sqlArray($sql);
    $new['country_code'] = 'A2';
    $new['folder_name'] = 'multiply1';
    $new['my_uid'] = 999; // done by computer
    $res = createContent($new);

 }
 writeThisLog('importMyFriends', $debug);
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