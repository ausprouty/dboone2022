<?php
return;
require_once ('../.env.api.remote.mc2.php');
myRequireOnce ('sql.php');
myRequireOnce ('.env.cors.php');
myRequireOnce ('getLatestContent.php');
myRequireOnce ('create.php');

$debug = "In Fix Develop1\n";
$sql = 'SELECT DISTINCT filename FROM content 
    WHERE language_iso = "eng"
    AND country_code = "A2"
    AND folder_name = "develop"
    AND filename != "index"
    AND filename != "loc00"
    AND filename NOT LIKE "locp%"
    ORDER BY filename';
 $query  = sqlMany($sql);
 while($data = $query->fetch_array()){
     $debug .= $data['filename'] . "<br>\n";
     $p = array(
         'scope'=> 'page',
         'country_code' => 'A2',
         'language_iso' => 'eng',
         'folder_name' => 'develop',
         'filename' => $data['filename']
     );
    $res = getLatestContent($p);
    $new = $res['content'];
    $new['text'] = _fix($new['text']);
    
    $new['my_uid'] = 999; // done by computer
    createContent($new);

 }
 _writeThisLog('fixDevelop', $debug);
 echo $debug;
 return;

 function  _fix($text){
    $bad= 'Reinforce the overall vision—a church or faith community for every 1,000 people.';
    $good = 'Our vision — a church or faith community for every 1,000 people.';
    $text = str_replace($bad, $good, $text);

    $bad= 'Have each person set a goal of one thing they will work on in their walk with God.';
    $good = 'Set a goal of one thing you will work on in your walk with God.';
    $text = str_replace($bad, $good, $text);

    $bad = 'Have everyone set goals for improvement from their evaluation and share their goals with the small group. Then have them pray for one another.';
    $good = 'Set goals for improvement from your evaluation and share your goals with the small group. Then pray for one another.';
    $text = str_replace($bad, $good, $text);

    
    $bad= 'Have everyone set goals for improvement from their evaluation and share their goals with the small group. Then have them pray for one another.';
    $good = 'Set goals for improvement from your evaluation and share your goals with the small group. Then pray for one another.';
    $text = str_replace($bad, $good, $text);

    $bad= 'Each person read their assigned verses and then tell the group:';
    $good = 'Read your assigned verses and then tell the group:';
    $text = str_replace($bad, $good, $text);


    $bad = '<ul>
	<li>Celebrate Faithfulness in the large group so that all can benefit and be encouraged. Give opportunity for participants to share what has happened since the last meeting. Ask them to relate it to goals they set from the last meeting.</li>
</ul>';
    $good ='<div class="trainer">
<hr />Celebrate Faithfulness in the large group so that all can benefit and be encouraged. Give opportunity for participants to share what has happened since the last meeting. Ask them to relate it to goals they set from the last meeting.
<hr /></div>';
$text = str_replace($bad, $good, $text);

$bad = '<div class="reveal">';
$good = '<div class="reveal">&nbsp;';
$text = str_replace($bad, $good, $text);

$bad = '<div class="reveal bible">';
$good = '<div class="reveal bible">&nbsp;';
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
