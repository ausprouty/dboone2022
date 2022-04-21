<?php

myRequireOnce ('dirMake.php');
myRequireOnce ('writeLog.php');
myRequireOnce ('modifyRevealVideo.php');
myRequireOnce ('videoFindForApkNewName.php', 'apk');
myRequireOnce ('audioMakeRefFileForApk.php', 'apk');
myRequireOnce ('videoReference.php', 'apk');
myRequireOnce('videoFollows.php', 'apk');


function videoMakeBatFileForApk($p){
   audioMakeRefFileForApk($p);
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
                writeLogAppend('videoMakeBatFileForApk', $chapter );
            }
            elseif ($chapter->prototype){
                $chapter_videos = videoFindForApk($p, $chapter->filename);
                writeLogAppend('videoMakeBatFileForApk-37', $chapter_videos);
                $count= count($chapter_videos);
                //writeLog('videoMakeBatFileForApk-32-count-'. $chapter->filename , $count);
                $dir = 'video/'. $p['folder_name'];
                 if ($count == 1){

                    $output .= videoMakeBatFileForApkSingle($chapter_videos[0], $dir);
                }
                if ($count > 1){
                    $output .= videoMakeBatFileForApkConsiderConcat($chapter_videos,  $p, $chapter->filename, $dir);
                }
                videoMakeBatFileToCheckSource($chapter_videos, $p );
            }
        }
    }
    videoMakeBatFileForApkWrite($output, $p);
    return $output;
}
function videoMakeBatFileForApkSingle($video, $dir){
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

function videoMakeBatFileForApkConsiderConcat($chapter_videos,  $p, $filename, $dir){
    $output = '';
    $concat= [];
    // find out if videos are a sequence
    foreach($chapter_videos as $key=>$video){
        if ($video['follows']){
            $previous = $key -1;
            $chapter_videos[$previous]['preceeds'] = $video['url'];
        }
    }
    writeLogAppend('videoMakeBatFileForApkConsiderConcat-97',$chapter_videos);
    foreach($chapter_videos as $key=>$video){
        if (!$video['follows'] && !isset($video['preceeds'])){
            $output .= videoMakeBatFileForApkSingle($video, $dir);
        }
        if (isset($video['preceeds']) && !$video['follows']){
            writeLogAppend('videoMakeBatFileForApkConsiderConcat-104', $video);
           $concat= [];
           $concat[] = $video;
           $next_video = $video['preceeds'];
        }
        if ($video['follows']){
             writeLogAppend('videoMakeBatFileForApkConsiderConcat-110', $video);
              writeLogAppend('videoMakeBatFileForApkConsiderConcat-111', $next_video);
            if ($video['url'] == $next_video){
                $concat[] = $video;
                writeLogAppend('videoMakeBatFileForApkConsiderConcat-114', $concat);
                if (!isset($video['proceeds'])){
                    writeLogAppend('videoMakeBatFileForApkConsiderConcat-116', $concat);
                    $output .= videoMakeBatFileForApkConcat($concat, $p,  $filename);
                    $concat = [];
                }
                else{
                    $next_video = $video['preceeds'];
                }
            }
        }
    }
    if(count($concat)>1){
        writeLogAppend('videoMakeBatFileForApkConsiderConcat-125', $concat);
        $output .= videoMakeBatFileForApkConcat($concat, $p,  $filename);

    }
    return $output;
}

function videoMakeBatFileForApkConcat($chapter_videos, $p,  $filename){
    // see https://trac.ffmpeg.org/wiki/Concatenate#samecodec
    $output ='';
    $template = 'ffmpeg -f concat -safe 0 -i concat/[list_name] -c copy video/[dir]/[new_name].mp4';
    $file_list = '';
    $dir='concat';
    foreach ($chapter_videos as $video){
        $file_list .= 'file '. $video['new_name']  .'.mp4' ."\n";
        $output .= videoMakeBatFileForApkSingle($video, $dir);
    }
    videoMakeBatFileForApkWriteConcat($file_list, $p, $filename);
    $placeholders = array(
        '[list_name]',
        '[width]',
        '[dir]',
        '[new_name]'
    );
    $list_name = $filename . '.txt';
    $new_name = videoFindForApkNewName($filename);
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

function videoMakeBatFileForApkWrite($text, $p){
    //define("ROOT_EDIT", '/home/vx5ui10wb4ln/public_html/myfriends.edit/');
     $dir = ROOT_EDIT  . 'sites/' . SITE_CODE  . '/apk/' . $p['country_code'] . '/' . $p['language_iso'] . '/';
     dirMake($dir);
     $filename=  $p['folder_name'] . '.bat';
    $fh = fopen( $dir. $filename, 'w');
	fwrite($fh, $text);
    fclose($fh);
    return;

}
function videoMakeBatFileForApkWriteConcat($text, $p, $filename){
    //define("ROOT_EDIT", '/home/vx5ui10wb4ln/public_html/myfriends.edit/');
    //writeLogDebug('videoMakeBatFileForApkWriteConcat-123-p', $p);
    $dir = ROOT_EDIT  . 'sites/' . SITE_CODE  . '/apk/' . $p['country_code'] . '/' . $p['language_iso']  .'/concat/';
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
function videoFindForApk($p, $filename){
    //todo clean this
    $chapter_videos = [];

    //writeLog('videoFindForApk-113-p', $p);
    //writeLog('videoFindForApk-114-filename', $filename);
    // find chapter that has been prototyped
    $chapter_videos = [];
    $videoReference = videoReference();
    $video = [];
    $video['filename'] =$filename;
    $new_name= videoFindForApkNewName($filename);
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
        writeLogError ('videoFindForApk -'. $filename, $sql);
        return $chapter_videos;

    }
    $text= $data['text'];
    ////writeLog('videoFindForApk-76-'. $filename, $text);
    $find = '<div class="reveal film">';
    $count = substr_count($text, $find);
    //writeLog('videoFindForApk-140-count', $count);
    $offset = 0;
    $previous_url = NULL;
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
        ////writeLog('videoFindForApk-95-'. $filename . $count, $url . "\n" . $find);

        if (strpos($url, $arc)){
            $url = str_ireplace('https://api.arclight.org/videoPlayerUrl?refId=', '', $url);
            ////writeLog('videoFindForApk-99-'. $filename . $count, $url);
            $start = strpos($url, '-') +1;
            $url= substr($url, $start);
            if (isset($videoReference[$url])){
                 $video['download_name'] = $videoReference[$url];
            }
            else{
                $video['download_name'] = NULL;
                $message = 'Download name not found for ' . $url;
                writeLogError('videoFindForApk-216-'. $p['language_iso'] .'-'. $filename, $message );
            }
        }
        else{
            if (isset($videoReference[$url])){
                 $video['download_name'] = $videoReference[$url];
            }
            else{
                $video['download_name'] = NULL;
                 $message = 'Download name not found for ' . $url;
                writeLogError('videoFindForApk-226-'. $p['language_iso'] .'-'.  $filename, $message );
            }
        }
        $video['url']= $url;
         // find start and end times
        $video['start_time'] = modifyVideoRevealFindTime ($old, 7);
        $video['end_time'] = modifyVideoRevealFindTime ($old, 9);
        //does this follow on from previous video? If so record $url
        $video['follows'] = videoFollows($previous_url, $url);
        $previous_url= $url;
        //if more than one video in this chapter
        if ($i > 0){
            $video['new_name'] = $new_name . '-' . $i;
        }
        $chapter_videos[] = $video;
    }
    //writeLog('videoFindForApk-185-chaptervideos', $chapter_videos);
    return $chapter_videos;
}

function  videoMakeBatFileToCheckSource($chapter_videos, $p ){
    $output ='';
    $dir = ROOT_EDIT  . 'sites/' . SITE_CODE  . '/apk/' . $p['country_code'] . '/' . $p['language_iso'] . '/';
    dirMake($dir);
    $filename = 'CheckSource-' . $p['folder_name'] . '.txt';
    foreach ($chapter_videos as $video){
        $output .= $video['download_name'] . "\n";
    }
    $fh =  $dir. $filename;
    file_put_contents($fh, $output,  FILE_APPEND | LOCK_EX );
    return;
}
