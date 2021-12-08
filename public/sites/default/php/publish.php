<?php
myRequireOnce ('prototypeORpublish.php');

function publishWrite($p, $fname, $text, $standard_css, $selected_css){
	$p['status'] = 'publish';
    if (!isset($p['debug'])){
        $p['debug'] = '';
	}
	$fname = ROOT_PUBLISH_CONTENT . $fname;
    // start with header
    $output = prototypeHeader($p);
    //$p['debug'] .= "\n". 'publishFiles' . "\n";
    $p['debug'] .= 'publishFiles : ' . $file .  "\n";
    $placeholders = array(
        '{{ standard.css }}',
        '{{ selected.css }}', 
        '</html>',  
        '</body>');
    $replace = array( 
        $standard_css, 
        $selected_css, 
        '',
        '');
    $output = str_replace($placeholders, $replace,  $output);
    // insert text
    $output .= $text;
    // append footer
    $output .= prototypeFooter($p);
    // copy all images and styles to the prototype directory
    $p = prototypeCopyImagesAndStyles($output, 'publish');
    // make sure we have all the necessary directories
    dirMake($file);
    // write the file
    $fh = fopen($fname, 'w');
    if ($fh){
        fwrite($fh, $output);
        fclose($fh);
    }
    else{
        $p['debug'] .= 'NOT able to write' .  $file . "\n";
        $p['error'] = true;
    }

   
    // return all parameters
    return ($p);
}

function publishCountries($p){
}
function publishLanguage($p){
}
function publishLibrary($p){
	
	$debug = 'in publishLibrary'. "\n";
	$recnum = $p['recnum'];
	$sql = "select * from content 
		WHERE recnum = $recnum
		ORDER BY recnum DESC LIMIT 1";
	$debug .= $sql . "\n";
	$index = sqlArray($sql);
	// give nice names for sql
	foreach ($index as $param_name => $param_value) {
		$$param_name = $param_value;
		$debug .= $$param_name. "\n";
	}
	$debug .= $index['text']. "\n";
	$books = JSON_DECODE ($index['text']);
	foreach ($books as $book){
		if ($book->format == 'series'){
			$debug .= $book->book . ' is series' . "\n";
			$this_filename = $book->index;
			$this_folder = $book->folder;
			$sql = "SELECT recnum from content 
				WHERE country_code =  '$country_code' AND language_iso =  '$language_iso'
				AND folder =  '$this_folder' AND filename =  '$this_filename'
				AND publish_date IS NULL
				ORDER BY recnum DESC LIMIT 1";
			$series = sqlArray($sql);
			if (isset($series['recnum'])){
                $p['recnum'] = $series['recnum'];
				$debug .= publishSeries($p);
			}
		}
		else{
			$debug .= $book->book . ' is page' . "\n";
			$this_filename = $book->page;
			$this_folder = $book->folder;
			$sql = "SELECT recnum from content 
				WHERE country_code =  '$country_code' AND language_iso =  '$language_iso'
				AND folder =  '$this_folder' AND filename =  '$this_filename'
				AND publish_date IS NULL
				ORDER BY recnum DESC LIMIT 1";
			$page = sqlArray($sql);
			if (isset($page['recnum'])){
                $p['recnum'] = $page['recnum'];
				$debug .= publishPage($p);
			}
		}
    }
    $p['recnum'] = $recnum;
	$debug .= publishPage($p);
	return $debug;
	
}
function publishSeries($p){
    $debug = 'in publishSeries'. "\n";
    $recnum = $p['recnum'];
	// get library
	$sql = "SELECT * from content 
		WHERE recnum = $recnum
		ORDER BY recnum DESC LIMIT 1";
	$debug .= $sql . "\n";
	$index = sqlArray($sql);
	// give nice names for sql
	foreach ($index as $param_name => $param_value) {
		$$param_name = $param_value;
		$debug .= $$param_name. "\n";
	}
	$debug .= $index['text']. "\n";
	$text = JSON_DECODE ($index['text']);
	
	$chapters = $text->chapters;
	// publish each chapter in latest index
	foreach ($chapters as $chapter){
		$this_filename = $chapter->filename;
		$debug .= $this_filename . "\n";
		$sql = "SELECT recnum from content 
			WHERE country_code =  '$country_code' AND language_iso =  '$language_iso'
			AND folder =  '$folder_name' AND filename =  '$this_filename'
			AND publish_date IS NULL
			ORDER BY recnum DESC LIMIT 1";
		$chapter_revision = sqlArray($sql);
	    if ($chapter_revision['recnum']) {
            $p['recnum'] = $chapter_revision['recnum'];
			$debug .= publishPage($p);
		}
	}
	$debug .= publishPage($p);
	return $debug;
	
}
function publishPage($p){
	$debug = 'in publishPage'. "\n";
	$debug .= 'REcnum: ' . $recnum . "\n";
	$sql = "select * from content 
		WHERE recnum = $recnum
		ORDER BY recnum DESC LIMIT 1";
	$debug .= $sql . "\n";
	$query = $conn->query($sql);
	$content = $query->fetch_array();
	// make nice names
	foreach ($content as $param_name => $param_value) {
		$$param_name = $param_value;
		$debug .= $param_name . ' = ' . $param_value. "\n";
	}
	// update records
	$publish_date = time();
	$sql = "UPDATE content SET publish_uid = '$my_uid',  publish_date ='$publish_date'
			WHERE country_code =  '$country_code' AND language_iso =  '$language_iso'
			AND folder =  '$folder_name' AND filename =  '$filename'
			AND publish_date IS NULL";
	$debug .= $sql . "\n";
	$query = sqlArray($sql, 'update');
	// create file
	$filename = $root . 'content/'. $country_code . '/' . $language_iso. '/'. $folder_name . '/'. $filename;
	$filetype = '.'. $filetype;
	if (!strpos ($content['filename'], $filetype)){
		$filename .= $filetype;
	}
	$filename = str_ireplace('//', '/', $filename);
	$fh = fopen($filename, 'w');
	fwrite($fh, $content['text']);
	fclose($fh);
	
    return $debug;	
}
