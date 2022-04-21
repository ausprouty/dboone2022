<?php

function videoTemplateLink($bookmark){
    $template_link ='
        <button id="VimeoButton0" type="button" class="external-movie ">[title_phrase]</button>
        <div class="collapsed">
        <video id="video"  width = "100%" controls>
        <source src="[video]" type="video/mp4">
        </div>';
    return  $template_link;

}

function videoTemplateWatchPhrase($bookmark){
    $watch_phrase = $bookmark['language']->watch_offline;
    return $watch_phrase;
}
