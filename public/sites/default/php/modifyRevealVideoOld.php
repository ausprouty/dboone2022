<?php
/*
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

This is legacy data but 1_ = jfilm
                        2_ = acts
                        6_= lumo

Output is:  
    <button id="ButtonWatch" type="button" class="collapsible external-movie">Watch online</button>
    <div class="collapsed" id ="TextWatch">
        <div class="arc-cont">
            <iframe src="https://player.vimeo.com/video/'. $video . '" allowfullscreen webkitallowfullscreen mozallowfullscreen></iframe>
        <style>.arc-cont{position:relative;display:block;margin:10px auto;width:100%}.arc-cont:after{padding-top:59%;display:block;content:""}.arc-cont>iframe{position:absolute;top:0;bottom:0;right:0;left:0;width:98%;height:98%;border:0}</style>
        </div> 
    </div> 
*/
function modifyRevealVideo($text, $bookmark){
    
}
function revealVideoOld($text, $bookmark){
    
    $debug = 'In revealVideo';
    $watch_phrase = $bookmark['language']->watch;

    $template= '<button id="VideoTitle[id]" type="button" class="collapsible external-movie">[Title]</button>
    <div class="collapsed" id ="VideoFrame[id]">
        <div class="arc-cont">
            <iframe src="[Player][Video]" allowfullscreen webkitallowfullscreen mozallowfullscreen></iframe>
        <style>.arc-cont{position:relative;display:block;margin:10px auto;width:100%}.arc-cont:after{padding-top:59%;display:block;content:""}.arc-cont>iframe{position:absolute;top:0;bottom:0;right:0;left:0;width:98%;height:98%;border:0}</style>
        </div> 
    </div>';

    $player_vimeo = 'https://player.vimeo.com/video/';
    $player_jproject = null;
    $find = '<div class="reveal video">';
    $count = substr_count($text, $find);
    for ($i = 0; $i < $count; $i++){
        $new = $template;
        $player =$player_jproject;
        // get division
        $pos_start = mb_strpos($text,$find);
        $pos_end = mb_strpos($text, '</div>', $pos_start);
        $length = $pos_end - $pos_start + 6;  // add 6 because last item is 6 long
        $old = mb_substr($text, $pos_start, $length);
        $debug .=  "old is $old\n";
        //find Video Title and add phrase "Watch Online"
        $word = trim(strip_tags($old));
        $word = trim(strip_tags($old));
        $word = str_replace('&nbsp;', ' ', $word);
        $word = str_replace('%', $word, $watch_phrase);
        $new = str_replace('[id]', $i, $new);
        $new = str_replace('[Title]', $word, $new);
        // replace player
        if (mb_strpos($old, 'vimeo') !== FALSE){
            $player = $player_vimeo;
            $pos_vid_start = mb_strpos($old, 'vimeo.com/') + 10; // add 6 because string is 6 long
            $pos_vid_end = mb_strpos($old,'">' , $pos_vid_start);
            $vid_length = $pos_vid_end - $pos_vid_start + 0; // add 0 because we don't want any of that
            $video = mb_substr($old, $pos_vid_start, $vid_length);
        }
        else{
             //find video
            $pos_vid_start = mb_strpos($old, 'href="') + 6; // add 6 because string is 6 long
            $pos_vid_end = mb_strpos($old,'">' , $pos_vid_start);
            $vid_length = $pos_vid_end - $pos_vid_start + 0; // add 0 because we don't want any of that
            $video = mb_substr($old, $pos_vid_start, $vid_length);
        }
        $new = str_replace('[Player]', $player, $new); 
        $new = str_replace('[Video]', $video, $new); 

        $debug .=  "word is $word\n";
        $debug .=  "new is $new\n";
        
         // from https://stackoverflow.com/questions/1252693/using-str-replace-so-that-it-only-acts-on-the-first-match
         // added these because we are not using a multi-bite function below.
        $pos_start = strpos($text,$find);
        $pos_end = strpos($text, '</div>', $pos_start);
        $length = $pos_end - $pos_start + 6;  // add 6 because last item is 6 long
        $text = substr_replace($text, $new, $pos_start, $length);
    }
    $out = $text;
    return $out;
}
/* <div class="reveal video">&nbsp;
<hr /><a href="https://api.arclight.org/videoPlayerUrl?refId=6_529-GOMattEnglish5104">Matthew 3:1-17</a>

<hr /></div>
*/
function revealVideo($text, $bookmark){
    
    $debug = 'In revealVideo' . "\n";;
    $watch_phrase = $bookmark['language']->watch;
    $template= '<button id="revealButton[id]" type="button" class="external-movie [video_type]">[Title]</button>
        <div class="collapsed">[Video]</div>
        <div id="ShowOptionsFor[Video]"></div>' ;
    // [ChangeLanguage] is changed in local.js
    $find = '<div class="reveal video">';
    $count = substr_count($text, $find);
    for ($i = 0; $i < $count; $i++){
        $new = $template;
        // get old division
        $pos_start = strpos($text,$find);
        $pos_end = strpos($text, '</div>', $pos_start);
        $length = $pos_end - $pos_start + 6;  // add 6 because last item is 6 long
        $old = substr($text, $pos_start, $length);
        $debug .=  "old is | $old |\n";
        //find Video Title and add phrase "Watch Online"
        $word = trim(strip_tags($old));
        $word = trim(strip_tags($old));
        $word = str_replace('&nbsp;', ' ', $word);
        $word = str_replace('%', $word, $watch_phrase);
        $word = str_replace("\n", '', $word);
        $word = str_replace("\r", '', $word);
        $new = str_replace('[id]', $i, $new);
        $new = str_replace('[Title]', $word, $new);
        // find type of video
        $pos_video_type_start = strpos($old, 'refId=');
        $debug .=  "pos_video_type_start | $pos_video_type_start |\n";
        $pos_video_type_end = strpos($old, '_', $pos_video_type_start );
        $debug .=  "pos_video_type_end | $pos_video_type_end |\n";
        $video_type_length = $pos_video_type_end - $pos_video_type_start - 6;
        $debug .=  "video_type_length | $video_type_length |\n";
        $video_type_string = substr($old, $pos_video_type_start + 6, $video_type_length);
        $debug .=  "video_type_string | $video_type_string |\n";
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
        // replace link  "https://api.arclight.org/videoPlayerUrl?refId=2_20615-Acts7306-0-0"
        $pos_vid_start = strpos($old, '-') ; 
        $pos_vid_end = strpos($old,'">' , $pos_vid_start);
        $vid_length = $pos_vid_end - $pos_vid_start + 0; // add 0 because we don't want any of that
        $video = '['. $video_type . ']' . mb_substr($old, $pos_vid_start, $vid_length); //-Acts7306-0-0
        $new = str_replace('[Video]', $video, $new); 
        $new = str_replace('[video_type]', $video_type, $new); 
        $debug .=  "word is | $word |\n";
        $debug .=  "new is | $new |\n";
        
         // from https://stackoverflow.com/questions/1252693/using-str-replace-so-that-it-only-acts-on-the-first-match
         // added these because we are not using a multi-bite function below.
        $pos_start = strpos($text,$find);
        $pos_end = strpos($text, '</div>', $pos_start);
        $length = $pos_end - $pos_start + 6;  // add 6 because last item is 6 long
        $text = substr_replace($text, $new, $pos_start, $length);
    }
    $out = $text;
    return $out;
}
function revealVideoActs($text, $bookmark){
    
    $debug = 'In revealVideoActs' . "\n";;
    $watch_phrase = $bookmark['language']->watch;
    $template= '<button id="ActsButton[id]" type="button" class="external-movie acts">[Title]</button>
        <div class="collapsed">[Video]</div>
        <div id="ShowOptionsFor[Video]"></div>' ;
    // [ChangeLanguage] is changed in videos.js
    $find = '<div class="reveal video acts">';
    $count = substr_count($text, $find);
    for ($i = 0; $i < $count; $i++){
        $new = $template;
        // get old division
        $pos_start = mb_strpos($text,$find);
        $pos_end = mb_strpos($text, '</div>', $pos_start);
        $length = $pos_end - $pos_start + 6;  // add 6 because last item is 6 long
        $old = mb_substr($text, $pos_start, $length);
        $debug .=  "old is $old\n";
        //find Video Title and add phrase "Watch Online"
        $word = trim(strip_tags($old));
        $word = trim(strip_tags($old));
        $word = str_replace('&nbsp;', ' ', $word);
        $word = str_replace('%', $word, $watch_phrase);
        $new = str_replace('[id]', $i, $new);
        $new = str_replace('[Title]', $word, $new);
        // replace link  "https://api.arclight.org/videoPlayerUrl?refId=2_20615-Acts7306-0-0"
        $pos_vid_start = strpos($old, '-') ; 
        $pos_vid_end = strpos($old,'">' , $pos_vid_start);
        $vid_length = $pos_vid_end - $pos_vid_start + 0; // add 0 because we don't want any of that
        $video = '[acts]' . mb_substr($old, $pos_vid_start, $vid_length); //-Acts7306-0-0
        $new = str_replace('[Video]', $video, $new); 
        $debug .=  "word is $word\n";
        $debug .=  "new is $new\n";
        
         // from https://stackoverflow.com/questions/1252693/using-str-replace-so-that-it-only-acts-on-the-first-match
         // added these because we are not using a multi-bite function below.
        $pos_start = strpos($text,$find);
        $pos_end = strpos($text, '</div>', $pos_start);
        $length = $pos_end - $pos_start + 6;  // add 6 because last item is 6 long
        $text = substr_replace($text, $new, $pos_start, $length);
    }
    $out = $text;
    return $out;
}
function revealVideoJFilm($text, $bookmark){
    
    $debug = 'In revealVideoJFilm' . "\n";;
    $watch_phrase = $bookmark['language']->watch;
    $template= '<button id="JFilmButton[id]" type="button" class="external-movie jfilm">[Title]</button>
        <div class="collapsed">[Video]</div>
        <div id="ShowOptionsFor[Video]"></div>' ;
    // [ChangeLanguage] is changed in local.js
    $find = '<div class="reveal video jfilm">';
    $count = substr_count($text, $find);
    for ($i = 0; $i < $count; $i++){
        $new = $template;
        // get old division
        $pos_start = mb_strpos($text,$find);
        $pos_end = mb_strpos($text, '</div>', $pos_start);
        $length = $pos_end - $pos_start + 6;  // add 6 because last item is 6 long
        $old = mb_substr($text, $pos_start, $length);
        $debug .=  "old is $old\n";
        //find Video Title and add phrase "Watch Online"
        $word = trim(strip_tags($old));
        $word = trim(strip_tags($old));
        $word = str_replace('&nbsp;', ' ', $word);
        $word = str_replace('%', $word, $watch_phrase);
        $new = str_replace('[id]', $i, $new);
        $new = str_replace('[Title]', $word, $new);
        // replace link  "https://api.arclight.org/videoPlayerUrl?refId=2_20615-Acts7306-0-0"
        $pos_vid_start = strpos($old, '-') ; 
        $pos_vid_end = strpos($old,'">' , $pos_vid_start);
        $vid_length = $pos_vid_end - $pos_vid_start + 0; // add 0 because we don't want any of that
        $video = '[jfilm]' . mb_substr($old, $pos_vid_start, $vid_length); //-Acts7306-0-0
        $new = str_replace('[Video]', $video, $new); 
        $debug .=  "word is $word\n";
        $debug .=  "new is $new\n";
        
         // from https://stackoverflow.com/questions/1252693/using-str-replace-so-that-it-only-acts-on-the-first-match
         // added these because we are not using a multi-bite function below.
        $pos_start = strpos($text,$find);
        $pos_end = strpos($text, '</div>', $pos_start);
        $length = $pos_end - $pos_start + 6;  // add 6 because last item is 6 long
        $text = substr_replace($text, $new, $pos_start, $length);
    }
    $out = $text;
    return $out;
}
function revealVideoLumo($text, $bookmark){
    
    $debug = 'In revealVideoLumo' . "\n";;
    $watch_phrase = $bookmark['language']->watch;
    $template= '<button id="LumoButton[id]" type="button" class="external-movie lumo">[Title]</button>
        <div class="collapsed">[Video]</div>
        <div id="ShowOptionsFor[Video]"></div>' ;
    // [ChangeLanguage] is changed in videos.js
    $find = '<div class="reveal video lumo">';
    $count = substr_count($text, $find);
    for ($i = 0; $i < $count; $i++){
        $new = $template;
        // get old division
        $pos_start = mb_strpos($text,$find);
        $pos_end = mb_strpos($text, '</div>', $pos_start);
        $length = $pos_end - $pos_start + 6;  // add 6 because last item is 6 long
        $old = mb_substr($text, $pos_start, $length);
        $debug .=  "old is $old\n";
        //find Video Title and add phrase "Watch Online"
        $word = trim(strip_tags($old));
        $word = trim(strip_tags($old));
        $word = str_replace('&nbsp;', ' ', $word);
        $word = str_replace('%', $word, $watch_phrase);
        $new = str_replace('[id]', $i, $new);
        $new = str_replace('[Title]', $word, $new);
        // replace link  "https://api.arclight.org/videoPlayerUrl?refId=2_20615-Acts7306-0-0"
        $pos_vid_start = strpos($old, '-') ; 
        $pos_vid_end = strpos($old,'">' , $pos_vid_start);
        $vid_length = $pos_vid_end - $pos_vid_start + 0; // add 0 because we don't want any of that
        $video = '[lumo]' . mb_substr($old, $pos_vid_start, $vid_length); //-Acts7306-0-0
        $new = str_replace('[Video]', $video, $new); 
        $debug .=  "word is $word\n";
        $debug .=  "new is $new\n";
        
         // from https://stackoverflow.com/questions/1252693/using-str-replace-so-that-it-only-acts-on-the-first-match
         // added these because we are not using a multi-bite function below.
        $pos_start = strpos($text,$find);
        $pos_end = strpos($text, '</div>', $pos_start);
        $length = $pos_end - $pos_start + 6;  // add 6 because last item is 6 long
        $text = substr_replace($text, $new, $pos_start, $length);
    }
    $out = $text;
    return $out;
}


function revealVideoVimeo($text, $bookmark){
    
    $debug = 'In revealVideoJFilm' . "\n";;
    $watch_phrase = $bookmark['language']->watch;
    $template= '<button id="VimeoButton[id]" type="button" class="external-movie ">[Title]</button>
        <div class="collapsed">[Video]</div>' ;
    $find = '<div class="reveal video vimeo">';
    $count = substr_count($text, $find);
    for ($i = 0; $i < $count; $i++){
       
        // get old division
        $pos_start = mb_strpos($text,$find);
        $pos_end = mb_strpos($text, '</div>', $pos_start);
        $length = $pos_end - $pos_start + 6;  // add 6 because last item is 6 long
        $old = mb_substr($text, $pos_start, $length);
        $debug .=  "old is $old\n";
        //find Video Title and add phrase "Watch Online"
        $word = trim(strip_tags($old));
        $word = trim(strip_tags($old));
        $word = str_replace('&nbsp;', ' ', $word);
        $word = str_replace('%', $word, $watch_phrase);
        $word = str_replace("\n", '', $word);
        $word = str_replace("\r", '', $word);
        // 
        $new = $template;
        $new = str_replace('[id]', $i, $new);
        $new = str_replace('[Title]', $word, $new);
        // replace link  "href="https://vimeo.com/162977296">"
        $pos_vid_start = strpos($old, 'vimeo.com/') ; 
        $pos_vid_end = strpos($old,'">' , $pos_vid_start);
        $vid_length = $pos_vid_end - $pos_vid_start -10; //  because we don't want any of that 'vimeo.com/'
        $video = '[vimeo]' . mb_substr($old, $pos_vid_start +10 , $vid_length); //-Acts7306-0-0
        $new = str_replace('[Video]', $video, $new); 
        $debug .=  "word is | $word |\n";
        $debug .=  "new is | $new |\n";
        
         // from https://stackoverflow.com/questions/1252693/using-str-replace-so-that-it-only-acts-on-the-first-match
         // added these because we are not using a multi-bite function below.
        $pos_start = strpos($text,$find);
        $pos_end = strpos($text, '</div>', $pos_start);
        $length = $pos_end - $pos_start + 6;  // add 6 because last item is 6 long
        $text = substr_replace($text, $new, $pos_start, $length);
    }
    $out = $text;
    //writeLog('revealVideoVimeo', $debug);
    return $out;
}