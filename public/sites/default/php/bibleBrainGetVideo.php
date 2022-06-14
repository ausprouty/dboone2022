<?php

myRequireOnce('bibleGetPassageBibleBrain.php');

//https://4.dbt.io/api/bibles/filesets/:filesetid/:bookid/:chapterid?v=4
function bibleBrainGetVideo($p){

     $p['fileset']= 'AMHEVG';
     $p['bookId'] ='MRK';
     $p['chapterId'] =2;
	$output = '';
    $url = 'https://4.dbt.io/api/bibles/filesets/';
    $url .=  $p['fileset'] . '/'.$p['bookId'] . '/'.$p['chapterId'] . '/';
    $url .= 'v=4&key=';
    $response =  bibleBrainGet($url);
    return $response;
}