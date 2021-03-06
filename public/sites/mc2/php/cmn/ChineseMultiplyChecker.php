<?php
require_once ('../.env.api.remote.mc2.php');
myRequireOnce ('sql.php');

myRequireOnce ('.env.cors.php');
myRequireOnce ('bookmark.php');
myRequireOnce ('bibleDbtArray.php');
myRequireOnce ('bibleGetPassage.php');
myRequireOnce ('create.php');

// print titles and see that read-more == read-referencec



$ul_start = FALSE;
$ulul_start = FALSE;
$new = '';


$file = '/api/chinese/M1cmn.txt';
//$file = '/api/chinese/M3cmn.txt';
//$file = '/api/chinese/test.txt';
$filename = '';
$section = 'back';
$count = 0;
$description = null;
$read_more_count = 0;
$read_reference_count = 0;
$video_reference_count = 0;
$video_link_count = 0;
$reference = '';


$text_file = file_get_contents(ROOT_EDIT .  $file);

$lines = explode("\n", $text_file);
$series = 'multiply2';
foreach ($lines as $line){
    if (strpos($line, '<') !== FALSE){
        $instruction = _getInstruction($line);
        $text =  _getText($line);
        //deal with ul
        if ($ulul_start == TRUE){
            if (strpos($instruction, '<lili') === FALSE){
                $new .= '</ul>'. "\n";
                $ulul_start = FALSE;
            }
        }
        if ($ul_start == TRUE){
            if (strpos($instruction, '<li') === FALSE){
                $new .= '</ul>'. "\n";
                $ul_start = FALSE;
            }
        }
        // now the master switch

        switch ($instruction){
            case "<answer>":
                $pre = '';
                $text = $line;
                $end = '';
            break;
            
            case "<bible>":
                $pre = '<p class="forward reference">';
                $text = _superscript($text);
                $end = "</p>\n";
            break;
            
            case "<called-by-Lord>":
                $pre = '<h2 class="up">';
                $end = "</h2>";
            break;
            
            case "<celebration-practise>":
                $pre = '<h2 class="back">';
                $end = "</h2>";
            break;
            
            case "<fathers-heart>":
                $pre = '<h2 class="back">';
                $end = "</h2>";
            break;
            case "<filenumber>":
                //echo nl2br ("$text\n");

            break;
            
            case "<gospel-action>":
                $pre = '<h2 class="forward">';
                $end = "</h2>";
            break;
            
            case "<gospel-prayer>":
                $pre = '<h2 class="forward">';
                $end = "</h2>";
            break;
            
            case "<gospel-preparation>":
                $pre = '<h2 class="forward">';
                $end = "</h2>";
            break;
            
            case "<learn-to-be-like-jesus>":
                $pre = '<h2 class="up">';
                $end = "</h2>";
            break;
            
            case "<li background>":
                if ($ul_start == FALSE) {
                    $pre = _ul_start($section);
                }
                else{
                    $pre = '<li class="'. $section .'">';
                }
                $ul_start = TRUE;
                $end = "</li>";
            break;
            
            case "<li discuss>":
                if ($ul_start == FALSE) {
                    $pre = _ul_start($section);
                }
                else{
                    $pre = '<li class="'. $section .'">';
                }
                $ul_start = TRUE;
                $end = "</li>";
            break;
            
            case "<li read-scripture>":
                if ($ul_start == FALSE) {
                    $pre = _ul_start($section);
                }
                else{
                    $pre =  $pre = '<li class="'. $section .'">';
                }
                $ul_start = TRUE;
                $end = "</li>";
            break;
            
            case "<li retell>":
                if ($ul_start == FALSE) {
                    $pre = _ul_start($section);
                }
                else{
                    $pre =  $pre = '<li class="'. $section .'">';
                }
                $ul_start = TRUE;
                $end = "</li>";
            break;
            
            case "<li>":
                if ($ul_start == FALSE) {
                    $pre = _ul_start($section);
                }
                else{
                    $pre =  $pre = '<li class="'. $section .'">';
                }
                $ul_start = TRUE;
                $end = "</li>";
            break;
            
            case "<lili>":
                if ($ulul_start == FALSE) {
                    $pre = _ul_start($section);
                }
                else{
                    $pre =  $pre = '<li class="'. $section .'">';
                }
                $ulul_start = TRUE;
                $end = "</li>";
            break;
            
            case "<look-back>":
                $pre = '<div class="lesson"><img class="lesson-icon" src="/sites/mc2/content/M2/cmn/images/standard/mc2back.png" />
                <div class="lesson-subtitle"><span class="back">?????????</span></div>
                </div>';
                $text ='';
                $end = "\n";
                $section = 'back';
            break;
            
            case "<look-forward>":
                $pre = '<div class="lesson"><img class="lesson-icon" src="/sites/mc2/content/M2/cmn/images/standard/mc2forward.png" />
                <div class="lesson-subtitle"><span class="forward">?????????</span></div>
                </div>';
                $end = "\n";
                $section = 'forward';
            break;
            
            case "<look-up>":
                $pre = '<div class="lesson"><img class="lesson-icon" src="/sites/mc2/content/M2/cmn/images/standard/mc2up.png" />
                <div class="lesson-subtitle"><span class="up">?????????</span></div>
                </div>';
                $text ='';
                $end = "\n";
                $section = 'up';
            break;
            
            case "<note-area>":
                $pre = '<div class="note-area" id="note#">
                <form id="note#">??????????????????????????????????????????<br />
                <textarea rows="5"></textarea></form>
                </div>';
                $text ='';
                $end = "\n";
            break;
            
            case "<prayer-and-care>":
                $pre = '<h2 class="back">';
                $end = "</h2>";
            break;
            
            case "<purpose>":
                $pre = '<p class="purpose">';
                $end = "</p>";
            break;
            
            case "<read-bible-c>":
                $pre = '<p>';
                $text = _superscript($text);
                $end =  '</p>';
            break;
            
            case "<read-bible>":
                $pre = '<p>';
                $text = _superscript($text);
                $end =  '</p>';
            break;
            
            case "<read-instruction>":
                $pre = '<h2 class="up">';
                $end = "</h2>";
                $video_titles = _videoTitles($text);
                $video_reference_count = _videoReferenceCount($text);
            break;
            case "<read-more>":
                $pre = '<p>';
                $text = _link ($reference);
                $end = '
                    </p>
                <hr /></div>';
                $read_more_count++;
            break;
            
            case "<read-reference>":
                $pre = '<div class="reveal bible">&nbsp;
                <hr />
                <p class="reference">';
                $bible_reference = $text;
                $reference = $text;
               
                $end = '</p>';
                $read_reference_count++;
            break;
            
            case "<recitation-bible>":
                $pre = '<p class="forward bible">';
                $text = _superscript($text);
                $end = '</p>
                <hr /></div>';
            break;
            
            case "<recitation-reference>":
                $pre = '<p class="forward">';
                $end = "</p>";
            break;
            
            case "<recitation>":
                $pre = '<div class="reveal">&nbsp;
                <hr />
                <p class="back">';
                $end = '</p>';
            break;
            
            case "<reference>":
                $pre = '<p class="forward reference">';
                $end = "</p>\n";
            break;
            
            case "<sent-by-Lord>":
            break;
            
            case "<summary>":
                $pre = '<div class="reveal">&nbsp;
                <hr />
                <p class="up">??????</p>
                <p class="up">';
                $text = _summary($text);
                $end = '</p>

                <hr /></div>';
                break;
            
            case "<title>":
                if ($filename){
                    _write($new, $filename);
                    _indexMaker($count, $title, $description, $bible_reference, $filename );
                    if ($read_more_count !=  $read_reference_count){
                        //echo nl2br ("Missmatch of read-reference and read-more  $read_more_count /  $read_reference_count\n");
                    }
                    if  ($video_reference_count !=  $video_link_count){
                        //echo nl2br ("Missmatch of video_reference_count and video_link_count  $video_reference_count /  $video_link_count \n");
                    }
                }
                $filename = _getFilename($text, $series);
                $title =  _title($text);
                $count++;
                $text = null;
                $read_more_count = 0;
                $read_reference_count = 0;
                $video_reference_count = 0;
                $video_link_count = 0;
                $new = '';
                $pre = '';
                $end = '';
            break;
            case "<video-link>":
                $pre = null;
                $text = _videoLink($video_titles);
                $end = null;
                $video_link_count++;
            break;
            
            case "<worship>":
                $pre = '<h2 class="back">';
                $end = "</h2>";
            break;
            
            case "<worship_bible>":
                $pre = '<p class="back bible">';
                $text = _superscript($text);
                $end = '</p>
                <hr /></div>';
            break;
            
            case "<worship_reference>":
                $pre = '<p class="back">';
                $end = "</p>";
            break;
            case "<worship_reveal>":
               $pre = '<div class="reveal">&nbsp;
               <hr />
               <p class="back">';
               $end = '</p>';
            break;
            default:
                $pre = '<p>';
                $end = '</p>';  
        }
        $new .= $pre . $text . $end . "\n";
    }
  
}
_write($new, $filename);
return;


function _getFilename($text, $series){
    $begin = mb_strpos($text, '???');
    $colon = html_entity_decode('&#65306;', ENT_QUOTES, 'utf-8');
    $end = mb_strpos($text,$colon);
    $length = $end - $begin -1;
    $number = mb_substr($text, $begin+1 , $length);
    if ($number < 10){
        $filename = $series . '0' . $number;
    }
    else{
        $filename = $series  . $number;
    }
    return $filename;
}
function _getInstruction($line){
    $end = mb_strpos($line, '>');
    $instruction = trim(mb_substr($line, 0 , $end + 1));
    return $instruction;
}
function _getText($line){
    $end = mb_strpos($line, '>');
    $text = trim( mb_substr($line, $end + 1));
    return $text;
}
// we want to produce number| title | description| bible reference| filename| image|publish=Y) 
function _indexMaker($number, $title, $description, $bible_reference, $filename ){
    echo nl2br ("$number\t $title\t $description\t $bible_reference\t$filename\t\tN\t\n");
}
function _link($reference){
    $pre = '<a class="readmore" href="https://biblegateway.com/passage/?search=';
    $link =  str_replace(' ' , '%20', trim($reference));
    $end = '&amp;version=CUVS" target="_blank">??????????????????</a>';
    return $pre . $link . $end;

}
function _summary($text){
    // get rid of ?????????
    $end = mb_strpos($text, '???');
    $text = trim( mb_substr($text, $end + 1));
    return $text;

}
function _superscript($text){
    for ($i = 0; $i <= 9; $i++){
        $text = str_replace($i, '<sup>' . $i . '</sup>', $text);
    }
    $text = str_replace('</sup><sup>', '', $text);
    return $text;
}
function _title($text){
    $colon = html_entity_decode('&#65306;', ENT_QUOTES, 'utf-8');
    $begin = mb_strpos($text,$colon);
    $title = trim( mb_substr($text, $begin + 1));
    return $title;
}

function _ul_start($class){
    $out = '<ul class="'. $class . '">' . "\n";
    $out .= '<li class="'. $class . '">';
    return $out;
}
function _videoReferenceCount($text){
    if (mb_strpos($text, '???') === FALSE){
        return 0;;
    }
    $count = mb_substr_count($text, '???');
    return $count;

}
function _videoTitles($text){
    if (mb_strpos($text, '???') === FALSE){
        return null;
    }
    $begin = mb_strpos($text, '???');
    $end = mb_strpos($text, '???');
    $length = $end - $begin + 1;
    $title = trim( mb_substr($text, $begin, $length ));
    return $title;
}
function _videoLink($text){
    if (!$text){
        return null;
    }
    $pre = '<div class="reveal video">&nbsp;
    <hr /><a href="https://api.arclight.org/videoPlayerUrl?refId=6_529-GOLukeEnglish6007">';
    $end = '</a>
    <hr /></div>';
    return $pre . $text . $end;

}

function _write($text, $filename){
    return;
}