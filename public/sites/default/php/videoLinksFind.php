<?php
myRequireOnce ('writeLog.php');

function videoLinksFind($text, $filename){
    $find = '<div class="reveal film';
    $find_length = strlen($find);
    $count = substr_count($text, $find);
    $tab = "\t";
    $linefeed = "\n";
    $output = '';
    $pos_start = 0;
    for ($i= 1; $i <= $count; $i++){
        $debug = '';
        $output .= $filename . $tab;
        // get Title
        $pos_start = strpos($text, $find, $pos_start) +  $find_length;
        $debug .= 'pos_start: ' . $pos_start . $linefeed;
        $pos_title_label = strpos($text, 'Title:', $pos_start);
        $pos_title = strpos($text, '<td class="video"', $pos_title_label);
        $pos_title_start = strpos($text, '>', $pos_title) +1;
        $pos_title_end = strpos($text, '</td', $pos_title_start);
        $title_length = $pos_title_end -  $pos_title_start;
        $title = substr($text, $pos_title_start, $title_length);
        $output .= $title . $tab;

        // get URL
        $pos_url_label = strpos($text, 'URL:', $pos_title_end);
        $debug .= 'pos_url_label: ' . $pos_url_label . $linefeed;
        $pos_url = strpos($text, '<td class="video"', $pos_url_label);
        $pos_url_start = strpos($text, '>', $pos_url) +1;
        $pos_url_end = strpos($text, '</td', $pos_url_start);
        $url_length = $pos_url_end -  $pos_url_start;
        $url = substr($text, $pos_url_start, $url_length);
        $output .= $url . $tab;
        // get Start Time
        $pos_start_time_label = strpos($text, 'Start Time:', $pos_url_end);
        $debug .= 'pos_start_time_label: ' . $pos_start_time_label . $linefeed;
        $pos_start_time = strpos($text, '<td class="video"', $pos_start_time_label);
        $pos_start_time_start = strpos($text, '>', $pos_start_time) +1;
        $pos_start_time_end = strpos($text, '</td', $pos_start_time_start);
        $debug .= 'pos_start_time_end: ' . $pos_start_time_end . $linefeed;
        $start_time_length = $pos_start_time_end -  $pos_start_time_start;
        $start_time = substr($text, $pos_start_time_start, $start_time_length);
        $debug .= 'start_time: ' . $start_time . $linefeed;
        $output .= $start_time . $tab;
        // get Eng Time
        $pos_end_time_label = strpos($text, 'End Time:', $pos_start_time_end);
        $debug .= 'pos_end_time_label: ' . $pos_end_time_label . $linefeed;
        $pos_end_time = strpos($text, '<td class="video"', $pos_end_time_label);
        $pos_end_time_start = strpos($text, '>', $pos_end_time) +1;
        $pos_end_time_end = strpos($text, '</td', $pos_end_time_start);
        $end_time_length = $pos_end_time_end -  $pos_end_time_start;
        $end_time = substr($text, $pos_end_time_start, $end_time_length);
        $debug .= 'end_time: ' . $end_time . $linefeed;
        $output .= $end_time . $linefeed;
        // reset
        $pos_start = $pos_end_time_end;
        $debug .= $text;
        //writeLog('videoLinksFind-'  . '-'. $filename . '-'. $i, $debug);
    }
   
    return $output;

}


function videoLinksTemplate(){
    return '

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
}