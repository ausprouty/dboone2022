<?php

require_once ('../../.env.api.remote.mc2.php');
myRequireOnce ('sql.php');
myRequireOnce ('.env.cors.php');
myRequireOnce ('getLatestContent.php');
myRequireOnce ('create.php');
myRequireOnce ('videoLinksUpdate.php');

$debug = "In Fix Video Links<br>\n";
$sql = 'SELECT DISTINCT filename FROM content 
    WHERE language_iso = "eng"
    AND country_code = "M2"
    AND folder_name = "multiply1"
    AND filename != "index"
    ORDER BY filename';
 $query  = sqlMany($sql);
 while($data = $query->fetch_array()){
     $debug .= $data['filename'] . "<br>\n";
     $p = array(
         'scope'=> 'page',
         'country_code' => 'M2',
         'language_iso' => 'eng',
         'folder_name' => 'multiply1',
         'filename' => $data['filename']
     );
    $res = getLatestContent($p);
    $new = $res['content'];
    $text = $new['text'];
    if (strpos($text, '<div class="reveal video') !== FALSE){
        $find = '<div class="reveal video';
        $response=  videoLinksFix($text, $find);
        $new['text'] = $response['content'];
        if ($new['debug']){
            $debug .= $new['debug'];
        }
        $new['my_uid'] = 999; // done by computer
        createContent($new);
        echo nl2br($data['filename'] . "\n");
    }

 }
 writeThisLog('fixVideoMultiply3', $debug);
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