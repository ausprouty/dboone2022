<?php

myRequireOnce ('dirMake.php');
myRequireOnce ('writeLog.php');
myRequireOnce ('modifyRevealVideo.php');
myRequireOnce ('videoFindForSDCardNewName.php', 'sdcard');
myRequireOnce ('audioMakeRefFileForSDCard.php', 'sdcard');
myRequireOnce ('videoReference.php', 'sdcard');


function videoMakeBatFileForSDCard($p){
   audioMakeRefFileForSDCard($p);
   $output = 'mkdir video' . "\n";
   $output .= 'cd video' . "\n";
   $output .= 'mkdir ' . $p['folder_name'] . "\n";
   $output .= 'cd ..' . "\n";
   $series_videos = [];
   $chapter_videos = [];

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
            if (!isset($chapter->prototype)){
                writeLogAppend('videoMakeBatFileForSDCard', $chapter );
            }
            elseif ($chapter->prototype){
                $chapter_videos = videoFindForSDCard($p, $chapter->filename);
                $count= count($chapter_videos);
                //writeLog('videoMakeBatFileForSDCard-32-count-'. $chapter->filename , $count);
                 if ($count == 1){
                     $dir = 'video/'. $p['folder_name'];
                    $output .= videoMakeBatFileForSDCardSingle($chapter_videos[0], $dir);
                }
                if ($count > 1){
                    $output .= videoMakeBatFileForSDCardConcat($chapter_videos,  $p, $chapter->filename);
                }
                videoMakeBatFileToCheckSource($chapter_videos, $p );
            }
        }
    }
    videoMakeBatFileForSDCardWrite($output, $p);
    return $output;
}
function videoMakeBatFileForSDCardSingle($video, $dir){
    $output = '';
    $template_with_end = 'ffmpeg  -accurate_seek -i [old_name] -ss [start] -to [end]   -vf scale=[width]:-1  [dir]/[new_name].mp4' ;
    $template_without_end = 'ffmpeg  -accurate_seek -i [old_name] -ss [start]  -vf scale=[width]:-1    [dir]/[new_name].mp4';
    if (isset($video['download_name'])){
        $placeholders = array(
                '[old_name]',
                '[start]',
                '[end]',
                '[width]',
                '[new_name]',
                '[dir]'
            );
            $replace = array(
                $video['download_name'],
                $video['start_time'],
                $video['end_time'],
                VIDEO_WIDTH,
                $video['new_name'],
                $dir
            );
            if ($video['end_time'] == NULL){
                $template = $template_without_end;
            }
            else{
                $template = $template_with_end;
            }
            $output = str_replace($placeholders, $replace,  $template) . "\n";
    }

    return $output;
}
function videoMakeBatFileForSDCardConcat($chapter_videos, $p,  $filename){
    // see https://trac.ffmpeg.org/wiki/Concatenate#samecodec
    $output ='';
    $template = 'ffmpeg -f concat -safe 0 -i concat/[list_name] -c copy video/[dir]/[new_name].mp4';
    $file_list = '';
    $dir='concat';
    foreach ($chapter_videos as $video){
        $file_list .= 'file '. $video['new_name']  .'.mp4' ."\n";
        $output .= videoMakeBatFileForSDCardSingle($video, $dir);
    }
    videoMakeBatFileForSDCardWriteConcat($file_list, $p, $filename);
    $placeholders = array(
        '[list_name]',
        '[width]',
        '[dir]',
        '[new_name]'
    );
    $list_name = $filename . '.txt';
    $new_name = videoFindForSDCardNewName($filename);
    $replace = array(
        $list_name,
        VIDEO_WIDTH,
        $p['folder_name'],
        $new_name,

    );
    $concat = str_ireplace ($placeholders,  $replace, $template);
    $output.= $concat . "\n";
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
function videoMakeBatFileForSDCardWriteConcat($text, $p, $filename){
    //define("ROOT_EDIT", '/home/vx5ui10wb4ln/public_html/myfriends.edit/');
    //writeLogDebug('videoMakeBatFileForSDCardWriteConcat-123-p', $p);
    $dir = ROOT_EDIT  . 'sites/' . SITE_CODE  . '/sdcard/' . $p['country_code'] . '/' . $p['language_iso']  .'/concat/';
    dirMake($dir);
    $filename=  $filename . '.txt';
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
    //todo clean this
    $chapter_videos = [];

    //writeLog('videoFindForSDCard-113-p', $p);
    //writeLog('videoFindForSDCard-114-filename', $filename);
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
    if (!isset($data['text'])){
        writeLogError ('videoFindForSDCard -'. $filename, $sql);
        return $chapter_videos;

    }
    $text= $data['text'];
    ////writeLog('videoFindForSDCard-76-'. $filename, $text);
    $find = '<div class="reveal film">';
    $count = substr_count($text, $find);
    //writeLog('videoFindForSDCard-140-count', $count);
    $offset = 0;
    for ($i = 0; $i < $count; $i++){
        // get old division
        $pos_start = strpos($text,$find, $offset);
        $pos_end = strpos($text, '</div>', $pos_start);
        $offset = $pos_end ;
        $length = $pos_end - $pos_start + 6;  // add 6 because last item is 6 long
        $old = substr($text, $pos_start, $length);
        // find title_phrase
        $video['title'] = modifyVideoRevealFindText($old, 2);
        //find url
        $url = modifyVideoRevealFindText($old, 4);
        $arc = 'api.arclight.org';
        ////writeLog('videoFindForSDCard-95-'. $filename . $count, $url . "\n" . $find);

        if (strpos($url, $arc)){
            $url = str_ireplace('https://api.arclight.org/videoPlayerUrl?refId=', '', $url);
            ////writeLog('videoFindForSDCard-99-'. $filename . $count, $url);
            $start = strpos($url, '-') +1;
            $url= substr($url, $start);
            if (isset($videoReference[$url])){
                 $video['download_name'] = $videoReference[$url];
            }
            else{
                $video['download_name'] = NULL;
                $message = 'Download name not found for ' . $url;
                writeLogError('videoFindForSDCard-216-'. $p['language_iso'] .'-'. $filename, $message );
            }
        }
        else{
            if (isset($videoReference[$url])){
                 $video['download_name'] = $videoReference[$url];
            }
            else{
                $video['download_name'] = NULL;
                 $message = 'Download name not found for ' . $url;
                writeLogError('videoFindForSDCard-226-'. $p['language_iso'] .'-'.  $filename, $message );
            }
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
    //writeLog('videoFindForSDCard-185-chaptervideos', $chapter_videos);
    return $chapter_videos;
}

function  videoMakeBatFileToCheckSource($chapter_videos, $p ){
    $output ='';
    $dir = ROOT_EDIT  . 'sites/' . SITE_CODE  . '/sdcard/' . $p['country_code'] . '/' . $p['language_iso'] . '/';
    dirMake($dir);
    $filename = 'CheckSource-' . $p['folder_name'] . '.txt';
    foreach ($chapter_videos as $video){
        $output .= $video['download_name'] . "\n";
    }
    $fh =  $dir. $filename;
    file_put_contents($fh, $output,  FILE_APPEND | LOCK_EX );
    return;
}
