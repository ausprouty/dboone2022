<?php
return;
require_once ('../.env.api.remote.mc2.php');
myRequireOnce ('sql.php');
myRequireOnce ('.env.cors.php');

$debug = "In Fix Video<br>\n";
$sql = 'SELECT DISTINCT filename FROM content 
    WHERE language_iso = "eng"
    AND country_code = "M2"
    AND folder_name = "multiply2"
    ORDER BY filename';
 $query  = sqlMany($sql);
 while($data = $query->fetch_array()){
    $debug .= "\n\n" . $data['filename'] . "\n";
    $sql2 = 'SELECT * FROM content 
        WHERE language_iso = "eng"
        AND country_code = "M2"
        AND folder_name = "multiply2"
        AND filename = "'. $data['filename'] .'"
        ORDER BY filename';
    $query2  = sqlMany($sql2);
    while($page = $query2->fetch_array()){
        $text = $page['text'];
        if (strpos($text, '<a href="http') !== FALSE){
            $count = substr_count($text,'<a href="http' );
            $video = array();
            $pos_start = 0;
            for ($i = 0; $i < $count; $i++){
                $pos_start = mb_strpos($text,'<a href="http', $pos_start) + 9;
                $pos_end = mb_strpos($text, '>', $pos_start);
                $length = $pos_end - $pos_start -1;
                $old = mb_substr($text, $pos_start, $length);
                $video[$old] = $old;
            }
            sort($video);
            foreach ($video as $v){
                $debug .= $v . "\n";
            }
            
        }
    }

 }
 _writeThisLog('fixVideo', $debug);
 echo nl2br($debug);
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