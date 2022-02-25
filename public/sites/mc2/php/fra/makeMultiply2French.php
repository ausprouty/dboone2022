<?php
return;
require_once ('../.env.api.remote.mc2.php');
myRequireOnce ('sql.php');

myRequireOnce ('.env.cors.php');
myRequireOnce ('bookmark.php');
myRequireOnce ('bibleDbtArray.php');
myRequireOnce ('bibleGetPassage.php');
myRequireOnce ('create.php');


// get template
$template = file_get_contents(ROOT_EDIT .  '/api/french/M2Template.html');
// get Bible passages and Video details
$passages = [];
$item = [];
$p = array(
    'country_code' => 'M2',
    'language_iso' => 'fra',
    'folder_name'=> 'multiply2',
    'filetype' => 'html',
    'series' => 'multiply',
    'starting_value' => 201,
    'ending_value' => 299,
    'my_uid'=> 999, //computer
);
$response = bookmark($p);
$p['bookmark']  = $response['content'];
$passages_file = file_get_contents(ROOT_EDIT .  '/api/french/M2Passages.txt');
$passage_lines = explode("\n", $passages_file);
$index = 0;
foreach ($passage_lines as $passage_line){
    $item = explode("\t", $passage_line);
    if (isset($item[3])){ // lines may be blank
        $passages[] = array(
            'index' => $index,
            'file' => trim($item[0]),
            'bible_entry' => trim($item[1]),
            'video_url' => trim($item[2]),
            'video_title' => trim($item[3]),
        );
    }
}
// get study name and 
$summary_file = _file_get_contents_utf8(ROOT_EDIT .  '/api/french/M2Summaries.txt');
$summary_lines = explode("\n", $summary_file);
$file_number = $p['starting_value'];
foreach ($summary_lines as $summary_line){
    //echo nl2br ($summary_line . "\n\n");
    $find = $file_number . '.';
    if (mb_strpos($summary_line, $find) !== false){
        // 252. Leadership Obéissant: Matthieu 28:16-20; 
        if (isset($p['title'])){
            _createPage($template, $passages, $p);
        }
        $summary_line = trim(str_replace($find, '', $summary_line)); // take out number
        $colon = mb_strpos($summary_line, ':');
        $p['title'] = mb_substr($summary_line, 0, $colon);
        $passage_start = $colon + 1;
        $p['bible_entries'] = trim(mb_substr($summary_line, $passage_start)); // may be multiple
        $p['file_number'] = $file_number;
        $p['filename'] = 'multiply'. $file_number ;
        $p['summary'] = '';
        $p['enrichment'] = '';
        $p['year'] = '';
        if ($file_number > $p['ending_value']){
            return "finished with " . $p['ending_value'];
        }
        $file_number++;
       
    }
    elseif (mb_strpos($summary_line, 'Résumé:') !== false){ 
        $pos = mb_strpos($summary_line, ':') + 1;
        $summary_line = trim(mb_substr($summary_line, $pos));
        $p['summary'] = $summary_line;
   
    }
    elseif(mb_strpos($summary_line, 'Pour une étude approfondie') !== false){ 
        $p['enrichment'] = '<p class="enrichment">' . $summary_line . '</p>';
        
    }
    elseif(mb_strpos($summary_line, 'AP. J.-C') !== false){ 
        $p['year'] = '<p class="year">' . $summary_line . '</p>';
    }
}

return;


function  _createPage($template, $passages, $p){

    // get bookmark
    $bookmark = bookmark($p);
    $p['bookmark'] = $bookmark['content'];
    // create Bible Block
    $bible_block = '';
    foreach ($passages as $passage){
        if ($passage['file'] ==  $p['series'] . $p['file_number']){
            if ($passage['bible_entry'] != ''){
             $bible_block .=  _revealBible($passage, $p);
            }
            if ($passage['video_url'] != ''){
               $bible_block .=  _revealVideo($passage);
            }
        }
    }
    // now major replace
    $replace = array(
        '[Bible Reference]',
        '[Bible Block]',
        '[Summary]',
        '[Enrichment]',
        '[Year]'
    );
    $values = array(
        $p['bible_entries'],
        $bible_block,
        $p['summary'],
        $p['enrichment'],
        $p['year']
    );
    $text = str_replace($replace, $values, $template);
    $p['text']= $text;
   
    $fh = fopen(ROOT_LOG . $p['filename'] .'html', 'w');
	fwrite($fh, $text);
    fclose($fh);
    $res = createContent($p);
}

function _revealBible($passage, $p){
    $p['entry'] = $passage['bible_entry'];
   // echo nl2br ($p['entry'] . "\n");
    $res = createBibleDbtArrayFromPassage($p);
    $dbt_array = $res['content'];
    $dbt = $dbt_array[0];
    
    if ($dbt['collection_code'] == 'NT'){
        $dbt['bid'] = $p['bookmark']['language']->bible_nt;
    }
    else{
        $dbt['bid'] = $p['bookmark']['language']->bible_ot;
    }
    //foreach ($dbt as $key => $value){
    //    echo nl2br ($key . ' - '. $value. "\n");
    //}
    $res = bibleGetPassage($dbt);
   // foreach ($res['content'] as $key => $value){
   //     if ($key != 'bible'){
   //         echo nl2br($key . ' - ' . $value .  "\n");
    //    }
        
   // }
    //
    //echo nl2br($res['content']['bible'] . "\n");
    
    $template = '<div class="reveal bible">&nbsp;
<hr /><p class="reference">[Reference]</p>
[Bible Passage]
<p class="bible"><a class="bible-readmore" href="[Link]">[Read More]</a></p>
<hr /></div>'. "\n";
    $replace = array(
        '[Reference]',
        '[Bible Passage]',
        '[Link]',
        '[Read More]'
    );
    $values = array(
        $res['content']['passage_name'],
        $res['content']['bible'],
        $res['content']['link'],
        $p['bookmark']['language']->read_more
    );
    $out = str_replace($replace, $values, $template);
return $out;
}



function _revealVideo($passage){
    $template = '<div class="reveal video">&nbsp;
<hr /><a href="[Video URL]">[Video Title]</a>
<hr/></div>'. "\n";
    $replace = array(
        '[Video URL]',
        '[Video Title]'
    );
    $values = array(
        $passage['video_url'],
        $passage['video_title']
    );
    $out = str_replace($replace, $values, $template);
    return $out;
}

function _file_get_contents_utf8($fn) {
    $content = file_get_contents($fn);
     return mb_convert_encoding($content, 'UTF-8',
         mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true));
}
