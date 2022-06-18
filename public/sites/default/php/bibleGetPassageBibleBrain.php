<?php
/*returns an array:
    $output['content']= [
		'reference' =>  $output['passage_name'],
		'text' => $output['bible'],
		'link' => $output['link']
	];
*/
myRequireOnce('bibleBrainGet.php');

function bibleGetPassageBibleBrain($p){
	$output = [];
	$output['content']=[];
	writeLogDebug('bibleGetPassageBibleBrain-4', $p);
	$fileset = substr($p['damId'], 0,6);
    $url = 'https://4.dbt.io/api/bibles/filesets/';
    $url .=  $fileset .'/'. $p['bookId'] . '/'. $p['chapterId'] .'?';
    $url .= 'verse_start='. $p['verseStart']. '&verse_end='.$p['verseEnd'] .'&';
	writeLogDebug('bibleGetPassageBibleBrain-7', $url);
    $text =  bibleGetPassageBibleBrainText($url);
	if (isset($p['extraChapters'])){
		foreach ($p['extraChapters'] as $chapter){
			$url = 'https://4.dbt.io/api/bibles/filesets/';
			$url .=  $fileset .'/'. $p['bookId'] . '/'. $chapter['chapterId'] .'?';
			$url .= 'verse_start='. $chapter['verseStart']. '&verse_end='.$chapter['verseEnd'] .'&v=4&key=';
			$text .= '<sup class="chapternum">'.  $chapter['chapterId'] . ':</sup>';
			$text .=  bibleGetPassageBibleBrainText($url);
		}
	}
	//https://live.bible.is/bible/AMHEVG/MAT/1
	$output['content']['link']= 'https://live.bible.is/bible/'. $fileset . '/'.$p['bookId'].'/'.$p['chapterId'];
	$output['content']['reference'] = $p['entry'];
	$output['content']['text'] = $text;
	return $output;
}
function bibleGetPassageBibleBrainText($url){
    $response =  bibleBrainGet($url);
	$verses = $response->data;
    $text = '<p class="bible_text>';
    foreach ($verses as $verse){
        $text .= '<sup class="versenum">'. $verse->verse_start .'</sup>';
        $text .=  $verse->verse_text .' ';
    }
	return $text . '</p>';
}


function bibleBrainGetBibles($language_iso){
	$output = '';
	$count = 0;
    $url = 'https://4.dbt.io/api/bibles?language_code=' . $language_iso . '&';
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
