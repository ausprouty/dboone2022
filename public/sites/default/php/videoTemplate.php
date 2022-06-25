<?php

function videoTemplateLink($bookmark, $url){
    if (strpos($url, 'https://4.dbt.io') !== FALSE){
    $template_link = '<button id="revealButton[id]"type="button" class="external-dbt-movie"
      onclick="dbtVideoPlay(\'[id]\', \'[play_list]\', \'[start_time]\', \'[duration]\')">[title_phrase]</button>
        <div class="collapsed">
            <video preload="none" id="dbtVideo[id]"  controls crossorigin></video>
        </div>';
    }
    else{
        $template_link = '<button id="revealButton[id]" type="button" class="external-movie">[title_phrase]</button>
        <div class="collapsed">[video]</div>';
    }
    writeLogDebug('videoTemplateLink-14', $template_link);
    return  $template_link;

}

function videoTemplateWatchPhrase($bookmark){
   $watch_phrase = $bookmark['language']->watch;
    return $watch_phrase;
}
