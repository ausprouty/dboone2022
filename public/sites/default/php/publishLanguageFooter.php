<?php
myRequireOnce ('writeLog.php');
myRequireOnce ('myGetPrototypeFile.php');

// requires $p['recnum'] and $p['library_code']
function publishLanguageFooter($p){
    myRequireOnce('languageSpecificJavascripts.php', $p['destination']);

    $debug = 'In publishLanguageFooter' . "\n";
    if (isset($p['recnum'])){
        $b['recnum'] = $p['recnum'];
        $b['library_code'] =isset($p['library_code'])?$p['library_code']:'library';
    }
    else{
        $b = $p;
    }
    $bookmark  = bookmark($b);
    $url = isset($bookmark['country']->url) ?  $bookmark['country']->url: 'https://myfriends.life';
    $website = isset($bookmark['country']->website) ? $bookmark['country']->website : 'www.myfriends.life';
    if (!isset($debug)){
        $debug = '';
    }
    if (!isset($p['language_iso'])){
        $p['language_iso'] = '';
    }
    $footer  = null;
    $debug .= 'Looking for Language Footer'. "\n";
    $sql = "SELECT * FROM content
        WHERE  country_code = '". $p['country_code'] ."'
        AND  language_iso = '". $p['language_iso'] ."'
        AND folder_name = ''  AND filename = 'index'
        ORDER BY recnum DESC LIMIT 1";
    $debug .= $sql. "\n";
    $data = sqlArray($sql);
    if ($data){
        $text = json_decode($data['text']);
        $footer  = isset($text->footer) ? $text->footer : null;
    }
    if (!$footer ){
         myRequireOnce ('getLanguageFooter.php', $p['destination']);
         $footer=getLanguageFooter($p);
    }
     $page_title ='Link: ';
    if (isset($p['recnum'])){
         $page_title = getTitle($p['recnum']) .': ';
         $page_title = str_replace("'", "\'", $page_title);// this will go right before link when shared.
    }
    if (isset($bookmark['page']->filename)){
        //$page = $p['country_code'] . '/'. $p['language_iso']. '/'. $p['folder_name'] .'/'.  $bookmark['page']->filename .'.html';
        $note_id = $p['country_code'] . '-'. $p['language_iso']. '-'. $p['folder_name'] .'-'.  $bookmark['page']->filename  .'.html';
    }
    else{
       // $page = '';
        $note_id = 'must be an error somewhere';
    }
    $placeholders = array(
        '{{ url }}',
        '{{ website }}',
        '{{ page_title }}',
        '{{ note_id }}',
        '{{ country_code }}',
        '{{ language_iso }}'
    );
    $values = array(
        $url,
        $website,
        $page_title,
        $note_id,
        $p['country_code'],
        $p['language_iso']
    );
    $footer  = str_replace( $placeholders, $values, $footer ) ;
    $footer .= languageSpecificJavascripts($p);
    return $footer;
}