<?php

function videoTemplateLink($bookmark){
    $template_link = '<button id="revealButton[id]" type="button" class="external-movie">[title_phrase]</button>
    <div class="collapsed">[video]</div>';
    return  $template_link;

}

function videoTemplateWatchPhrase($bookmark){
   $watch_phrase = $bookmark['language']->watch;
    return $watch_phrase;
}
