<?php
myRequireOnce('writeLog.php');
// get styles that actually reside in the node_modules directory
function getCkEditStyleSets($p){

	//define("ROOT_EDIT", '/home/globa544/edit.mc2.online/');
	$sets = [];
	$out['debug'] = 'getCkEditStyleSets'. "\n";

	$site = ROOT_EDIT. 'node_modules/ckeditor/styles.js';
	//$sites[] = '/sites/default/ckeditor/styles/styles.js';
    //$sites[] =   '/sites/' . $p['site'] . '/ckeditor/styles/styles.js';
	if (file_exists( $site)){
		$text = file_get_contents( $site);
		$out['debug'] .=  $text . "\n";
		$res =_getStyleSetName($text);
		if ($res['content']){
			foreach ($res['content'] as $value){
				array_push($sets, $value);
			}
		}
	}
	$out['content']= $sets;
    writeLog('getCKStyleSets', $out['content']);
	writeLog('getCKStyleSets-debug', $out['debug']);
	return $out;

}

function _getStyleSetName($text){
	$out = [];
    $sets =[];
	$find = 'window.CKEDITOR.stylesSet.add';
	$bad =[' ', '(', '\''];
    $count = substr_count($text, $find);
	$out['debug'] ="count is $count\n";
 	$pos_start = 0;
    for ($i = 0; $i < $count; $i++){
		$pos_start = strpos($text,$find, $pos_start) + strlen($find);
        $pos_end = strpos($text, ',', $pos_start);
		$length = $pos_end - $pos_start;  // add 6 because last item is 6 long
        $old = substr($text, $pos_start, $length);
		$old = str_ireplace($bad, '', $old);
		$out['debug'] .="old is $old\n";
		$sets[]= $old;
		$pos_start = $pos_end;
	}
	$out['content'] = $sets;
	writeLog('_getStyleSetName', $out['content']);
	writeLog('_getStyleSetName-debug', $out['debug']);
	return $out;
}