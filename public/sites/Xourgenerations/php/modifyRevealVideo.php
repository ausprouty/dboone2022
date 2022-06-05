<?php
myRequireOnce ('writeLog.php');
/*
Input is:
    <div class="reveal film">&nbsp;
        <hr />
        <table class="video" border="1">
            <tbody  class="video">
                <tr class="video" >
                    <td class="video label" ><strong>Title:</strong></td>
                    <td class="video" >[Title]</td>
                </tr>
                <tr class="video" >
                    <td class="video label" ><strong>URL:</strong></td>
                    <td class="video" >[Link]</td>
                </tr>
                <tr class="video" >
                    <td class="video instruction"  colspan="2" style="text-align:center">
                    <h2><strong>Set times if you do not want to play the entire video</strong></h2>
                    </td>
                </tr>
                <tr class="video" >
                    <td class="video label" >Start Time: (min:sec)</td>
                    <td class="video" >start</td>
                </tr>
                <tr class="video" >
                    <td class="video label" >End Time: (min:sec)</td>
                    <td class="video" >end</td>
                </tr>
            </tbody>
        </table>

    <hr /></div>';

Data structure:
 video Types from ArcLight:  https://api.arclight.org/videoPlayerUrl?refId=1_529-jf6159-30-0
    1_ = jfilm
    2_ = acts
    6_= lumo

    Second set of numbers is language

Output for Acts: where input is https://api.arclight.org/videoPlayerUrl?refId=2_529-Acts7302-0-0
    NOT:

    <button id="RevealButton0" type="button" class="external-movie acts">Watch  Acts 6:1-7 online</button>
        <div class="collapsed">[acts]-Acts7302-0-0</div>
        <div id="ShowOptionsFor[acts]-Acts7302-0-0"></div>

    INSTEAD:

    <div id="VideoToggle3" class="video">
        <div onclick="toggleVideo('Videotoggle3')" class="summary-visible">
            <img class = "generations_plus_big" src="/images/generations-plus-big.png">
            <div class="summary-title">
                <span class = "summary_heading" >Watch <i> Acts 6:1-7 </i>online</span>
            </div>
        </div>
        <div class="collapsed" id ="VideoToggleVideo3">
            [acts]-Acts7302-0-0</div>
        </div>
        <div id="ShowOptionsFor[acts]-Acts7302-0-0"></div>
    </div>



*/
function modifyRevealVideo($text, $bookmark){

    $out = [];
    $debug = 'In 4G revealVideo' . "\n";
    $watch_phrase = $bookmark['language']->watch;
    $standard_template_link= '<button id="revealButton[id]" type="button" class="external-movie [video_type]">[title_phrase]</button>
        <div class="collapsed">[video]</div>';
    $template_link = '
    <div id="VideoToggle[id]" class="summary [video_type]">
        <div onclick="toggleVideo(\'VideoToggle[id]\', \'[video_type]\')" class="summary-visible">
            <img class = "generations-plus-big" src="/images/generations-plus-big.png">
            <div class="summary-title">
                <span class = "summary_heading" >[title_phrase]</span>
            </div>
        </div>
        <div class="collapsed" id ="VideoToggle[id]Video">
            [video]
        </div>';
    $template_options = '<div id="ShowOptionsFor[video]"></div>';
    // [ChangeLanguage] is changed in local.js
    $find = '<div class="reveal_big film">';
    $count = substr_count($text, $find);
    $debug = "count is $count \n";;
    for ($i = 0; $i < $count; $i++){
        $new = $template_link;
        // get old division
        $pos_start = strpos($text,$find);
        $pos_end = strpos($text, '</div>', $pos_start);
        $length = $pos_end - $pos_start + 6;  // add 6 because last item is 6 long
        $old = substr($text, $pos_start, $length);
        // find title_phrase
        $title = modifyVideoRevealFindText($old, 2);
        $title = '<i>' . $title . '</i>';
        $title_phrase =  $word = str_replace('%', $title, $watch_phrase);
        //find url
        $url = modifyVideoRevealFindText($old, 4);
        $debug .=  "url is | $url |\n";
        // find type of video and trim url
        if (strpos($url, 'api.arclight.org/videoPlayerUrl?') != FALSE){
            $new .=  $template_options; // JESUS project videos are available in many languages
            //https://api.arclight.org/videoPlayerUrl?refId=6_529-GOMatt2512
            $url = str_ireplace('https://api.arclight.org/videoPlayerUrl?refId=', '', $url); //6_529-GOMatt2512
            $video_type_string = substr($url, 0, 1); //6
            switch ($video_type_string){
                case 1:
                    $video_type = 'jfilm';
                    break;
                case 2:
                    $video_type = 'acts';
                    break;
                case 6:
                    $video_type = 'lumo';
                    break;
                default:
                 $video_type = $video_type_string;
            }
            if (strpos($url, '-') !== FALSE){
                $dash = strpos($url, '-');
                $url = substr($url, $dash);
            }
            // find start and end times
            $start_time = modifyVideoRevealFindTime ($old, 7);
            $debug .=  "start_time is | $start_time |\n";
            $end_time = modifyVideoRevealFindTime ($old, 9);
            $debug .=  "end time is | $end_time |\n";
            if ($start_time || $end_time){
                $url .= '&start='. $start_time . '&end=' .$end_time;
            }
        }
        elseif (strpos($url, 'https://vimeo.com/') !== FALSE){  //https://vimeo.com/162977296
            $video_type = 'vimeo';
            $url = str_ireplace('https://vimeo.com/', '', $url); //162977296
        }
        else{
            $video_type = 'unknown';
            $url = 'unknown';

        }
        // make replacements
        $video = '['. $video_type . ']' . $url; //[lumo]-GOMatt2512

        $debug .=  "video is | $video |\n";
        $new = str_replace('[video]', $video, $new);
        $new = str_replace('[video_type]', $video_type, $new);
        $new = str_replace('[id]', $i, $new);
        $new = str_replace('[title_phrase]', $title_phrase, $new);
        $new .= '
    </div>';
        $debug .=  "new is | $new |\n";
        // replace old
         // from https://stackoverflow.com/questions/1252693/using-str-replace-so-that-it-only-acts-on-the-first-match
        $length = $pos_end - $pos_start + 6;  // add 6 because last item is 6 long
        $text = substr_replace($text, $new, $pos_start, $length);
    }
    writeLog('modifyVideoReveal', $debug);
    return $text;
}
// return the text from the td_segment
function modifyVideoRevealFindText($old, $td_number){
    $pos_td = 0;
    for ($i = 1; $i<= $td_number; $i++){
        $pos_td = strpos($old, '<td', $pos_td + 1);
    }
    $pos_start = strpos($old, '>', $pos_td) +1;
    $pos_end = strpos($old, '</', $pos_td);
    $length = $pos_end - $pos_start;
    $text = substr($old, $pos_start, $length);
    $text = trim(rtrim($text));
    return $text;
}
function modifyVideoRevealFindTime ($old,$td_number){
    $text = modifyVideoRevealFindText($old, $td_number);
    if ($text == 'start'){$time = 0; }
    else if ($text == 'end'){$time = null; }
    else{ $time = timeToSeconds($text);}
    return $time;
}
//function timeToSeconds(string $time): int
function timeToSeconds($time)
{
    if (strpos($time, ':') == FALSE){
        return intval($time);
    }
    $arr = explode(':', $time);
    if (count($arr) == 3) {
        return $arr[0] * 3600 + $arr[1] * 60 + $arr[2];
    } else {
        return $arr[0] * 60 + $arr[1];
    }
}
