<?php

myRequireOnce('bibleBrainGet.php');

//https://4.dbt.io/api/bibles/filesets/:filesetid/:bookid/:chapterid?v=4
function bibleBrainGetVideo($p){

     $p['fileset']= 'HAEBSEP2DV';
     $p['bookId'] ='MRK';
     $p['chapterId'] =2;
	$output = '';
    $url = 'https://4.dbt.io/api/bibles/filesets/';
    $url .=  $p['fileset'] . '/'.$p['bookId'] . '/'.$p['chapterId'];;
    writeLogDebug('bibleBrainGetVideo-15', $url);
    $response =  bibleBrainGet($url);
    return $response;
}