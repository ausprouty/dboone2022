<?php

myRequireOnce ('dirMake.php');
myRequireOnce ('writeLog.php');
myRequireOnce('modifyRevealVideo.php');
myRequireOnce('videoFindForSDCardNewName.php');
myRequireOnce('audioMakeRefFileForSDCard.php');


function videoMakeBatFileForSDCard($p){
   audioMakeRefFileForSDCard($p);
   $output = '';
   $series_videos = [];
   $chapter_videos = [];
   // this allows me to include a different file for each language.
   myRequireOnce('videoReference.php',  $p['language_iso']);
 //find series data that has been prototyped
    $sql = "SELECT * FROM content
        WHERE  country_code = '". $p['country_code'] ."'
        AND  language_iso = '". $p['language_iso'] ."'
        AND folder_name = '" .$p['folder_name'] ."'  AND filename = 'index'
        AND prototype_date IS NOT NULL
        ORDER BY recnum DESC LIMIT 1";
    $data = sqlArray($sql);
    // find chapters that have been prototyped
    $text = json_decode($data['text']);
    if (isset($text->chapters)){
        foreach ($text->chapters as $chapter){
            if ($chapter->prototype){
                $chapter_videos = videoFindForSDCard($p, $chapter->filename);
                if (count($chapter_videos) > 0){
                    foreach ($chapter_videos as $chapter_video){
                      array_push($series_videos, $chapter_video);
                   }
                }
            }
        }
    }
   // writeLog('videoMakeBatFileForSDCard-35-chapter_videos', $series_videos);
    // create file
    $template_with_end = 'ffmpeg  -accurate_seek -i [old_name].mp4 -ss [start] -to [end]   -vf scale=[width]:-1  [width]/[new_name].mp4' ;
    $template_without_end = 'ffmpeg  -accurate_seek -i [old_name].mp4 -ss [start]  -vf scale=[width]:-1    [width]/[new_name].mp4';
    foreach ($series_videos as $video){
        if ($video['download_name']){
            $placeholders = array(
                    '[old_name]',
                    '[start]',
                    '[end]',
                    '[width]',
                    '[new_name]'
                );
                $replace = array(
                    $video['download_name'],
                    $video['start_time'],
                    $video['end_time'],
                    VIDEO_WIDTH,
                    $video['new_name']
                );
                if ($video['end_time'] == NULL){
                    $template = $template_without_end;
                }
                else{
                    $template = $template_with_end;
                }
                $output .= str_replace($placeholders, $replace,  $template) . "\n";
        }
    }
    videoMakeBatFileForSDCardWrite($output, $p);
    return $output;
}

function videoMakeBatFileForSDCardWrite($text, $p){
    //define("ROOT_EDIT", '/home/vx5ui10wb4ln/public_html/myfriends.edit/');
     $dir = ROOT_EDIT  . 'sites/' . SITE_CODE  . '/sdcard/' . $p['country_code'] . '/' . $p['language_iso'] . '/';
     dirMake($dir);
     $filename=  $p['folder_name'] . '.bat';
    $fh = fopen( $dir. $filename, 'w');
	fwrite($fh, $text);
    fclose($fh);
    return;

}
/*Input is:
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
*/
function videoFindForSDCard($p, $filename){
    writeLog('videoFindForSDCard-113-p', $p);
    writeLog('videoFindForSDCard-114-filename', $filename);
    // find chapter that has been prototyped
    $chapter_videos = [];
    $videoReference = videoReference();
    $video = [];
    $video['filename'] =$filename;
    $new_name= videoFindForSDCardNewName($filename);
    $video['new_name'] = $new_name;
    $sql = "SELECT * FROM content
        WHERE  country_code = '". $p['country_code'] ."'
        AND  language_iso = '". $p['language_iso'] ."'
        AND folder_name = '" .$p['folder_name'] ."'
        AND filename = '" . $filename . "'
        AND prototype_date IS NOT NULL
        ORDER BY recnum DESC LIMIT 1";
    $data = sqlArray($sql);
    $text= $data['text'];
    //writeLog('videoFindForSDCard-76-'. $filename, $text);
    $find = '<div class="reveal film">';
    $count = substr_count($text, $find);
    $offset = 0;
    for ($i = 0; $i < $count; $i++){
        // get old division
        $pos_start = strpos($text,$find, $offset);
        $pos_end = strpos($text, '</div>', $pos_start);
        $offset = $pos_end + 1;
        $length = $pos_end - $pos_start + 6;  // add 6 because last item is 6 long
        $old = substr($text, $pos_start, $length);
        // find title_phrase
        $video['title'] = modifyVideoRevealFindText($old, 2);
        //find url
        $url = modifyVideoRevealFindText($old, 4);
        $find = 'api.arclight.org';
        //writeLog('videoFindForSDCard-95-'. $filename . $count, $url . "\n" . $find);

        if (strpos($url, $find)){
            $url = str_ireplace('https://api.arclight.org/videoPlayerUrl?refId=', '', $url);
            //writeLog('videoFindForSDCard-99-'. $filename . $count, $url);
            $start = strpos($url, '-') +1;
            $url= substr($url, $start);
            if ($videoReference[$url]){
                 $video['download_name'] = $videoReference[$url];
                if (strpos($video['download_name'], 'LUMO') !== FALSE){
                    $video['download_name'] ='lumo/'. $video['download_name'];
                }
                else{
                   $video['download_name'] ='acts/'. $video['download_name'];
                }
            }
            else{
                $video['download_name'] = NULL;
                $message = 'Download name not found for ' . $url;
                writeLogError('videoFindForSDCard-102-' . $url, $message );
            }
        }
        else{
            $video['download_name'] = NULL;
            $message = 'Download name not found for ' . $url;
            writeLogError('videoFindForSDCard-102-' . $url, $message );

        }
        $video['url']= $url;
         // find start and end times
        $video['start_time'] = modifyVideoRevealFindTime ($old, 7);
        $video['end_time'] = modifyVideoRevealFindTime ($old, 9);
        //if more than one video in this chapter
        if ($i > 0){
            $video['new_name'] = $new_name . '-' . $i;
        }
        $chapter_videos[] = $video;
    }
    writeLog('videoFindForSDCard-185-chaptervideos', $chapter_videos);
    return $chapter_videos;
}
