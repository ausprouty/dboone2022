<?php

myRequireOnce('bibleBrainGet.php');

//https://4.dbt.io/api/bibles/filesets/:filesetid/:bookid/:chapterid?v=4
 /*$dbt_array = array(
        'entry' => $passage,
        'bookId' => $book_details['book_id'],
        'bookNumber'=> $book_details['book_number'],
        'bookLookup'=> $book_lookup,
        'chapterId' => $chapterId,
        'verseStart' => $verseStart,
        'verseEnd' => $verseEnd,
        'collection_code' => $book_details['testament'],
    );

    */
function bibleBrainGetVideoPlaylist($p){
    $output= [];
    if (!isset($p['fileset'])){
      $p['fileset'] = bibleBrainGetVideoFilesetForLanguage($p);
    }
    //https://4.dbt.io/api/bibles/filesets/:filesetid/:bookid/:chapterid?v=4
    $url = 'https://4.dbt.io/api/bibles/filesets/';
    $url .=  $p['fileset'] . '/'.$p['bookId'] . '/'.$p['chapterId'] . '?';
    writeLogDebug('bibleBrainGetVideo-32', $url);
    $response =  bibleBrainGet($url);
    if (isset($response->data)){
      $videos =  $response->data;
      foreach ($videos as $video){
        if ($video->verse_end >= $p['verseStart'] && $video->verse_start <= $p['verseEnd']){
        $output[] =$video->path;
        }
      }
    }
    return $output;
}


function bibleBrainGetVideoFilesetForLanguage($p){
  // find video fileset for this language
    $url = 'https://4.dbt.io/api/bibles?language_code=';
    $url .=  $p['language_iso'] ;
    $url .= '&page=1&limit=25&';
    $response = bibleBrainGet($url);
    $bibles =$response->data;
    writeLogDebug('bibleBrainGetVideoFilesetForLanguage-45', $bibles);
    $dbp_vid ='dbp-vid';
    foreach($bibles as $bible){
      if (isset($bible->filesets->$dbp_vid)){
        $filesets = $bible->filesets->$dbp_vid;
        foreach ($filesets as $fileset){
          $output = $fileset->id;
        }
      }
    }
    return $output;
}