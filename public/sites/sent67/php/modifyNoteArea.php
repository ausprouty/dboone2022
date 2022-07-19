<?php
myRequireOnce ('writeLog.php');
/*

This is an attempt to have a growing note area on the iphone
SEE https://css-tricks.com/auto-growing-inputs-textareas/

alter notes area from input:

<div class="note-area" id="note#">
........
</div>

to:

<div class="note-div" >
    <form class = "auto_submit_item">
        Notes: (click outside box to save)<br>
         <textarea class="textarea resize-ta" onchange= "addNote('[id]')"  id ="[id]" ></textarea>';
    </form>
</div>

remove for nojs  (if $p['destination] == 'nojs')

  */
  function modifyNoteArea($text,  $bookmark, $p){

    $standard_instruction = $bookmark['language']->notes;
    $template = '
    <div class="note-div">
        <form class = "auto_submit_item">
            <p class="note-instruction">[user_instruction]</p>
            <textarea class="textarea resize-ta" onchange= "addNote(\'[id]\')"  id ="[id]" ></textarea>';
    if ($p['destination'] == 'nojs' || $p['destination'] == 'pdf'){
       $template ='<div class="note-removed">';
    }

    $count = substr_count($text, '<div class="note-area"');
    for ($i = 1; $i<= $count; $i++){
        $pos_start = strpos($text,'<div class="note-area"');
        $pos_end = strpos($text, '</div>', $pos_start);
        $length = $pos_end - $pos_start;

        $block= substr($text, $pos_start, $length);
        $row_start = strpos($block, 'rows="' ) + 6;
        $row_end = strpos($block, '">', $row_start );
        $row_length = $row_end - $row_start;
        $rows = substr($block, $row_start, $row_length);
        if ($rows == 1){
            $label_start = strpos($block, '<form id="note#">' ) + 17;
            $label_end = strpos($block, '<br />', $label_start );
            $label_length = $label_end - $label_start;
            $user_instruction = substr($block, $label_start, $label_length);
        }
        else{
             $user_instruction = $standard_instruction;
        }
        $bad = array(
            '[user_instruction]',
            '[id]',
            '[rows]'

        );
        $good = array(
            $user_instruction,
            'note'. $i .'Text',
            $rows
        );
        $new_template = str_replace($bad, $good, $template);

         // from https://stackoverflow.com/questions/1252693/using-str-replace-so-that-it-only-acts-on-the-first-match
        $text = substr_replace($text, $new_template, $pos_start, $length);

    }
    return $text;
  }