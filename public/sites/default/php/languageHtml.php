<?php

function languageHtml($language_iso){

    $google= array(
        'amh'=> 'am',
        'arb'=> 'ar',
        'cmn'=> 'zh-Hans',
        'cmt'=>'zh-Hant',
        'deu'=> 'de',
        'eng'=> 'en',
        'fin'=> 'fi',
        'fra'=> 'fr',
        'gaz'=> '',
        'hin'=> 'hi',
        'por'=> 'pt-BR',
        'spa'=> 'es',
        'tam'=> 'ta',
        'urd'=> 'ur',

    );
    $out = 'en';
    if (isset($google[$language_iso])){
       $out = $google[$language_iso];
    }
    return $out;
}
