<?php

myRequireOnce ('create.php');
myRequireOnce('getLatestContent.php');
myRequireOnce('bibleDbtArray.php');
myRequireOnce('bibleGetPassage.php');
/* This routine changes bible-link into bible-popup

   Input is : <span class="bible-link">Matthew 5:14</span>
   Output is: <a href="javascript:popUp('pop#')">Matthew 5:14</a>
			<div class="popup invisible" id="pop#">You are the light of the world. A town built on a hill cannot be hidden.</div>
*/

function biblePopupMaker($p){
    $out = [];
    $out['debug'] = '';
    $out['debug'] .= 'in biblePopupMaker' . "\n";
    if (!isset($p['recnum'])){
        $out['debug'] .= 'p[recnum] is not set' . "\n\n\n";
        return $out;
    }
    $template = '<a href="javascript:popUp(\'pop[id]\')">[reference]</a>
    <div class="popup" id="pop[id]">[text]</div>';

    // get record
    $sql = 'SELECT * FROM content WHERE  recnum =' . $p['recnum'];
    $out['debug'] .= $sql . "\n";
    $p = sqlArray($sql);
    $text = $p['text'];
    $highest_existing = biblePopupFindExisting ($text);
    //$out['debug'] .= $text;
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
            $out['debug'] .= $reference . "\n";
            $out['debug'] .= $span . "\n";
            // create dbtArray
            $res = createBibleDbtArrayFromPassage($p);
            $dbtArray = $res['content'];
            $bible_text = '';
            // find text
            foreach ($dbtArray as $dbt){
                $dbt['version_ot'] = 1257; // NIV English
                $dbt['version_nt'] = 1257;
                $bible = bibleGetPassage($dbt);
                $bible_text .= $bible['content']['text'];
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
           // $text = str_replace($span, $popup, $text, &$count = 1); // you only want to replace things once
        }
    }
   // writeLog ('popup74-'. $i . '-'. time(), $text);
    $p['text'] = $text;
    createContent($p);
    $p['scope'] = 'page';
    unset($p['recnum']);
    $res = getLatestContent($p);
    if ($res['debug']){
        $out['debug'] .= $res['debug'];
    }
    $out['content'] = $res['content'];
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