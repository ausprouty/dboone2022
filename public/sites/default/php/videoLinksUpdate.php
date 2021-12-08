<?php
myRequireOnce('create.php');
myRequireOnce('getContentByRecnum.php');
myRequireOnce('getLatestContent.php');

function videoLinksUpdate($p){
    $out = [];
    $out['debug'] = 'I was in updateVideoLinks'. "\n";
    $p['scope'] = 'page';
    $res = getContentByRecnum($p);
    if ($res['debug']){
        $out['debug'] .= $res['debug'];
    }
    $text = $res['content']['text'];
    if (strpos($text, '<div class="reveal video') !== FALSE){
        $find = '<div class="reveal video';
        $response=  videoLinksFix($text, $find);
        $text = $response['content'];
        $out['debug'] .= $response['debug'];
    }
    $res['content'] ['text']= $text;
    createContent($res['content']);
    $res['content']['scope'] = 'page';
    unset($res['recnum']);
    $res = getLatestContent($res['content']);
    if ($res['debug']){
        $out['debug'] .= $res['debug'];
    }
    $out['content'] = $res['content'];
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
        $out['debug'] .=  "old is | $old |\n";
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
        $out['debug'] .=  "word is | $word |\n";
        $out['debug'] .=  "new is | $new |\n";
         // from https://stackoverflow.com/questions/1252693/using-str-replace-so-that-it-only-acts-on-the-first-match
        $text = substr_replace($text, $new, $pos_start, $length);
    }
    $out['content'] = $text;
    return $out;

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