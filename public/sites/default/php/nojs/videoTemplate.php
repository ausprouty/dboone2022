<?php

function videoTemplateLink($bookmark){
     $template_link =
        '<video width="100%"  controls>
            <source src="[video]" type="video/mp4">
            <a href="[video]"> Watch Here</a>
        </video>';
    return  $template_link;

}

function videoTemplateWatchPhrase($bookmark){
    $watch_phrase = '';
    return $watch_phrase;
}
