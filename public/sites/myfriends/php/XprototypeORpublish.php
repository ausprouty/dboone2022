<?php

myRequireOnce('bookmark.php');
myRequireOnce('getTitle.php');
myRequireOnce('languageSpecificJavascripts.php');
myRequireOnce('copyGlobal.php');

    
function _addHeader($p){
    //writeLog(time(). 'addHeader', myGetPrototypeFile('header.html'));
    return  myGetPrototypeFile('header.html');
}
function _addFooter($p){
    //writeLog(time(). 'addFooter', myGetPrototypeFile('footer.html'));
    return  myGetPrototypeFile('footer.html');
}
// $scope must be 'publish' or 'prototype'
function publishFiles( $scope , $p, $fname, $text, $standard_css, $selected_css){
    
    // start with header
    $output = _addHeader($p);
    //$p['debug'] .= "\n". 'publishFiles' . "\n";
    $out['debug'] = 'In publishFiles with: ' . $fname .  "\n";
    // add onload only if files are here
    $onload_note_js = '';
    if (strpos($text, '<form') !== false){
        $pos = strrpos($fname, '/') +1;
        $filename = substr($fname, $pos);
        $note_index = $p['country_code'] .'-'. $p['language_iso'] .'-'.$p['folder_name'] .'-'.$filename;
        $onload_note_js = ' onLoad= "showNotes(\'' . $note_index . '\')" ';
        $output .= '<!--- PrototypeOrpublish added onLoad -->' ."\n";
        $out['debug'] =   $onload_note_js  ."\n";
        $out['debug'] =   $output  ."\n";
    }
   
    $result = getTitle($p['recnum']); 
    $out['debug'] .= $result['debug'] ."\n";
    $title = WEBSITE_TITLE . $result['content'];
    $out['debug'] .= 'title is '. $title ."\n";
    $google= array(
        'eng' => 'en',
        'fra'=> 'fr',
        'cmn'=> 'zh-Hans',
    );
   
    $placeholders = array(
        '{{ language.google }}',
        '{{ title }}',
        '{{ standard.css }}',
        '{{ selected.css }}', 
        '{{ onload-note-js }}',
        '</html>',  
        '</body>');
    $replace = array( 
        $google[$p['language_iso']],
        $title,
        $standard_css, 
        $selected_css, 
        $onload_note_js,
        '',
        '');
    $output = str_replace($placeholders, $replace,  $output);
    // insert text
    $output .= $text;
    // remove dupliate CSS
    $response = _removeDuplicateCSS($output);
    $out['debug'] .= $response['debug'];
    $output = $response['content'];
    // append footer
    $output .= _addFooter($p);
    // copy all images and styles to the prototype directory
    $response = _copyImagesAndStyles($output, $scope);
    if (isset($response['debug'])){
        $out['debug'] .= $response['debug'];
    }
    // make sure we have all the necessary directories
    dirMake($fname);
    // write the file
    $fh = fopen($fname, 'w');
    writeLog('publishFiles', $fname);
    if ($fh){
        fwrite($fh, $output);
        fclose($fh);
    }
    else{
        $out['debug'] .= 'NOT able to write' .  $fname . "\n";
        $out['error'] = true;
    }
    return ($out);
}
function prototypeCopyDir($source, $destination){
    if (!file_exists($destination)){
        dirMake ($destination); 
    }
    copyGlobal($source, $destination);
    return $out;
}
// requires $p['recnum'] and $p['library_code']
function prototypeLanguageFooter($p){
    // get bookmark
    $b['recnum'] = $p['recnum'];
    $b['library_code'] = isset($p['library_code'])?$p['library_code']:'library';
    $bm = bookmark($b);
    $bookmark = $bm['content'];
    $p['debug'] .= isset($bm['debug'])? $bm['debug']:null;
    //
    $bookmark['country'] = $bookmark['country'];
    $url = isset($bookmark['country']->url) ?  $bookmark['country']->url: 'https://myfriends.life';
    $website = isset($bookmark['country']->website) ? $bookmark['country']->website : 'www.myfriends.life';
    if (!isset($p['debug'])){
        $p['debug'] = '';
    }
    if (!isset($p['language_iso'])){
        $p['language_iso'] = '';
    }
    $footer  = null;
    $p['debug'] .= 'Looking for Language Footer'. "\n";
    $sql = "SELECT * FROM content 
        WHERE  country_code = '". $p['country_code'] ."' 
        AND  language_iso = '". $p['language_iso'] ."' 
        AND folder_name = ''  AND filename = 'index' 
        ORDER BY recnum DESC LIMIT 1";
    $p['debug'] .= $sql. "\n";
    $data = sqlArray($sql);
    if ($data){
        $text_data = json_decode($data['text']);
        $footer  = isset($text_data->footer) ? $text_data->footer : null;
    }
    if (!$footer ){
        $footer  =  $footer  =  myGetPrototypeFile('languageFooter.html');
    }
    $title = getTitle($p['recnum']);
    if (isset($bookmark['page']->filename)){
        $page = $p['country_code'] . '/'. $p['language_iso']. '/'. $p['folder_name'] .'/'.  $bookmark['page']->filename .'.html';
        $note_id = $p['country_code'] . '-'. $p['language_iso']. '-'. $p['folder_name'] .'-'.  $bookmark['page']->filename  .'.html';
    }
    else{
        $page = '';
        $note_id = '';
    }
   
    $text = 'Here is the link to ' . $title['content'] ;
   
    $placeholders = array(
        '{{ url }}', '{{ website }}', '{{ title }}', '{{ text }}', '{{ page }}', '{{ note_id }}'
    );
    $values = array(
        $url,
        $website,
        $title['content'],
        $text,
        $page,
        $note_id
    );
    $footer  = str_replace( $placeholders, $values, $footer ) ;
    $footer .= languageSpecificJavascripts($p);
    return $footer;
}
/*
Looking for all images and styles that are in the current file
*/
function _copyImagesAndStyles($text, $scope){
    $source_dir = ROOT_EDIT;
    switch ($scope){
        case 'prototype':
            $destination_dir = ROOT_PROTOTYPE;
            break;
        case 'publish':
            $destination_dir = ROOT_PUBLISH;
            break;
        default:
            return;
    }
    $out = [];
    $out['message'] = null;
    $out['debug'] = 'prototypeCopyImagesAndStyles' . "\n";
    $out['debug'] = $scope . "\n";
    $out['debug'] .= $text . "\n\n";
   
    $find_begin = '"/content/';
    $find_end = '"';
    if (strpos($text, $find_begin)!== false){
        //$p['debug'] .= "Images found\n";
        while (strpos($text, $find_begin) !== false){
            $pos_begin = strpos($text, $find_begin);
            $text = substr($text, $pos_begin);
            $pos_end = strpos($text, $find_end, 2) -1;
            $filename = substr($text, 1, $pos_end);
            $from = $source_dir. $filename;
            $from = str_ireplace('//', '/', $from);
            $out['debug'] .= $from . "\n";
            $to = $destination_dir. $filename;
            if (file_exists($from)){
                _prototypeCreateDirectory($to);
                // do not copy html files or you will overwrite current index page
                if (!is_dir($from) && strpos ($to, '.html') === false){
                    copy ($from, $to );
                    $out['debug'] .= ' _copyImagesAndStyles copied ' . $filename . ' from' . $from . ' to '. $to . "\n";
                }
            }
            else{
                
                $out['message'] .= "$from not found in prototypeCopyImagesAndStyles \n";
                $out['error'] = true;
            }
            $text = substr($text, $pos_end);
        }
    }
    else{
        $out['message'] .= "no images found\n";
        $out['error'] = true;

    }
    writeLog('CopyImagesAndStylesLog', $out['debug']);

    
    return $out;
    
}
/*  creates directories for copying files
*/

function _prototypeCreateDirectory($dir){
    $out = null;
    $parts = explode('/', $dir);
    $path = null;
    foreach ($parts as $part){
        $path .= $part .'/';
        if (strpos ($part, '.') === false){
            if (!file_exists($path)){
                mkdir($path);
            }
        }
    }
    
    return $out;
}
/* There may be multiple links to the same style sheet
   You want to remove duplicates
    <link rel="stylesheet" href="/sites/default/styles/cardGLOBAL.css" />
*/
function _removeDuplicateCSS($text){
    $out= [];
    $out['debug'] = 'In _removeDuplicateCSS' . "\n";
    $count = substr_count($text, '<link rel="stylesheet');
    $out['debug'] .= "count is $count \n";
    $css = [];
    $one = 1;
    $pos_start = 1;
    $find = '<link rel="stylesheet" href="';
    $length_find = strlen($find);
    // find and extract all styles
    for ($i= 1; $i <= $count; $i++){
        $pos_start = mb_strpos($text, $find, $pos_start) + $length_find;
        $pos_end = mb_strpos($text, '"', $pos_start );
        $length = $pos_end - $pos_start;
        $link = mb_substr($text, $pos_start, $length);
        $out['debug'] .= "link is $link \n";
        $css[] = $link;
    }
    // now get rid of duplicates
    $length = count($css);
    $out['debug'] .= "length is $length \n";
    for ($i=1; $i<$length; $i++){
        $link = array_pop($css);
        $out['debug'] .= "link is $link \n";
        if (in_array($link, $css)){
            $needle = '<link rel="stylesheet" href="'. $link .'" />';
            $out['debug'] .= "needle is $needle \n";
            $out['debug'] .= "I only want to remove $one \n";
            // from https://stackoverflow.com/questions/1252693/using-str-replace-so-that-it-only-acts-on-the-first-match
            $pos = strpos($text, $needle);
            if ($pos !== false) {
                $text = substr_replace($text, '', $pos, strlen($needle));
            }
            $out['debug'] .= $text;
        }
    }
    $out= $text;
    return $out;

    return $out;
}