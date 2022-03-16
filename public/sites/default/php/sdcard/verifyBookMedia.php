<?php
/*
Looking for
 <video id="video"  width = "100%" controls>
        <source src="../../../../media/M2/spa/video/multiply1/102.mp4" type="video/mp4">
        see if directory exists on SD Card -- if not, copy media there.
        pull each html file
        find the videos in it
        see if it exists on directory
    $p['dir_sdcard'] = ROOT_SDCARD . _verifyBookClean($p['sdcard_settings']->subDirectory) .'/';
    $p['dir_video_list'] = ROOT_EDIT . 'sites/' . SITE_CODE .'/sdcard/' .$p['country_code'] .'/'. $p['language_iso'] .'/';
    $p['dir_series'] =  $p['country_code'] .'/'. $p['language_iso'] . '/'. $p['folder_name'];
*/
myRequireOnce('writeLog.php');
myRequireOnce('verifyBookDir.php', 'sdcard');
myRequireOnce('dirListFiles.php');
myRequireOnce('dirMake.php');

function verifyBookMedia($p){
   $output = [];
   $p = verifyBookDir($p);

   $dir_series_content = $p['dir_sdcard'].'folder/content/'.$p['dir_series'] .'/';
   $content_files =dirListFiles($dir_series_content);
   foreach ($content_files as $file_name){
      $ok = verifyBookMediaVideos($file_name, $p);
      if (!$ok){
         $output[] =$file_name . "\n";
      }
      $ok = verifyBookMediaAudio($file_name, $p);
      if (!$ok){
         $output[] =$file_name . "\n";
      }
    }
    if (count ($output) > 0){
        writeLogAppend('ERROR- verifyBookMedia', $output);
        return 'error';
    }
    return 'done';
}
/*   <video id="video"  width = "100%" controls>
        <source src="../../../../media/M2/spa/video/multiply1/102.mp4" type="video/mp4">
*/
function verifyBookMediaVideos($file_name, $p){
   $ok = true;
    $file = $p['dir_sdcard'].'folder/content/'.$p['dir_series'] .'/' . $file_name;
    $sd_media_location = $p['dir_sdcard'] . 'folder/media/'. SITE_CODE .'/';
    $dir_media_bank = ROOT_EDIT . 'sites/' . SITE_CODE.'/';
    $text= file_get_contents($file);
    $find = '<video id="video"';
    $count = substr_count($text, $find);
    $pos_start = 0;
    for ($i = 0; $i < $count; $i++){
        $pos_start = strpos($text,$find);
        $pos_video_start= strpos($text, '/media/', $pos_start);
        $pos_video_end= strpos($text, '"',  $pos_video_start);
        $length = $pos_video_end - $pos_video_start;  // add 6 because last item is 6 long
        $video_name = rtrim(substr($text, $pos_video_start, $length));
        $sd_video = $sd_media_location . $video_name;
        if (!file_exists( $sd_video)){
            dirMake($sd_video);
            $media_bank_file =  $dir_media_bank . $video_name;
            if (file_exists($media_bank_file)){
                copy($media_bank_file,  $sd_video);
            }
            else{
               $ok = false;
               writeLogAppend('ERROR-verifyBookMediaVideos-64', "$media_bank_file not found");
            }
        }
    }
    return $ok;
}

function verifyBookMediaAudio($file){
    return true;

}