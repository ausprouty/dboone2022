<?php

myRequireOnce ('create.php');
myRequireOnce ('bibleDbtArray.php');
myRequireOnce ('bibleGetPassage.php');
myRequireOnce ('getLatestContent.php');
myRequireOnce ('writeLog.php');
/* This routine changes bible-link into bible-popup

   Input is : <span class="bible-link">Matthew 5:14</span>
   Output is: <a href="javascript:popUp('pop#')">Matthew 5:14</a>
			<div class="popup invisible" id="pop#">You are the light of the world. A town built on a hill cannot be hidden.</div>
*/

function biblePopupMaker($p){
    $out='';
    $debug = '';
    if (!isset($p['text'])){
        writeLogError('biblePopupMaker-20', 'No Text');
        trigger_error("p[text] is not set in biblePopupMaker", E_USER_ERROR);
        return FALSE;
    }
    if (!isset($p['bookmark'])){
        writeLogError('biblePopupMaker-26', 'No Bookmark');
        trigger_error("p[bookmark] is not set in biblePopupMaker", E_USER_ERROR);
        return FALSE;
    }
    $bookmark = json_decode ($p['bookmark']);
    $ot = $bookmark->language->bible_ot;
    $nt = $bookmark->language->bible_nt;
    $debug .= "nt and ot are $nt and $ot\n";
    //writeLog('biblePopupMaker-33', $debug);
    $template = '<a href="javascript:popUp(\'pop[id]\')">[reference]</a>
    <div class="popup" id="pop[id]">[text]</div>';
    $text = $p['text'];
    $highest_existing = biblePopupFindExisting ($text);
    $count = substr_count($text, '"bible-link"');
    $pos_end = 0;
    for ($i = 1; $i <= $count; $i++){
        $pos_start = $pos_end;
        // sometimes a verse may appear more than once on a page.
        if (strpos ($text, '<span class="bible-link">', $pos_start) !== FALSE){
            $pos_start = strpos ($text, '<span class="bible-link">', $pos_start);
            $word_start = strpos ($text, '>', $pos_start) + 1;
            $pos_end = strpos($text, '</span>', $pos_start);
            $word_length = $pos_end - $word_start;
            $reference = substr($text, $word_start, $word_length);  //>Matthew 5:14
            $span_length = $pos_end - $pos_start + 7;
            $span = substr($text, $pos_start, $span_length); //<span class="bible-link">Matthew 5:14</span>
            $p['entry'] = $reference;
            $debug .= $reference . "\n";
            $debug .= $span . "\n";
            // create dbtArray
            writeLogError('biblePopupMaker-54-p-'. $count, $p);
            $res = createBibleDbtArrayFromPassage($p);
            writeLogError('biblePopupMaker-56-res'.$count , $res);
            if (isset($res)){
                $dbtArray = $res;
                $bible_text = '';
                // find text
                foreach ($dbtArray as $dbt){
                    $dbt['version_ot'] = $ot;
                    $dbt['version_nt'] = $nt;
                    $bible = bibleGetPassage($dbt);
                    writeLogError('biblePopupMaker-65-bible'.$count , $bible);
                    $bible_text .= $bible['text'];
                }
                // remove any headers
                if (strpos ($bible_text, '<h3>') !== FALSE){
                    $bible_text = _removeH3($bible_text);
                }
                $id = $i + $highest_existing;
                $old = array(
                    '[id]',
                    '[reference]',
                    '[text]',
                );
                $new = array(
                    $id,
                    $reference,
                    $bible_text,
                );
                $popup = str_replace($old, $new, $template);
                // replace only first occurance: https://stackoverflow.com/questions/1252693/using-str-replace-so-that-it-only-acts-on-the-first-match
                $pos = strpos($text,$span);
                if ($pos !== false) {
                    $text = substr_replace($text,$popup,$pos,strlen($span));
                }
            }
           // $text = str_replace($span, $popup, $text, &$count = 1); // you only want to replace things once
        }
    }
   // writeLog ('popup74-'. $i . '-'. time(), $text);
    $p['text'] = $text;
    createContent($p);
    $p['scope'] = 'page';
    unset($p['recnum']);
    $out = getLatestContent($p);
    return $out;
}
function _removeH3($text) {
    $count = substr_count($text, '<h3>');
    $pos_end = 0;
    for ($i = 1; $i <= $count; $i++){
        $pos_start = $pos_end;
        $pos_start = strpos ($text, '<h3>', $pos_start);
        $pos_end = strpos($text, '</h3>', $pos_start);
        $h3_length = $pos_end - $pos_start + 5;
        $h3 = substr($text, $pos_start, $h3_length );
        $new_text = str_ireplace($h3, '', $text);
    }
    return  $new_text;
}
function  biblePopupFindExisting ($text){
    $count = substr_count($text, 'javascript:popUp');
    $pos_start = 0;
    $largest= 0;
    for ($i = 1; $i <= $count; $i++){
        $pos_start = strpos($text, 'javascript:popUp', $pos_start) + 21;
        $pos_end = strpos ($text, ')', $pos_start) -1;
        $length = $pos_end - $pos_start;
        $id = substr($text, $pos_start, $length);
        if ($id > $largest){
            $largest = $id;
        }
    }
    return $largest;
}