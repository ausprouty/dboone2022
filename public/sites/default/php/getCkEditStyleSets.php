<?php
myRequireOnce('writeLog.php');
// get styles from default, site  and country
function getCkEditStyleSets($p){
	$sets = [];
	$out['debug'] = 'getCkEditStyleSets'. "\n";
	$sites = [];
	$sites[] = '/sites/default/ckeditor/styles/styles.js';
    $sites[] =   '/sites/' . $p['site'] . '/ckeditor/styles/styles.js';
	foreach ($sites as $site){
		if (file_exists( ROOT_EDIT. $site)){
			$text = file_get_contents(ROOT_EDIT . $site);
			$res =_getStyleSetName($text);
			if ($res['content']){
				foreach ($res['content'] as $value){
					array_push($sets, $value);
				}
			}
		}

	}
	$out['content']= $sets;
	return $out;

}

function _getStyleSetName($text){
	$out = [];
    $sets =[];
	$find = 'window.CKEDITOR.stylesSet.add';
	$bad =[' ', '(', '\''];
    $count = substr_count($text, $find);
 	$pos_start = 0;
    for ($i = 0; $i < $count; $i++){
		$pos_start = strpos($text,$find) + strlen($find);
        $pos_end = strpos($text, ',', $pos_start);
		$length = $pos_end - $pos_start;  // add 6 because last item is 6 long
        $old = substr($text, $pos_start, $length);
		$old = str_ireplace($bad, '', $old);
		$sets[]= $old;
		$pos_start = $pos_end;
	}
	$out['content'] = $sets;
	return $out;
}