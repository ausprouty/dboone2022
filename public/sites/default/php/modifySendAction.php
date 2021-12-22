<?php

function modifySendAction($text, $p, $data){
    $debug = 'In _sendAction'. "\n";
    $page = $p['country_code'] . '-'. $p['language_iso'] . '-'. $p['folder_name'] . '-'. $data['filename'] .'.html';
    $action = '<button id="sendAction" class="action hidden" onclick="sendAction(\'[filename]\', \'[title]\' )" >';
    $action = str_replace('[filename]', $page,  $action );
     $title = getTitle($p['recnum']);
    $action = str_replace('[title]', $title,  $action );
    $text = str_replace('<button class="action">', $action, $text);
    $debug .= 'Leaving _sendAction'. "\n";
    return $text;
}