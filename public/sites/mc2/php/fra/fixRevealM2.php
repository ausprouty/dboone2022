<?php
return;
require_once ('../.env.api.remote.mc2.php');
myRequireOnceSetup(11);
myRequireOnce ('sql.php');
myRequireOnce ('.env.cors.php');
myRequireOnce ('getLatestContent.php');
myRequireOnce ('create.php');

$echo = "In Fix Develop1\n";
$sql = 'SELECT DISTINCT filename FROM content 
    WHERE language_iso = "fra"
    AND country_code = "M2"
    AND folder_name = "multiply2"
    AND filename != "index"
    ORDER BY filename';
 $query  = sqlMany($sql);
 while($data = $query->fetch_array()){
     $debug .= $data['filename'] . "<br>\n";
     echo nl2br($data['filename'] . "<br>\n");
     $p = array(
         'scope'=> 'page',
         'country_code' => 'M2',
         'language_iso' => 'fra',
         'folder_name' => 'multiply2',
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
         '<p>Louange</p>',
         '<p>Projection de Vision</p>',
         '<p>Contexte</p>',
         '<p>R&eacute;sum&eacute;</p>',
         '<p>Se Pr&eacute;parer pour la Mission</p>'
    ];
    $good= [
        '<h2>Louange</h2>',
         '<h2>Projection de Vision</h2>',
         '<h2>Contexte</h2>',
         '<h2>R&eacute;sum&eacute;</h2>',
         '<h2>Se Pr&eacute;parer pour la Mission</h2>'

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
