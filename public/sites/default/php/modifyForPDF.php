<?php

/*

<div class="lesson"><img class="lesson-icon" src="/content/ZZ/images/mc2/mc2back.png" />
<div class="lesson-subtitle"><span class="back">LOOKING BACK</span></div>
</div>

Becomes:

<div class="lesson-subtitle"><img class="lesson-icon" src="/content/ZZ/images/mc2/mc2back.png" />
<span class="back">LOOKING BACK</span></div>

*/

myRequireOnce ('writeLog.php');

function modifyForPDF($text){
    $text= modifyForPDFLookSections($text);
    $text= modifyForPDFImagePath($text);
  return $text;
}
/* Note that all image references are for the site you are going to,
rather than the site you are coming from.

This we need to revert their file structured

<img alt="3 circles" src="../../../../content/M2/eng/tc/transferable-concepts-image-20.png" />
want to replace
<img    ../content/
with
<img    ../sites/SITE_CODE/content/

*/
function modifyForPDFImagePath($text){
    $find = '<img';
    if (strpos($text, $find)){
        $count = substr_count ($text, $find);
        $pos_begin = 0;
        for ($i = 1; $i<=$count; $i++){
            $pos_begin = strpos($text, $find, $pos_begin);
            $pos_end = strpos($text, '>', $pos_begin);
            $length = $pos_end- $pos_begin + 1;
            $old = substr($text, $pos_begin, $length);
            $bad = '../content/';
            $good = '../sites/' . SITE_CODE . '/content/';
            $new = str_ireplace ($bad, $good, $old);
            $text = substr_replace($text, $new, $pos_begin, $length);
            $pos_begin = $pos_end;
        }
    }
    return $text;
}

function modifyForPDFLookSections($text){
    $find = '<div class="lesson">';
    if (strpos($text, $find)){
        $count = substr_count ($text, $find);
        $pos_begin = 0;
        for ($i = 1; $i<=$count; $i++){
           $pos_begin = strpos($text, $find);
           $pos_end = strpos($text, '</div>', $pos_begin); // looking for second one
           $pos_end = strpos($text, '</div>', $pos_end + 1);
           $length = $pos_end- $pos_begin + 6;
           $old = substr($text, $pos_begin, $length);
           $new = str_ireplace('<div class="lesson-subtitle">', '', $old);
           $new = str_ireplace('<div class="lesson">', '<div class="lesson-subtitleX">', $new);
           $one = 1;
           $old = str_ireplace('</div>', '', $new, $one);
            $text = substr_replace($text, $new,$pos_begin, $length);
        }

    }
    $text = str_ireplace('<div class="lesson-subtitleX">','<div class="lesson">', $text);
    return $text;
}
