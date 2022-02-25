<?php
return;
require_once ('../.env.api.remote.mc2.php');
myRequireOnceSetup(11);
myRequireOnce ('sql.php');
myRequireOnce ('.env.cors.php');
myRequireOnce ('getLatestContent.php');
myRequireOnce ('create.php');

$debug = "In Fix Develop1\n";
$sql = 'SELECT DISTINCT filename FROM content 
    WHERE language_iso = "eng"
    AND country_code = "M2"
    AND folder_name = "multiply3"
    AND filename != "index"
    ORDER BY filename';
 $query  = sqlMany($sql);
 while($data = $query->fetch_array()){
     $debug .= $data['filename'] . "<br>\n";
     echo nl2br($data['filename'] . "<br>\n");
     $p = array(
         'scope'=> 'page',
         'country_code' => 'M2',
         'language_iso' => 'eng',
         'folder_name' => 'multiply3',
         'filename' => $data['filename']
     );
    $res = getLatestContent($p);
    $new = $res['content'];
    $new['text'] = _fix($new['text']);
    
    $new['my_uid'] = 997; // done by computer
    createContent($new);

 }
 _writeThisLog('fixReveal', $debug);
 echo $debug;
 return;

 function  _fix($text){
    $bad= [
         '<p class="back">Praise</p>',
         '<p class="back">Motivation and Encouragement</p>',
         '<p class="up">Context</p>',
         '<p class="up">Summary</p>',
         '<p class="forward">Preparing for Mission</p>'
    ];
    $good= [
        '<h2 class="back">Praise</h2>',
        '<h2 class="back">Motivation and Encouragement</h2>',
        '<h2 class="up">Context</h2>',
        '<h2 class="up">Summary</h2>',
        '<h2 class="forward">Preparing for Mission</h2>'

    ];
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
