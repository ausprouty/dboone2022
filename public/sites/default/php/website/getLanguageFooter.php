<?php
myRequireOnce('myGetPrototypeFile.php');
function getLanguageFooter($p){
    $language_footer=  'languageFooter.html';
    $footer  =  myGetPrototypeFile( $language_footer, $p['destination']);
    return $footer;
}