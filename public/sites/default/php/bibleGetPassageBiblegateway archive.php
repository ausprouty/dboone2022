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
        book_id: "John"
        book_name: "يوحنا"
        book_order: "58"
        chapter_id: "3"
        chapter_title: "Chapter 3"
        paragraph_number: "68"
        verse_id: "16"
		verse_text: "أَحَبَّ اللهُ كُلَّ النَّاسِ لِدَرالَ حَيَاةَ الْخُلُودِ."
		
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
	//  'bible' => '<div class = "bible"><h3>Jesus Promises the Holy Spirit</h3><p><sup>15 </sup>&#8220;If you love me, keep my commands.  you of everything I have said to you.</p>
	//</div>
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
	//$output['debug'] .= $parse['text'] . "\n\n\n";
	$output['initital_text'] =  "\n\n\n" . $parse['text']  . "\n\n\n";
	
	$begin = '<div class="passage-text">';
	if (strpos ($parse['text'], $begin) !== FALSE){
		// remove  header
        $parse['begin'] = $begin ;
		$parse = bible_parse_remove_begin($parse);
		$output['remove_begin'] = $parse['text'];
		// remove  footer
		if (strpos($parse['text'], '<div class="passage-scroller bottom-scroller">' !== FALSE)){
			$parse['end'] = '<div class="passage-scroller bottom-scroller">';
		}
		elseif (strpos($parse['text'], '<section class="sponsors">' !== FALSE)){
			$parse['end'] = '<section class="sponsors">';
		}
       
		$parse = bible_parse_remove_end($parse);
		$output['remove_end'] = $parse['text'];
		// obtain passage name // Yohana 3:16-18
        $parse['begin'] = '<span class="passage-display-bcv">';
        $parse['end'] = '</span>';
		$parse = bible_parse($parse );
		$output['passage_name'] = $parse['keep'];  
		$output['debug'] .= ' Passage Name is ' . $output['passage_name'] . "\n\n\n";

        // obtain name of bible
        $parse['begin'] = '<span class="passage-display-version">';
        $parse['end'] = '</span>';
        $parse = bible_parse($parse );
		$output['bible_name'] = $parse['keep'];
		$output['debug'] .= ' Bible Name is ' . $output['bible_name'] . "\n\n\n";

		// FIND START AND END OF BIBLE TEXT
		$output['looking'] = $parse['text'];
		$output['debug'] .=   ' Looking for Bible Text in ' . $output['looking'] . "\n\n\n";
		// is this the start of chapter?
		if (strpos($parse['text'],'<span class="chapternum">') !== FALSE){
			$passage_start = '<span class="chapternum">';
		}
		else{
			$passage_start = '<sup class="versenum">';
		}
		$output['passage_start'] = $passage_start;
		// now look at end
		// are their footnotes
		if (strpos ( $parse['text'], 'Footnotes:') !== FALSE){
			$passage_end =  '<div class="footnotes';
			$output['footnotes'] = '<div class="footnotes';
			$foot = 1;
		}
		// are there cross references?
		if (!isset($passage_end) && strpos ( $parse['text'],'<h4>Cross references:</h4>') !== FALSE){
			$passage_end =  '<div class="crossrefs';
			$foot = 0;
		}
		if (!isset($passage_end)){
			$passage_end =  '<div class="publisher';
			$foot = 0;
		}
		$output['passage_end'] = $passage_end;
		if ($passage_start == '<span class="chapternum">'){
            $parse['begin'] = $passage_start;
            $parse['end'] = $passage_end;
			$parse = bible_parse($parse );
			 


            $parse['begin'] = '</span>';
            $parse['end'] = $parse['keep'];;
			$parse = bible_parse($parse );
			$keep = $parse['keep'];

		}
		else{
            $parse['begin'] = $passage_start;
            $parse['end'] = $passage_end;
            $parse = bible_parse($parse );
			$keep =  '<sup>' . $parse['keep'];
		}
	}
	// if acceptable text not found return nothing.
	if (!$keep){
	  return;
	}
	$output['keep'] = $keep;
	//
	// publisher information
    //
    $parse['begin'] = '<div class="publisher-info-bottom">';
    $parse['end'] = '</div>';
    $parse = bible_parse($parse );
    $publisher = $parse['keep'];

	$publisher = str_replace('href="/versions/', 'href="http://www.biblegateway.com/versions/',$publisher);
	$publisher = str_replace(')</strong> <p>',')</strong></p> <p>',$publisher); 
	$publisher = str_replace('</p> <p>',' ',$publisher); 
	// CLEAN BIBLE TEXT
	$lines = explode('<span id', $keep);
	$clean = '';
	$i = 1;
	foreach($lines as $line){
		// add back what was exploded off.
		if ($i > 1){
			$line = '<span id'. $line;
		}
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
		$line =  preg_replace("/<sup class='footnote'.+?<\/sup>/", '' ,  $line);
		$line = str_replace('href="/', 'href="http://www.biblegateway.com/', $line);
		$line = rtrim($line);
		$clean .= $line;
		$i++;
	}
	// balance div markers
	 $div_begin = substr_count ($clean, '<div');
	 $div_end = substr_count ($clean, '</div>');
	 for ($i = $div_begin; $i < $div_end; $i++){
		$clean = '<div>'. $clean;
	 }
	 
	// remove trailing footnotes and cross refrences
	$parse['text'] = $clean; 
	if ($foot == 1){
        $parse['end'] = '<div class="footnotes">';
        $parse = bible_parse_remove_end($parse );
    }
    $parse['end'] = '<div class="crossrefs"style="display:none">';
    $parse = bible_parse_remove_end($parse );

	$class = 'bible';
	if ($p['rldir'] == 'rtl'){
		$class = 'bible_rtl';
	}
	$output['bible'] =   "\n" . '<!-- begin bible --><div class = "'. $class .'">'. $parse['text']   ;
	$output['bible'] .=  "\n" . '</div><!-- end bible -->' . "\n";

	$output['publisher'] = "\n" . '<!-- begin publisher --><div class= "publisher"> '. $publisher;
	$output['publisher'] .=  '</div><!-- end publisher -->' . "\n";
    $output['content']= [
		'reference' =>  $output['passage_name'],
		'text' => $output['bible'],
		'link' => $output['link']
	];
	return $output ;  
}

function bible_parse($param){
	// returns what is between $param['begin'] and $param['end']
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
