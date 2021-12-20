<?php
// requires $p['recnum'] and $p['library_code']
function prototypeLanguageFooter($p){
    if (!isset($p['debug'])){
        $p['debug'] = 'prototypeLanguageFooter' . "\n";
    }
     $p['debug'] .= 'in ';
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
        $text = json_decode($data['text']);
        if (isset($text->footer)){
            $p['debug'] .= 'LanguageFooter Set by Database'. "\n";
        }
        $footer  = isset($text->footer) ? $text->footer : null;
    }
    if (!$footer ){
        $p['debug'] .= 'LanguageFooter Setfrom languageFooter.html'. "\n";
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

    $p['debug'] .=  'Country Footer:' . "\n"  .$footer  . "\n";
   // $fh = fopen('logs/prototypeLanguageFooter' .   time(). '.txt', 'w');
  // fwrite($fh, $p['debug']);
   // fclose($fh);
//
    return $footer;
}