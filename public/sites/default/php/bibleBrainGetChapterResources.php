<?php
myRequireOnce('bibleBrainGet.php');
 // this gives you text of the Bible (fileset setlected);
// "\/bibles\/filesets\/{fileset_id}\/{book}\/{chapter}": {
function bibleBrainGetChapterResources($p){
    $p['fileset']= 'AMHEVG';
    $p['book']= 'MAT';
    $p['chapter'] = 2;
	$output = '';
    $url = 'https://4.dbt.io/api/bibles/filesets/';
    $url .= $p['fileset'] .'/'. $p['book'] . '/'. $p['chapter'] . '?';
    writeLogDebug('bibleBrainGetChapterResources-12', $url);
    $response =  bibleBrainGet($url);
    $output = $response;
	return $output;
}