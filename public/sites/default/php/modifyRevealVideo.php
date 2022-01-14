<?php
myRequireOnce ('writeLog.php');
myRequireOnce('videoFindForSDCardNewName.php');
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

For Website Data:

Output for Acts: where input is https://api.arclight.org/videoPlayerUrl?refId=2_529-Acts7302-0-0
    <button id="RevealButton0" type="button" class="external-movie acts">Watch  Acts 6:1-7 online</button>
        <div class="collapsed">[acts]-Acts7302-0-0</div>
        <div id="ShowOptionsFor[acts]-Acts7302-0-0"></div>

Output for JFilm:  where input is https://api.arclight.org/videoPlayerUrl??refId=1_529-jf6159-30-0
    <button id="RevealButton0" type="button" class="external-movie jfilm">Watch  Acts 6:1-7 online</button>
        <div class="collapsed">[jfilm]-jf6159-30-0</div>
        <div id="ShowOptionsFor[jfilm]-jf6159-30-0></div>

Output for Lumo [Nerw] : where input is https://api.arclight.org/videoPlayerUrl?refId=6_529-GOMatt2512
    <button id="RevealButton0" type="button" class="external-movie lumo">Watch  Matthew 3:1-17 online</button>
        <div class="collapsed">[lumo]-GOMatt2512</div>
        <div id="ShowOptionsFor[lumo]-GOMatt2512"></div>

 Output for Lumo [Old] : where input is  https://api.arclight.org/videoPlayerUrl?refId=6_529-GOLukeEnglish6030
    <button id="RevealButton0" type="button" class="external-movie lumo">Watch  Matthew 3:1-17 online</button>
        <div class="collapsed">[lumo]--GOLukeEnglish6030</div>
        <div id="ShowOptionsFor[lumo]--GOLukeEnglish6030"></div>

 Output for Vimeo : where input is https://vimeo.com/162977296
        <button id="VimeoButton0" type="button" class="external-movie ">Watch  Luke 18:35-43 online</button>
        <div class="collapsed">[vimeo]162977296</div>

OLD For SD Card:
    <button id="VimeoButton0" type="button" class="external-movie ">Watch  Luke 18:35-43 </button>
    <div class="collapsed">
        <video controls>
            <source src="myVideo.mp4" type="video/mp4">
            <p>Your browser doesn't support video. Here is a
            <a href="myVideo.mp4">link to the video</a> instead.</p>
        </video>
    </div>
SD Card:
    <button id="VimeoButton0" type="button" class="external-movie ">Watch  Luke 18:35-43 </button>
    <div class="collapsed">
      <video id="video[id]"  width = "100%" controls>
      <source src="[video]" type="video/mp4">
      <script>  plyr.setup("#video[id]");</script>
    </div>

NO JS:
    <div> <a href src="[video]">Watch  Luke 18:35-43 </a></div>

*/
function modifyRevealVideo($text, $bookmark, $p){
    $debug = '';

    if ($p['destination'] == 'nojs'){
        $watch_phrase = '';
        $template_link =
        '<video width="100%"  controls>
            <source src="[video]" type="video/mp4">
            <a href="[video]"> Watch Here</a>
        </video>';
    }
    elseif ($p['destination'] == 'sdcard'){
        $watch_phrase = $bookmark['language']->watch_offline;
        $template_link ='
        <button id="VimeoButton0" type="button" class="external-movie ">[title_phrase]</button>
        <div class="collapsed">
        <video id="video[id]"  width = "100%" controls>
        <source src="[video]" type="video/mp4">
        <script>plyr.setup("#video[id]");</script>
            <a href="javascript:void(0);" onclick="shareVideo([video])">
                <img class="social" src="/content/M2/images/standard/share_video.png" />
            </a>
        </div>';
    }
    else{
        $watch_phrase = $bookmark['language']->watch;
        $template_link = '<button id="revealButton[id]" type="button" class="internal-movie">[title_phrase]</button>
                   <div class="collapsed">[video]</div>';
    }
    $template_options = '<div id="ShowOptionsFor[video]"></div>';
    // [ChangeLanguage] is changed in local.js
    $find = '<div class="reveal film">';
    $count = substr_count($text, $find);
    for ($i = 0; $i < $count; $i++){
        // we only have one video link for each page on the sdcard
        $new = $template_link;
        if ($i > 0 && ($p['destination'] == 'sdcard' || $p['destination'] == 'nojs')){
          $new = '';
        }
        // get old division
        $pos_start = strpos($text,$find);
        $pos_end = strpos($text, '</div>', $pos_start);
        $length = $pos_end - $pos_start + 6;  // add 6 because last item is 6 long
        $old = substr($text, $pos_start, $length);
        // find title_phrase
        $title = modifyVideoRevealFindText($old, 2);
        $title = '&nbsp;<i>'. $title . '</i>&nbsp;';
        $title_phrase =  $word = str_replace('%', $title, $watch_phrase);
        //find url
        $url = modifyVideoRevealFindText($old, 4);
         if ($p['destination'] == 'sdcard' || $p['destination'] =='nojs'){
             $filename = $bookmark['page']->filename;
             $video = '/media/'. $p['country_code'] . '/'. $p['language_iso'] .'/video/'. videoFindForSDCardNewName($filename) ;
             $video .='.mp4';
             $video_type= 'file';
         }
        else {
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
            elseif (strpos($url, 'https://www.youtube.com/watch?v=') !== FALSE){  //https://www.youtube.com/watch?v=I7ks0udfjOw
                $video_type = 'youtube';
                $url = str_ireplace('https://www.youtube.com/watch?v=', '', $url); //I7ks0udfjOw
            }
            elseif (strpos($url, 'https://youtu.be/') !== FALSE){  //https://youtu.be/I7ks0udfjOw?t=732
                $video_type = 'youtube';
                $url = str_ireplace('https://youtu.be/', '', $url); //I7ks0udfjOwt=732
            }
            else{
                $video_type = 'url';
            }
            // make replacements
            $video = '['. $video_type . ']' . $url; //[lumo]-GOMatt2512
        }
        $debug .=  "video is | $video |\n";
        $new = str_replace('[video]', $video, $new);
        $new = str_replace('[video_type]', $video_type, $new);
        $new = str_replace('[id]', $i, $new);
        $new = str_replace('[title_phrase]', $title_phrase, $new);
        $debug .=  "new is | $new |\n";
        // replace old
         // from https://stackoverflow.com/questions/1252693/using-str-replace-so-that-it-only-acts-on-the-first-match
        $length = $pos_end - $pos_start + 6;  // add 6 because last item is 6 long
        $text = substr_replace($text, $new, $pos_start, $length);
    }
    //writeLog('modifyVideoReveal', $debug);
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
    $text = strip_tags($text);
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
