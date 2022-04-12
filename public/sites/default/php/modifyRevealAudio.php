<?php
myRequireOnce ('writeLog.php');
myRequireOnce ('audioReference.php', 'sdcard');
/*
Input is:
    <--Start Audio Template-->
    <div class="reveal audio">&nbsp;
        <hr />
        <table class="" border="1">
            <tbody  class="audio">
                <tr class="audio" >
                    <td class="audio label" ><strong>Title:</strong></td>
                    <td class="audio" >[Title]</td>
                </tr>
                <tr class="audio" >
                    <td class="audio label" ><strong>URL:</strong></td>
                    <td class="audio" >[Link]</td>
                </tr>
                <tr class="audio" >
                    <td class="audio label" ><strong>Optional Text</strong></td>
                    <td class="audio" >[Text]</td>
                </tr>
            </tbody>
        </table>
    <hr /></div>
    <--Start Audio Template-->';




 Output
        <button id="AudioButton0" type="button" class="external-audio ">Listen to Title online<</button>
        <div class="collapsed">
            <audio controls src="TC2.mp3"> </audio>
            <p>Text</p>
        </div>
    OR
    <button id="AudioButton0" type="button" class="external-audio ">Listen to Title online</button>
        <div class="collapsed">
            <iframe src="https://open.spotify.com/embed/track/26HTolgTkxItxPoqErHewB" width="100%" height="80" frameBorder="0" allowtransparency="true" allow="encrypted-media">
            </iframe>
            <p>Text</p>
        </div>
    OR   Output for Vimeo : where input is https://vimeo.com/162977296 AND you want the words LISTEN TO
        <button id="VimeoButton0" type="button" class="external-movie ">Listen to Title online</button>
        <div class="collapsed">[vimeo]162977296</div>


        For SD Card:
    <button id="AudioButton0" type="button" class="external-audio ">Listen to Title</button>
    <div class="collapsed">
        <audio controls>
            <source src="myAudio.mp3" type="audio/mpeg">
            <p>Your browser doesn't support audio. Here is a
            <a href="myAudio.mp3">link to the audio</a> instead.</p>
        </audio>
    </div>

*/
function modifyRevealAudio($text, $bookmark, $p){
    $debug ='';
    //writeLog('modifyRevealAudio-61-text', $text);
    //writeLog('modifyRevealAudio-61-p', $p);'
    if ($p['destination'] == 'nojs'){
        $listen_phrase ='';
       $local_template = '';
    }
    if ($p['destination'] == 'nojs'){
        $listen_phrase = $bookmark['language']->listen_offline;
       $local_template = '<div> <a href src="[url]">[title_phrase]</a></div>
        <audio width="100%"  controls src="[url]">
        </audio>';
    }
    elseif ($p['destination'] == 'sdcard' || $p['destination'] == 'apk'){
        $listen_phrase = $bookmark['language']->listen_offline;
        $local_template= '
        <button id="AudioButton[id]" type="button" class="collapsible external-audio ">[title_phrase]</button>
        <div class="collapsed" style="display:none;">
            <audio id="plyr-audio[id]" controls>
                <source src="[url]" type="audio/mp3">
            </audio>
            <script>plyr.setup("#plyr-audio[id]");</script>
            <p>[audio_text]</p>
        </div>
        ';

    }
    else{
        $listen_phrase = $bookmark['language']->listen;
        $local_template= '
        <button id="AudioButton[id]" type="button" class="collapsible external-audio ">[title_phrase]</button>
        <div class="collapsed" style="display:none;">
            <audio controls src="[url]">Sorry, Your browser does not support our audio playback.  Try Chrome. </audio>
            <p>[audio_text]</p>
        </div>
        ';
        $spotify_template= '
        <button id="AudioButton[id]" type="button" class="collapsible external-audio ">[title_phrase]</button>
        <div class="collapsed" style="display:none;">
            <iframe src="[url]" width="100%" height="80" frameBorder="0" allowtransparency="true" allow="encrypted-media">
            </iframe>
            <p>[audio_text]</p>
        </div>
        ';
        $soundcloud_template = '
        <button id="AudioButton[id]" type="button" class="collapsible external-audio ">[title_phrase]</button>
        <div class="collapsed" style="display:none;"><iframe width="100%" height="166" scrolling="no" frameborder="no"
            src="https://w.soundcloud.com/player/?url=[url]&color=%23f9b625&auto_play=false&hide_related=true&show_comments=false&show_user=false&show_reposts=false&show_teaser=false">
            </iframe>
            <p>[audio_text]</p>
        </div>';
        $youtube_template= '<p></p><a href="[url]" target="_blank">[title_phrase]</a></p>';
        $vimeo_template ='<button id="VimeoButton[id]" type="button" class="external-movie ">[title_phrase]</button>
            <div class="collapsed">[vimeo][url]</div>';
    }

    // [ChangeLanguage] is changed in local.js
    $find = '<div class="reveal audio">';
    $count = substr_count($text, $find);
    for ($i = 0; $i < $count; $i++){
        // get old division
        $pos_start = strpos($text,$find);
        $pos_end = strpos($text, '</div>', $pos_start);
        $length = $pos_end - $pos_start + 6;  // add 6 because last item is 6 long
        $old = substr($text, $pos_start, $length);
        // find title_phrase
        $title = '<i>'. modifyRevealAudioFindText($old, 2) . '</i>&nbsp;';
        $title_phrase =  $word = str_replace('%', $title, $listen_phrase);
        //find url
        $url = modifyRevealAudioFindText($old, 4);
        $debug .=  "url is | $url |\n";
        $audio_text = modifyRevealAudioFindText($old, 6);
        $debug .=  "audio_text is | $audio_text |\n";
        if ($p['destination'] == 'sdcard' || $p['destination'] == 'nojs'||  $p['destination'] == 'apk'){
            $new = $local_template;
            $url = modifyRevealAudioSDCardUrl($url);
        }
        else{if (strpos ($url, 'open.spotify.com') !== false){
            $new = $spotify_template;
        }
        else if (strpos ($url, 'api.soundcloud.com') !== false){
            $new = $soundcloud_template;
        }
        else if (strpos ($url, 'music.youtube') !== false){
            $new = $youtube_template;
        }
        else if (strpos ($url, 'vimeo.com') !== false){
            $new = $vimeo_template;
            $url= str_ireplace('https://vimeo.com/', '', $url);
        }}


        // make replacements
        $new = str_replace('[id]', $i, $new);
        $new = str_replace('[title_phrase]', $title_phrase, $new);
        $new = str_replace('[url]', $url, $new);
        $new = str_replace('[audio_text]', $audio_text, $new);
        $debug .=  "new is | $new |\n";
        // replace old
         // from https://stackoverflow.com/questions/1252693/using-str-replace-so-that-it-only-acts-on-the-first-match
        $length = $pos_end - $pos_start + 6;  // add 6 because last item is 6 long
        $text = substr_replace($text, $new, $pos_start, $length);
    }
    //writeLog('modifyaudioReveal-147-text', $text);
    return $text;
}
// return the text from the td_segment
function modifyRevealAudioFindText($old, $td_number){
    $pos_td = 0;
    for ($i = 1; $i<= $td_number; $i++){
        $pos_td = strpos($old, '<td', $pos_td + 1);
    }
    $pos_start = strpos($old, '>', $pos_td) +1;
    $pos_end = strpos($old, '</', $pos_td);
    $length = $pos_end - $pos_start;
    $text = substr($old, $pos_start, $length);
    $text = strip_tags($text);
    return $text;
}
function modifyRevealAudioSDCardUrl($url){

    $url = trim($url);
    $link = audioReference();
    if (isset($link[$url])){

       return $link[$url];
    }
    else{
        if (strpos($url, 'https%3A')){
            $url = str_ireplace('https%3A', 'https:', $url);
            if (isset($link[$url])){
                return $link[$url];
            }
        }
        return 'unknown';
    }
}
