<?php
myRequireOnce('bibleBrainGet.php');

// "\/bibles\/filesets\/{fileset_id}\/{book}\/{chapter}": {
function bibleBrainGetChapterResources($p){
    $p['fileset']= 'AMHEVG';
    $p['book']= 'MAT';
    $p['chapter'] = 2;
	$output = '';
    $url = 'https://4.dbt.io/api/bibles/filesets';
    $url .= $p['fileset'] .'/'. $p['book'] . '/'. $p['chapter'] . '?';
    $response =  bibleBrainGet($url);
    $output = $response;
	return $output;
}