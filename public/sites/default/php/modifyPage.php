<?php

myRequireOnce ('createPage.php');
myRequireOnce ('getTitle.php');
myRequireOnce ('modifyBibleLinks.php');
myRequireOnce ('modifyHeader.php');
myRequireOnce ('modifyJavascript.php');
myRequireOnce ('modifyLinks.php');
myRequireOnce ('modifyNoteArea.php');
myRequireOnce ('modifyReadMore.php');
myRequireOnce ('modifyRevealAudio.php');
myRequireOnce ('modifyRevealBible.php');
myRequireOnce ('modifyRevealSummary.php');
myRequireOnce ('modifyRevealTrainer.php');
myRequireOnce ('modifyRevealVideo.php');
myRequireOnce ('modifySendAction.php');
myRequireOnce ('prototypeFindFilesInPage.php');
myRequireOnce ('version2Text.php');
myRequireOnce ('writeLog.php');


function modifyPage($text, $p, $data, $bookmark){

    $debug = "\n\nIn modifyPage\n\n";
    $debug .= 'Begin bookmark'. "\n";// what is bookmark?
    foreach ($bookmark as $key => $value){
        $debug .= $key . "\n";
        if (is_array($value)){
            foreach ($value as $key2 => $value2){
                $debug .= '    '. $key2 . "\n";
                if (is_string($value2)){
                    $debug .= '        '. $value2 . "\n";
                }
            }
        }
        else{
            //$debug .= ' '. $value . "\n";
        }

    }
    $debug .= 'end of bookmark'. "\n";
    $debug .= 'read is ' . isset($bookmark['language']->read) ? $bookmark['language']->read : 'Not Set' . "\n";

    $text= version2Text($text);
    writeLog('modifyPages-43-version2text', $text);
    //
    // modify note fields
    //
    if (strpos($text, '"note-area"')  !== false){
        $text =  modifyNoteArea($text, $bookmark);
        //add markers used by javascript
        $page = $p['country_code'] . '-'. $p['language_iso'] . '-'. $p['folder_name'] . '-'. $data['filename'] .'.html';
        $note_form_begin = '<form>'. "\n";
        $note_page =  '<input type="hidden" name ="notes_page"  id ="notes_page" value="' . $page . '">'. "\n";
        $note_form_end = '</form>';
        $text .= $note_form_begin. $note_page.  $note_form_end;
    }
    //
    // strip out open new tab
    //
    $text = str_replace('target="_blank"' ,'', $text);
    $text = str_replace('target="blank"' ,'', $text);
    $text = str_replace('<a  ' ,'<a ', $text);
    //$text = str_replace('href="http' ,' target="_blank" href="http', $text);
    //
    writeLog('modify-page-65', $text);
    //  change internal links for easy return:
    if (strpos($text, '<a href=') !== FALSE){
        $text = modifyLinks($text);
    }
    // popup text needs to be visible to editor but hidden in prototype and production
    if (strpos($text, 'class="popup"')!== FALSE){
        $text = str_ireplace('class="popup"', 'class="popup invisible"', $text);
    }
    if (strpos($text, '<span class="bible-link">')!== FALSE){
        $text = modifyBibleLinks($text, $p);
    }
    if (strpos($text, '<div class="reveal">') !== FALSE ||  strpos($text, '<div class="reveal_big">') !== FALSE){
        $text = modifyRevealSummary($text);
    }
    if (strpos($text, '<div class="reveal bible">')!== FALSE){
        $text = modifyRevealBible($text, $bookmark);
        if ($p['destination']  == 'sdcard'){
           $text = modifyReadMore($text, $bookmark);
        }
    }
    // reveal_big is used by generations
    if (strpos($text, '<div class="reveal film') !== FALSE || strpos($text, '<div class="reveal_big film') !== FALSE){
        $text =  modifyRevealVideo($text, $bookmark, $p);
    }

    if (strpos($text, '<div class="reveal audio">')!== FALSE){
        $text = modifyRevealAudio($text, $bookmark, $p);
    }
    if (strpos($text, '<div class="javascript') !== false){
        $text  = modifyJavascript($text);
    }
    /* if (strpos($text, '<div class="header') !== false){
This needs to come later in the process
    }
    */
    if (strpos($text, '<div class="trainer">')!== FALSE){
        $text = modifyRevealTrainer($text);
    }
    $bad = ['<div id="bible">','<div class="bible_container bible">' ];
    $text = str_replace($bad, '<div class="bible_container">', $text );
    $text = str_replace('bible-readmore', 'readmore', $text );

    //action button
    if (strpos($text, '<button class="action">') !== FALSE){
        $text = modifySendAction($text, $p, $data);
    }
    // get rid of horizontal lines and other odd things
    $text = str_replace('<hr />', '', $text);
    $text = str_replace('<li>&nbsp;', '<li>', $text);
    $text = str_replace('</a> )', '</a>)', $text);
    return $text;
}
