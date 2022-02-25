<?php
return;
require_once ('../.env.api.remote.mc2.php');
myRequireOnce ('sql.php');
myRequireOnce ('.env.cors.php');
myRequireOnce ('getLatestContent.php');
myRequireOnce ('create.php');

echo "In Fix Multiply Praise<br>\n";
$sql = 'SELECT DISTINCT filename FROM content 
    WHERE language_iso = "eng"
    AND country_code = "M2"
    AND folder_name = "multiply2"
    AND filename LIKE "multiply25%"
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
    if (strpos($new['text'], '[') !== FALSE){
        $response = _video($new['text']);
        $new['text'] = $response['content'];
        if (isset( $response['debug'])){
            $out['debug'] .= $response['debug'];
        }
    }
    $new['my_uid'] = 999; // done by computer
    createContent($new);
 }
 _writeThisLog('fixPraise', $debug);
 echo $debug;
 return;

 function  _fix($text){
    $bad= '<div class="reveal">';
    $good = '<div class="reveal">&nbsp;';
    $text = str_replace($bad, $good, $text);

    $text = _removeImages($text);
    $text = _removefirstNote($text);
    $text = _changeQuestions($text);
    $text = _removeActionButton($text);


    $bad = '<p class="back">Our vision is:<em> &ldquo;A church for every village and community, and the gospel for every person.&rdquo;</em></p>';
    $good = '';
    $text = str_replace($bad, $good, $text);

    $bad = urldecode('%3Cli%20class%3D%22back%22%3Ereminding%20them%20of%20what%20God%20wants%20to%20do%20through%20them%3C%2Fli%3E%0A%3C%2Ful%3E');
    $good = '<li class="back">reminding them of what God wants to do through them</li>
    </ul>
    <p class="back">Our vision is: <em> &ldquo;A church for every village and community, and the gospel for every person.&rdquo;</em></p>';
    $text = str_replace($bad, $good, $text);

    $bad = 'Ask each person to tell one highlight and explain one challenge they experienced this week.';
    $good = 'Ask, “What do you want Jesus to do for you this week?” Pray for each other’s needs.';
    $text = str_replace($bad, $good, $text);

    $bad = 'Each person to tell one highlight and explain one challenge they experienced this week.';
    $good = 'Each person answers  “What do you want Jesus to do for you this week?” Pray for each other’s needs.';
    $text = str_replace($bad, $good, $text);

    $bad = 'Ask, “What happened as you trusted God with your goals and I will statements?”';
    $good = 'Each person answers “What happened as you trusted God with your goals and I will statements?”';
    $text = str_replace($bad, $good, $text);

    $bad = '<p>Discovery Discussion (Everyone answers)</p>';
    $good = '<h2>Discovery Discussion (Everyone answers)</h2>';
    $text = str_replace($bad, $good, $text);

    return $text;
 }

 function _removeActionButton($text){
     echo ' _removeActionButton';
     $bad = '<p><button class="action">Send Notes and Action Points</button></p>';
    $good = '';
    $text = str_replace($bad, $good, $text);
    $bad = '<div><button class="action">Send Notes and Action Points</button></div>';
    $text = str_replace($bad, $good, $text);
    return $text;
}

 function _removePhase($text){
    echo ' _removePhase';
     $good = '';
     $phase[]= urldecode('%3Col%3E%0A%09%3Cli%20class%3D%22up%22%3E%3Cstrong%3EPreparation%3C%2Fstrong%3E%3C%2Fli%3E%0A%09%3Cli%20class%3D%22up%22%3EMinistry%20Foundations%3C%2Fli%3E%0A%09%3Cli%20class%3D%22up%22%3EMinistry%20Training%3C%2Fli%3E%0A%09%3Cli%20class%3D%22up%22%3EExpanded%20Outreach%3C%2Fli%3E%0A%09%3Cli%20class%3D%22up%22%3ELeadership%20Multiplication%3C%2Fli%3E%0A%3C%2Fol%3E');
     $phase[] = urldecode('%3Col%3E%0A%09%3Cli%20class%3D%22up%22%3EPreparation%3C%2Fli%3E%0A%09%3Cli%20class%3D%22up%22%3E%3Cstrong%3EMinistry%20Foundations%3C%2Fstrong%3E%3C%2Fli%3E%0A%09%3Cli%20class%3D%22up%22%3EMinistry%20Training%3C%2Fli%3E%0A%09%3Cli%20class%3D%22up%22%3EExpanded%20Outreach%3C%2Fli%3E%0A%09%3Cli%20class%3D%22up%22%3ELeadership%20Multiplication%3C%2Fli%3E%0A%3C%2Fol%3E');
     $text = str_replace($phase, $good, $text);
	 $phase[] = '<p class="up">The life of Christ is broken up into 5 periods:</p>

<ol>
	<li class="up">Preparation</li>
	<li class="up">Ministry Foundations</li>
	<li class="up">Ministry Training</li>
	<li class="up">Expanded Outreach</li>
	<li class="up"><strong>Leadership Multiplication</strong></li>
</ol>';
 $text = str_replace($phase, $good, $text);
     return $text;
 }

 function _changeQuestions($text){
    echo '_changeQuestions';
    $good  = '<ul class="up">
     <li>What caught your attention or what did you like best? Why?</li>
     <li>What is new or has developed at this point in Jesus story?</li>
     <li>What do we learn about the humanity or divinity of Jesus from this passage?</li>
     <li>How can we live differently now that we know this story?</li>
 </ul>
 <p>Additional Questions: </p>
 <ul class="up">
     <li>What are his followers learning?</li>
     <li>What is Jesus modeling to us about life and ministry or godly leadership?</li>
 </ul>';
    $question = array();
     $question[] = urldecode('%3Cul%20class%3D%22up%22%3E%0A%09%3Cli%3EWhat%20caught%20your%20attention%20or%20what%20did%20you%20like%20best%3F%20Why%3F%3C%2Fli%3E%0A%09%3Cli%3EWhat%20is%20new%20or%20has%20developed%20at%20this%20point%20in%20Jesus%20story%3F%3C%2Fli%3E%0A%09%3Cli%3EWhat%20do%20we%20learn%20about%20the%20humanity%20or%20divinity%20of%20Jesus%20from%20this%20passage%3F%3C%2Fli%3E%0A%09%3Cli%3EWhat%20are%20his%20followers%20learning%3F%3C%2Fli%3E%0A%09%3Cli%3EWhat%20is%20Jesus%20modeling%20to%20us%20about%20life%20and%20ministry%20or%20godly%20leadership%3F%3C%2Fli%3E%0A%09%3Cli%3EHow%20can%20we%20live%20differently%20now%20that%20we%20know%20this%20story%3F%3C%2Fli%3E%0A%3C%2Ful%3E');

     $question[] = urldecode('%3Cul%20class%3D%22up%22%3E%0A%09%3Cli%3EWhat%20caught%20your%20attention%20or%20what%20did%20you%20like%20best%3F%20Why%3F%3C%2Fli%3E%0A%09%3Cli%3EWhat%20is%20new%20or%20has%20developed%20at%20this%20point%20in%20Jesus%20story%3F%3C%2Fli%3E%0A%09%3Cli%3EWhat%20do%20we%20learn%20about%20the%20humanity%20or%20divinity%20of%20Jesus%20from%20this%20passage%3F%3C%2Fli%3E%0A%09%3Cli%3EWhat%20are%20his%20followers%20learning%3F%3C%2Fli%3E%0A%09%3Cli%3EWhat%20is%20Jesus%20modeling%20to%20us%20about%20life%20and%20ministry%20or%20godly%20leadership%3F%3C%2Fli%3E%0A%09%3Cli%3EHow%20can%20we%20live%20differently%20now%20that%20we%20know%20this%20story%3F%3C%2Fli%3E%0A%3C%2Ful%3E');
	 $question [] = '<ul class="up">
	<li>What caught your attention or what did you like best? Why?</li>
	<li>What is new or has developed at this point in Jesus story?</li>
	<li>What do we learn about the humanity or divinity of Jesus from this passage?</li>
	<li>What are his followers learning?</li>
	<li>What is Jesus modeling to us about life and ministry or godly leadership?</li>
	<li>How can we live differently now that we know this story?</li>
</ul>';
     $text = str_replace($question, $good, $text);

 return $text;
 }

 function _removeFirstNote($text){
    $code = '%3Cdiv%20class%3D%22note-area%22%20id%3D%22note%23%22%3E%0A%20%20%20%20%3Cform%20id%3D%22note%23%22%3ENotes%3A%20%28click%20outside%20box%20to%20save%29%3Cbr%20%2F%3E%0A%20%20%20%20%3Ctextarea%20rows%3D%225%22%3E%3C%2Ftextarea%3E%3C%2Fform%3E%0A%20%20%20%20%3C%2Fdiv%3E';
    $bad = urldecode($code);
    echo($bad);
    $text = str_replace($bad, $good, $text);
    return $text;
 }

function  _removeImages($text){
    echo '_removeImages';
    $bad = '<h2><img alt="Stage of Ministry" class="lesson_image" src="/content/A2/eng/loc/LoC1.png" /></h2>';
    $good = '';
    $text = str_replace($bad, $good, $text);

    $bad = '<h2><img alt="Stage of Ministry" class="lesson_image" src="/content/A2/eng/loc/LoC2.png" /></h2>';
    $good = '';
    $text = str_replace($bad, $good, $text);

    $bad = '<h2><img alt="Stage of Ministry" class="lesson_image" src="/content/A2/eng/loc/LoC3.png" /></h2>';
    $good = '';
    $text = str_replace($bad, $good, $text);

    $bad = '<h2><img alt="Stage of Ministry" class="lesson_image" src="/content/A2/eng/loc/LoC4.png" /></h2>';
    $good = '';
    $text = str_replace($bad, $good, $text);

    $bad = '<h2><img alt="Stage of Ministry" class="lesson_image" src="/content/A2/eng/loc/LoC5.png" /></h2>';
    $good = '';
    $text = str_replace($bad, $good, $text);
    return $text;
 }
// looking for : <p><a href="https://api.arclight.org/videoPlayerUrl?refId=6_529-GOJohnEnglish4701">[John 1:1-18]</a></p>
 function _video($text){
    $template = '<div class="reveal video">&nbsp;<hr /><a href="[link]">[title]</a>';
    $lines = explode("\n", $text);
    $search = '[';
    foreach ($lines as $line){
        if (strpos($line, $search) !== FALSE){
            $pos_start = mb_strpos($line, $search);
            $link_start = $pos_start + 1 ;
            $pos_end = mb_strpos($line, ']', $link_start );
            $length = $pos_end - $link_start;
            $title = mb_substr($line, $link_start, $length);
            echo $title;

            $pos_start = mb_strpos($line, '"');
            $link_start = $pos_start + 1 ;
            $pos_end = mb_strpos($line, '"', $link_start );
            $length = $pos_end - $link_start;
            $link = mb_substr($line, $link_start, $length);
            echo $link;
            $line = '<div class="reveal video">&nbsp;<hr /><a href="'. $link .'">' . $title . '</a><hr/></div>'; 
    }
    $new_text .= $line . "\n";
}
$out = $new_text;
return $out;

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
