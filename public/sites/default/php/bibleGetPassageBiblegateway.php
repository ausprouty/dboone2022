<?php
/* requires $p as array:
         'entry' => 'Zephaniah 1:2-3'
          'bookId' => 'Zeph',  
          'chapterId' => 1, 
          'verseStart' => 2, 
          'verseEnd' => 3,
         'collection_code' => 'OT' ,
         'version_ot' => '123', // this is bid 
         'version_nt' => '134'
     )

    returns an array:
    $output['content']= [
		'reference' =>  $output['passage_name'],
		'text' => $output['bible'],
		'link' => $output['link']
	];
		
		BASED ON THE LOGIC OF JANUARY 2020
*/
function bibleGetPassageBiblegateway($p){
	$output = array();
	$output['debug']= 'In bibleGetPassageBiblegateway' . "\n";
	// returns array (and I have no idea why both verse and reference; why k.
	//1 => 
	//array (
	//  'verse' => 
	//  array (
	//    1 => 'John 14:15-26',
	//  ),
	//  'k' => 
	//  array (
	//    1 => '<h3>Jesus Promises the Holy Spirit</h3><p><sup>15 </sup>&#8220;If you love'
	//  'bible' => '<h3>Jesus Promises the Holy Spirit</h3><p><sup>15 </sup>&#8220;If you love me, keep my commands.  you of everything I have said to you.</p>
	//
	//<p><strong><a href="http://mobile.biblegateway.com/versions/New-International-Version-NIV-Bible/">New International Version</a> (NIV)</strong> <p>Holy Bible, New International Version®, NIV® Copyright ©  1973, 1978, 1984, 2011 by <a href="http://www.biblica.com/">Biblica, Inc.®</a> Used by permission. All rights reserved worldwide.</p>',
	//   'reference' => 'John 14:15-26',
    // ),
    $parse = array();
	$reference_shaped = str_replace(' ' , '%20', trim($p['entry']));
	$agent = 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30)';
	$reffer = 'http://biblegateway.com//passage/?search='. $reference_shaped . '&version='. $p['version_code']; // URL
	$POSTFIELDS = null;
	$cookie_file_path = null;


	$ch = curl_init();	// Initialize a CURL conversation.     
	// The URL to fetch. You can also set this when initializing a conversation with curl_init(). 
	curl_setopt($ch, CURLOPT_USERAGENT, $agent); // The contents of the "User-Agent: " header to be used in a HTTP request. 
	curl_setopt($ch, CURLOPT_POST, 1); //TRUE to do a regular HTTP POST. This POST is the normal application/x-www-form-urlencoded kind, most commonly used by HTML forms. 
	curl_setopt($ch, CURLOPT_POSTFIELDS,$POSTFIELDS); //The full data to post in a HTTP "POST" operation. 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  // TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly. 
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // TRUE to follow any "Location: " header that the server sends as part of the HTTP header (note this is recursive, PHP will follow as many "Location: " headers that it is sent, unless CURLOPT_MAXREDIRS is set). 
	curl_setopt($ch, CURLOPT_REFERER, $reffer); //The contents of the "Referer: " header to be used in a HTTP request. 
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path); // The name of the file containing the cookie data. The cookie file can be in Netscape format, or just plain HTTP-style headers dumped into a file. 
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path); // The name of a file to save all internal cookies to when the connection closes. 
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //FALSE to stop CURL from verifying the peer's certificate. Alternate certificates to verify against can be specified with the CURLOPT_CAINFO option or a certificate directory can be specified with the CURLOPT_CAPATH option. CURLOPT_SSL_VERIFYHOST may also need to be TRUE or FALSE if CURLOPT_SSL_VERIFYPEER is disabled (it defaults to 2). TRUE by default as of CURL 7.10. Default bundle installed as of CURL 7.10. 
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // 1 to check the existence of a common name in the SSL peer certificate. 2 to check the existence of a common name and also verify that it matches the hostname provided. 
	curl_setopt($ch, CURLOPT_LOW_SPEED_LIMIT, 90); // Wait 30 seconds for download
	curl_setopt($ch, CURLOPT_LOW_SPEED_TIME, 90); // Wait 30 seconds for download
	curl_setopt($ch, CURLOPT_TIMEOUT, 90); // Wait 30 seconds for download
	
  	$url = 'https://biblegateway.com/passage/?search='. $reference_shaped . '&version='. $p['version_code']; // URL
	$output['link'] = $url;
  	curl_setopt($ch, CURLOPT_URL, $url); 
	$parse['text'] = curl_exec($ch);  // grab URL and pass it to the variable.
	$output['debug'] .= $parse['text'];
	// get passage name
	$parse['begin']= '<div class="dropdown-display-text">';
	$parse['end'] =  '</div>';
	$parse = bible_parse($parse);
	$output['passage_name'] = $parse['keep'];   
	// get Bible name
	$parse['begin'] = '<div class="dropdown-display-text">';
	$parse['end'] = '</div>';
	$parse = bible_parse($parse );
	$output['bible_name'] = isset($parse['keep']) ? $parse['keep']: null; // 1 Pierre 3:14-17
	// CLEAN BIBLE TEXT
	$parse['begin'] = '<div class="passage-text">';
	if (strpos($parse['text'],'<a class="full-chap-link"' ) !== FALSE){
		$parse['end'] = '<a class="full-chap-link"';
	}
	else{
		$try = '<h4>Footnotes</h4>';
		if (strpos($parse['text'], $try) === FALSE ){
			$try = '<div class="passage-scroller">';
		}
		$parse['end'] = $try;
	}
	$parse = bible_parse($parse);
	$output['debug'] .= 'line 92' . "\n";
	//$output['debug'] .= isset($parse['end']. " is Parse end\n";
	//$output['debug'] .= strpos($parse['text'], $try) . "is strpos\n";
	$output['debug'] .= $parse['keep'] . "\n\n";
	$lines = explode('<span id', $parse['keep']);
	$clean = '';
	$i = 1;
	foreach($lines as $line){
		$output['debug'] .= '---------------------------------------------- ' . "\n" . $line ."\n";
		// add back what was exploded off.
		if ($i > 1){
			$line = '<span id'. $line;
		}
		// remove extra divs twide
		$line = preg_replace('/<div.+?>/', '', $line);
		$line = preg_replace('/<div.+?>/', '', $line);
		// deal with chapter numbers; replace with verse 1
		$line = preg_replace('/<span class="chapternum.+?n>/', '<sup class="versenum">1 </sup>', $line);
		$line = preg_replace('/<span class=.+>/U', '', $line);
		// simplify paragraphs
		$line = preg_replace('/<p class.+?">/', '<p>', $line);
		// remove id
		$line = preg_replace('/<span id.+?>/', '', $line);
		// remove span
		$line = preg_replace('/<\/span>/', '', $line);
		// remove cross references and footnotes.

		$line = preg_replace("/<sup data.+?<\/sup>/" ,'', $line);
		$line = preg_replace("/<sup class='crossreference'.+?<\/sup>/" ,'', $line);
		$line =  preg_replace("/<sup class='footnote'.+?<\/sup>/", '' ,  $line);
		// clean css.
		$line = str_replace('<sup class="versenum hide">', '<sup class="versenum">', $line);
		$line = str_replace('href="/', 'href="http://www.biblegateway.com/', $line);
		$line = rtrim($line);
		$output['debug'] .= $line ."\n\n";
		$clean .= $line;
		$i++;
	}
	$output['debug'] .=  '++++++++++++++++++++++++++++++++++++++++++++++++++++' . "\n";
	$output['debug'] .=  $clean . "\n";
	// balance div markers by adding to begin;
		$div_begin = substr_count ($clean, '<div');
		$div_end = substr_count ($clean, '</div>');
		for ($i = $div_begin; $i < $div_end; $i++){
			$clean = '<div>'. $clean;
		}
	
	// clean publisher
	$parse['begin'] = '<div class="publisher-info-bottom">';
    $parse['end'] = '</div>';
    $parse = bible_parse($parse );
    $publisher = isset($parse['keep']) ? $parse['keep'] : null;
	$bad = array('<p>', '</p>', '</strong>', '<strong>');
	$publisher = str_ireplace($bad, '', $publisher);
	$publisher = str_ireplace('href="/', 'href="https://biblegateway.com/', $publisher);
	
		// determine text direction
	$class = 'bible';
	if ($p['rldir']){
		if ($p['rldir'] == 'rtl'){
			$class = 'bible_rtl';
		}
	}
	
	// assemble output
	//$output['bible'] =   "\n" . '<!-- begin bible --><div class = "'. $class .'">'. $clean   ;
	$output['bible'] =   "\n" . '<!-- begin bible -->'. $clean   ;
	//$output['bible'] .=  "\n" . '</div><!-- end bible -->' . "\n";
	$output['bible'] .=  "\n" . '<!-- end bible -->' . "\n";
	$output['publisher'] = "\n" . '<!-- begin publisher -->'. $publisher;
	$output['publisher'] .=  '<!-- end publisher -->' . "\n";
    $output['content']= [
		'reference' =>  $output['passage_name'],
		'text' => $output['bible'],
		'link' => $output['link']
	];
	return $output ;   
}

function bible_parse($param){
	if (!isset($param['text'])){
		return $param;
	}
	// returns $param['keep'] as what is between $param['begin'] and $param['end'] in  $param['text']
	// returns $params['text'] as what was left over at the end
	$pos_begin = strpos ( $param['text'] , $param['begin'] );
	if ($pos_begin === FALSE){
		return;
	}
	$begin_len = strlen ($param['begin']);
	$next_begin = $begin_len + $pos_begin;
	$pos_end = strpos ( $param['text'] , $param['end'], $next_begin );
	if (!$pos_end){
		//$_SESSION['hl_dbm_debug'] .= "begin is:$param['begin'] and end is:$e_mark and page is $page";
		//watchdog('hl_dbm_parse', "begin is:$b_mark and end is:$e_mark");
		return $param;
	}
	$length = $pos_end - $pos_begin;
	$keep_begin = $pos_begin + strlen($param['begin']);
	$keep_length = $pos_end - $pos_begin - strlen($param['begin']);
	$param['keep'] = rtrim(ltrim(substr ($param['text'] , $keep_begin, $keep_length )));
	$pos_throw = $pos_end ;
	$param['text'] = substr ($param['text'], $pos_throw);
    unset($param['begin']);
    unset($param['end']);
	return $param; 
}
function bible_parse_remove_begin($param){
	$pos_end = strpos ( $param['text'] , $param['begin'] );
	$param['text'] = rtrim(ltrim(substr ($param['text'] , $pos_end)));
	unset($param['begin']);
	return $param;
}
function bible_parse_remove_end($param){
	$pos_end = strpos ( $param['text'] , $param['end']);
	if ($pos_end !== FALSE){
		$param['text'] = rtrim(ltrim(substr ($param['text'] , 0, $pos_end)));
	}
    unset($param['end']);
	return $param;
}

function bible_parse_til($param){
	// returns what is before $param['end']
	$pos_end = strpos ( $param['text'] , $param['end']);
	if (!$pos_end){
		////$_SESSION['bible_debug'] .= "begin is:$b_mark and end is:$e_mark and page is $page";
		//watchdog('bible_parse', "begin is:$b_mark and end is:$e_mark");
		return '';
	}
	$param['keep'] = rtrim(ltrim(substr ($param['text'] , 0, $pos_end )));
	$pos_throw = $pos_end ;
	$param['text'] = substr ($param['text'], $pos_throw);
    unset($param['end']);
	return $param; 
}
