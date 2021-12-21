<?php
myRequireOnce ('create.php');
myRequireOnce ('getLatestContent.php');

function setupSeries($p){
	/* This expects input of Series in tab format (number| title | description| bible reference| filename|image|publish)
	   and creates datarecord for series
	*/
    $debug = 'setupSeries'. "\n";
    foreach ($p as $key=>$value){
        $debug .= $key .' -- '. $value . "\n";
    }
	if (!isset($p['folder_name'])){
        $message = "folder_name not set";
        trigger_error( $message, E_USER_ERROR);
		return NULL;
    }
     //checks for errors is_uploaded_file($_FILES['file']['tmp_name']))
    if ($_FILES['file']['error'] != UPLOAD_ERR_OK ){
		$message = "upload error";
        trigger_error( $message, E_USER_ERROR);
		return NULL;
    }
        //checks that file is uploaded
    if (!is_uploaded_file($_FILES['file']['tmp_name'])) {
		$message = "temp file not found";
        trigger_error( $message, E_USER_ERROR);
		return NULL;
    }
    $bad = array('"', '–');
    $good = array('', '-');
    $text = new stdClass();
    $text->series= $p['series_name'];
    $text->template = isset($p['series_name'])? $p['series_name']: null;
	$text->language = $p['language_iso'];
	$text->description = $p['description'];
    $i = 0;
    $chapters = [];
    $imported_text =  file_get_contents_utf8($_FILES['file']['tmp_name']);
    $lines = explode("\n", $imported_text);
    foreach ($lines as $line){
        $item = explode ("\t", $line);
        if (count($item) >= 7){
            $publish = trim($item[6]);
            if ($publish=='Y'){
                $chapter_publish = true;
            }
            else{
                $chapter_publish = false;
            }
            // we add .html here in case not in spreadsheet
            $chapter_filename = trim($item[4]);
            if (!strpos($chapter_filename, '.html')){
                $chapter_filename .= '.html';
            }
            $chapter = new stdClass();
            //$title =  utf8_encode(trim($item[1]));
            //$description = utf8_encode(str_replace($bad, $good, trim($item[2])));
            $title =  trim($item[1]);
            $description = trim($item[2]);
            $chapter->id =  $i ;
            $chapter->count =  trim($item[0]);
            $chapter->title =  $title;
            $chapter->description =  $description;
            $chapter->reference =  trim($item[3]);
            $chapter->filename =  substr($chapter_filename, 0, -5);
            $chapter->image =  trim($item[5]);
            $chapter->publish =  $chapter_publish;
            $debug .= json_encode($chapter, JSON_UNESCAPED_UNICODE) . "\n";
            $chapters[] = $chapter;
            $i++;
        }
        else{
            $debug .=  "Line has less then 7 elements\n";
        }
    }
    $text->chapters = $chapters;
    $p['text'] = json_encode($text, JSON_UNESCAPED_UNICODE);
    $debug .=   '$p[text]' ."\n";
    $debug .=   $p['text']."\n";
	$p['filename'] = 'index';
	$p['filetype'] = 'json';
	$o = createContent($p);
    $p['scope'] = 'series';
    $out = getLatestContent($p);
	return $out;
}
function file_get_contents_utf8($fn) {
    $content = file_get_contents($fn);
    return mb_convert_encoding($content, 'UTF-8',
        mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true));

}