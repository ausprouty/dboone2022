<?php
myRequireOnce ('create.php');
myRequireOnce ('getContentByRecnum.php');
myRequireOnce ('getLatestContent.php');

function videoLinksUpdate($p){

    $debug = 'I was in updateVideoLinks'. "\n";
    $p['scope'] = 'page';
    $content = getContentByRecnum($p);

    $text = $content['text'];
    if (strpos($text, '<div class="reveal video') !== FALSE){
        $find = '<div class="reveal video';
        $text =  videoLinksFix($text, $find);
    }
    $content ['text']= $text;
    createContent($content );
    $content ['scope'] = 'page';
    unset($content ['recnum']);
    $out = getLatestContent($content );
    return $out;
}
/* <div class="reveal video">&nbsp;
<hr /><a href="https://api.arclight.org/videoPlayerUrl?refId=6_529-GOJohnEnglish4724">John 10:22-42</a>
*/
function videoLinksFix($text, $find){

    $count = substr_count($text, $find);
    for ($i = 0; $i < $count; $i++){
        $new = videoLinksTemplate();
        // get old division
        $pos_start = strpos($text,$find);
        $pos_end = strpos($text, '</div>', $pos_start);
        $length = $pos_end - $pos_start + 6;  // add 6 because last item is 6 long
        $old = substr($text, $pos_start, $length);
        $debug .=  "old is | $old |\n";
        //find Video Title
        $word = trim(strip_tags($old));
        $word = trim(strip_tags($old));
        $word = str_replace('&nbsp;', ' ', $word);
        $word = str_replace("\n", '', $word);
        $word = trim(str_replace("\r", '', $word));
        $new = str_replace('[Title]', $word, $new);
        // find video link
        $link_start = strpos($text, 'href="', $pos_start) + 6;
        $link_end = strpos($text, '"', $link_start);
        $link_length = $link_end - $link_start;
        $link = substr($text, $link_start, $link_length);
        $new = str_replace('[Link]', $link, $new);
        $debug .=  "word is | $word |\n";
        $debug .=  "new is | $new |\n";
         // from https://stackoverflow.com/questions/1252693/using-str-replace-so-that-it-only-acts-on-the-first-match
        $text = substr_replace($text, $new, $pos_start, $length);
    }

    return $text;

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