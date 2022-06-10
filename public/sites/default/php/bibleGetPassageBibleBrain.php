<?php
/*returns an array:
    $output['content']= [
		'reference' =>  $output['passage_name'],
		'text' => $output['bible'],
		'link' => $output['link']
	];
*/

function bibleGetPassageBibleBrain($p){
	$output = [];
	$output['content']=[];
	writeLogDebug('bibleGetPassageBibleBrain-4', $p);
	$fileset = substr($p['damId'], 0,6);
    $url = 'https://4.dbt.io/api/bibles/filesets/';
    $url .=  $fileset .'/'. $p['bookId'] . '/'. $p['chapterId'] .'?';
    $url .= 'verse_start='. $p['verseStart']. '&verse_end='.$p['verseEnd'] .'&v=4&key=';
	writeLogDebug('bibleGetPassageBibleBrain-7', $url);
    $response =  bibleBrainGet($url);
	$verses = $response->data;
    $text = '';
    foreach ($verses as $verse){
        $text .= '<sup class="versenum">'. $verse->verse_start .'</sup>';
        $text .=  $verse->verse_text .' ';
    }
	//https://live.bible.is/bible/AMHEVG/MAT/1
	$output['content']['link']= 'https://live.bible.is/bible/'. $fileset . '/'.$p['bookId'].'/'.$p['chapterId'];
	$output['content']['reference'] = $p['entry'];
	$output['content']['text'] = $text;
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
