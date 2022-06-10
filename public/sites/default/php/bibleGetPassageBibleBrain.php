<?php

function bibleGetPassageBibleBrain($p){
	$dbt = $p['dbt'];
	writeLogDebug('bibleGetPassageBibleBrain-4', $dbt);
    $url = 'https://4.dbt.io/api/bibles/filesets/';
    $url .= $dbt->damId .'/'. $dbt->bookId . '/'. $dbt->chapterId .'?';
    $url .= 'verse_start='. $dbt->verseStart. '&verse_end='.$dbt->verseEnd .'&v=4&key=';
	writeLogDebug('bibleGetPassageBibleBrain-7', $url);
    $response =  bibleBrainGet($url);
	$verses = $response->data;
    $output = '';
    foreach ($verses as $verse){
        $output .= '<sup class="versenum">'. $verse->verse_start .'</sup>';
        $output .=  $verse->verse_text .' ';
    }
	return $output;
}

function bibleBrainGet($url){
    $key = '1462b719-42d8-0874-7c50-905063472458';
    $curl = curl_init();
	curl_setopt_array($curl, array(
	  CURLOPT_URL => $url . $key,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'GET',
	));
	$response = json_decode(curl_exec($curl));
    return $response;

}
function bibleBrainGetBibles($language_iso){
	$output = '';
	$count = 0;
    $url = 'https://4.dbt.io/api/bibles?language_code=' . $language_iso . '&v=4&key=';
	$response =  bibleBrainGet($url);
	$resources = $response->data;
	$dbp_prod = 'dbp-prod';
	$dbp_vid ='dbp-vid';
	foreach ($resources as $resource){
		$output .= $resource->abbr . ': '. $resource->vname . '(' . $resource->name. ')<br>';
		if (isset($resource->filesets->$dbp_prod)){
			$items = $resource->filesets->$dbp_prod;
			foreach ( $items as $item){
				$output .= '----------' .$item->id . '(' . $item->type. ')'. $item->size . '<br>';
			}
		}
		if (isset($resource->filesets->$dbp_vid)){
			$items = $resource->filesets->$dbp_vid;
			foreach ( $items as $item){
				$output .= '----------' .$item->id . '(' . $item->type. ')'. $item->size . '<br>';
			}
		}
	}

	return $output;
}
