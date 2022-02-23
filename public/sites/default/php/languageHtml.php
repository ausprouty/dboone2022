<?php

function languageHtml($language_iso){

    $google= array(
        'eng' => 'en',
        'fra'=> 'fr',
        'cmn'=> 'zh-Hans',
        'spa'=>'es'
    );
    return $google[$language_iso];
}