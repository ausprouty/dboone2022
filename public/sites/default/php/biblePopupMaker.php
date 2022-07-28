<?php

myRequireOnce ('create.php');
myRequireOnce ('bibleDbtArray.php');
myRequireOnce ('bibleGetPassage.php');
myRequireOnce ('getLatestContent.php');
myRequireOnce ('writeLog.php');
/* This routine changes bible-link into bible-popup

   Input is : <span class="bible-link">Matthew 5:14</span>
Output: see 3 templates in code
*/

function biblePopupMaker($p){
    $out='';
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
    $template = '<a href="javascript:popUp(\'pop[id]\')">[reference]</a>
    <div class="popup" id="pop[id]">[text]</div>';
    $template_book_not_found='<span class="bible-book-error">[reference]</span>';
    $template_text_not_found='<span class="bible-text-error">[reference]</span>';
    $text = $p['text'];
    // clean text from error spans added by this routine in the previous pass
    $text = str_ireplace('"bible-book-error"', '"bible-link"', $text);
    $text = str_ireplace('"bible-text-error"', '"bible-link"', $text);
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
            $p['entry'] = html_entity_decode($reference);
            $message = $p['entry'] . "  $reference ";
            $p['passage'] =$p['entry'];
            $id = $i + $highest_existing;
            $dbt = createBibleDbtArray($p);
            if (!$dbt){
                writeLogAppend('ERROR-biblePopupMaker-55' , $p);
                $popup = str_replace('[reference]', $reference, $template_book_not_found);
            }
            if ($dbt){
                $dbt['version_ot'] = $ot;
                $dbt['version_nt'] = $nt;
                $bible = bibleGetPassage($dbt);
                if (!isset($bible['text'])){
                    writeLogAppend('ERROR-biblePopupMaker-63' , $dbt);
                    $popup = str_replace('[reference]', $reference, $template_text_not_found);
                }
                else{
                    $bible_text = $bible['text'];
                    // remove any headers
                    if (strpos ($bible_text, '<h3>') !== FALSE){
                        $bible_text = _removeH3($bible_text);
                    }
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
                }
            }
            // replace only first occurance: https://stackoverflow.com/questions/1252693/using-str-replace-so-that-it-only-acts-on-the-first-match
            $pos = strpos($text,$span);
            if ($pos !== false) {
                $text = substr_replace($text,$popup,$pos,strlen($span));
            }
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