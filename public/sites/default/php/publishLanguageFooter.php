<?php
myRequireOnce ('writeLog.php');
myRequireOnce ('myGetPrototypeFile.php');

// requires $p['recnum'] and $p['library_code']
function publishLanguageFooter($p){
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
        if (isset($text->footer)){
            $debug .= 'LanguageFooter Set by Database'. "\n";
        }
        $footer  = isset($text->footer) ? $text->footer : null;
    }
    if (!$footer ){
        $language_footer = 'languageFooter.html';
        if($p['destination'] == 'nojs' || $p['destination'] == 'sdcard'){
            $language_footer = $p['sdcard_settings']->footer;
        }
        writeLogDebug('publishLanguageFooter-44-footer', $language_footer);
        writeLogDebug('publishLanguageFooter-45-p', $p['sdcard_settings']);
        $footer  =  myGetPrototypeFile( $language_footer, $p['destination']);
    }
    $placeholders = array(
        '{{ url }}', '{{ website }}'
    );
    $values = array(
        $url,
        $website
    );
    $footer  = str_replace( $placeholders, $values, $footer ) ;

    $footer .= languageSpecificJavascripts($p);

    $debug .=  'Country Footer:' . "\n"  .$footer  . "\n";
     //writeLog('publishLanguageFooter', $debug);
    return $footer;
}