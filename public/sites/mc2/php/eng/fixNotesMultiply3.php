<?php
return;
require_once ('../.env.api.remote.mc2.php');
myRequireOnce ('sql.php');
myRequireOnce ('.env.cors.php');
myRequireOnce ('getLatestContent.php');
myRequireOnce ('create.php');

$debug = "In Fix Notes<br>\n";
$sql = 'SELECT DISTINCT filename FROM content 
    WHERE language_iso = "eng"
    AND country_code = "M2"
    AND folder_name = "multiply3"
    AND filename != "index"
    AND filename != "actsEnd"
    AND filename NOT LIKE "actsp%"
    ORDER BY filename';
 $query  = sqlMany($sql);
 while($data = $query->fetch_array()){
     $debug .= $data['filename'] . "<br>\n";
     $p = array(
         'scope'=> 'page',
         'country_code' => 'M2',
         'language_iso' => 'eng',
         'folder_name' => 'multiply3',
         'filename' => $data['filename']
     );
    $res = getLatestContent($p);
    $new = $res['content'];
    $text = replaceNote($new['text']);
    $new['text'] = insertNote($text);
    $new['my_uid'] = 999; // done by computer
    createContent($new);

 }
 writeThisLog('fixNotes', $debug);
 echo $debug;
 return;


function replaceNote($text){
    $new_template = '
<div class="note-area" id="note#" >
    <form ><p>Notes: (click outside box to save)</p>
    <textarea rows="5"></textarea>
    </form>
';
    $pos_start = mb_strpos($text,'<div class="note-area"');
    $pos_end = mb_strpos($text, '</div>', $pos_start);
    $length = $pos_end - $pos_start;
     // from https://stackoverflow.com/questions/1252693/using-str-replace-so-that-it-only-acts-on-the-first-match
    $text = substr_replace($text, $new_template, $pos_start, $length);
    return $text;
}
function insertNote($text){
    $new_template = '
    <div class="note-area" id="note#" >
        <form ><p>Notes: (click outside box to save)</p>
        <textarea rows="5"></textarea>
        </form>
    </div>
    <h2 class="forward">Praying For the Mission
    ';
    $pos_start = mb_strpos($text,'<h2 class="forward">Praying');
    $pos_end = mb_strpos($text, '</h2>', $pos_start);
    $length = $pos_end - $pos_start;
    // from https://stackoverflow.com/questions/1252693/using-str-replace-so-that-it-only-acts-on-the-first-match
    $text = substr_replace($text, $new_template, $pos_start, $length);
    return $text;
}



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