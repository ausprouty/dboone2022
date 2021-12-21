<?php
myRequireOnce('writeLog.php');
// requires $p['recnum'] and $p['library_code']
function publishLanguageFooter($p){
    $debug = 'In publishLanguageFooter' . "\n";
    // get bookmark
    $b['recnum'] = $p['recnum'];
    $b['library_code'] = isset($p['library_code'])?$p['library_code']:'library';
    $bookmark  = bookmark($b);
    //
    $bookmark['country'] = $bookmark['country'];
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
        $debug .= 'LanguageFooter Setfrom languageFooter.html'. "\n";
        $footer  =  myGetPrototypeFile('languageFooter.html');
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
     writeLog('publishLanguageFooter, $debug');
    return $footer;
}