<?php
myRequireOnce('writeLog.php');
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
        <button id="AudioButton0" type="button" class="external-audio ">Listen to Title</button>
        <div class="collapsed">
            <audio controls src="TC2.mp3"> </audio>
            <p>Text</p>
        </div>
    OR
    <button id="AudioButton0" type="button" class="external-audio ">Listen to Title</button>
        <div class="collapsed">
            <iframe src="https://open.spotify.com/embed/track/26HTolgTkxItxPoqErHewB" width="100%" height="80" frameBorder="0" allowtransparency="true" allow="encrypted-media">
            </iframe>
            <p>Text</p>
        </div>
    OR   Output for Vimeo : where input is https://vimeo.com/162977296 AND you want the words LISTEN TO
        <button id="VimeoButton0" type="button" class="external-movie ">Watch  Luke 18:35-43 online</button>
        <div class="collapsed">[vimeo]162977296</div>



*/
function modifyRevealAudio($text, $bookmark){

    $out = [];
    $out['debug'] = 'In revealaudio' . "\n";
    writeLog('modifyRevealAudio-48', $out['debug']);
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
        $title = '<i>'. modifyAudioRevealFindText($old, 2) . '</i>&nbsp;';
        $title_phrase =  $word = str_replace('%', $title, $listen_phrase);
        //find url
        $url = modifyAudioRevealFindText($old, 4);
        $out['debug'] .=  "url is | $url |\n";
        $audio_text = modifyAudioRevealFindText($old, 6);
        $out['debug'] .=  "audio_text is | $audio_text |\n";
        if (strpos ($url, 'open.spotify.com') !== false){
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
        }
        else{
             $new = $local_template;
        }
        // make replacements
        $new = str_replace('[id]', $i, $new);
        $new = str_replace('[title_phrase]', $title_phrase, $new);
        $new = str_replace('[url]', $url, $new);
        $new = str_replace('[audio_text]', $audio_text, $new);
        $out['debug'] .=  "new is | $new |\n";
        // replace old
         // from https://stackoverflow.com/questions/1252693/using-str-replace-so-that-it-only-acts-on-the-first-match
        $length = $pos_end - $pos_start + 6;  // add 6 because last item is 6 long
        $text = substr_replace($text, $new, $pos_start, $length);
    }
    $out['content'] = $text;
    writeLog('modifyaudioReveal', $out['debug']);
    return $out;
}
// return the text from the td_segment
function modifyAudioRevealFindText($old, $td_number){
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
