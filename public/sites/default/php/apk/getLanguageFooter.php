<?php
function getLanguageFooter($p){
    if (isset($p['apk_settings']->language_footer)){
        $language_footer=  $p['apk_settings']->language_footer;
    }
    else{
        $language_footer=  'languageFooter.html';
    }
    $footer  =  myGetPrototypeFile( $language_footer, $p['destination']);
    return $footer;
}