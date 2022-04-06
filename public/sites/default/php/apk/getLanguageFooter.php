function getLanguageFooter($p){
    $language_footer=  $p['apk_settings']->language_footer;
    $footer  =  myGetPrototypeFile( $language_footer, $p['destination']);
    return $footer;
}