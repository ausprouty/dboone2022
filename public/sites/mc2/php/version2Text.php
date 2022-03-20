<?php

function version2Text($text){
    writeLog('version2Text-3', $text);
    $text = str_ireplace('src="/content/ZZ/images/mc2/mc2back.png"', 'src="/sites/mc2/images/standard/look-back.png"', $text);
    $text = str_ireplace('src="/content/ZZ/images/mc2/mc2up.png"', 'src="/sites/mc2/images/standard/look-up.png"', $text);
    $text = str_ireplace('src="/content/ZZ/images/mc2/mc2forward.png"', 'src="/sites/mc2/images/standard/look-forward.png"', $text);
    $text = str_ireplace('/content/ZZ/images/ribbons/back-ribbon-mc2.png','/sites/mc2/images/ribbons/back-ribbon-mc2.png'  ,$text);
    $text = str_ireplace('sites/generations', 'sites/mc2', $text);
    $text = str_ireplace('"image":"/content', '"image":"/sites/mc2/content', $text);
    $text = str_ireplace('/content/ZZ/styles/mc2GLOBAL.css', '/sites/mc2/styles/mc2GLOBAL.css', $text);
     $text = str_ireplace('src="/content/M2/images/standard/mc2back.png"', 'src="/sites/mc2/images/standard/look-back.png"', $text);
    $text = str_ireplace('src="/content/M2/images/standard/mc2up.png"', 'src="/sites/mc2/images/standard/look-up.png"', $text);
    $text = str_ireplace('src="/content/M2/images/standard/mc2forward.png"', 'src="/sites/mc2/images/standard/look-forward.png"', $text);
    //$text = str_ireplace('/content/M2/images/standard/Notes.png','/sites/mc2/images/standard/Notes.png' , $text);
    $text = str_ireplace('"/content/M2/', '"/sites/mc2/content/M2/', $text);
    $text = str_ireplace('"content', '"/content', $text);
    $text = str_ireplace('"sites', '"/sites', $text);
    $text = str_ireplace('sites/mc2/sites/mc2/', 'sites/mc2/', $text);
    //$text = str_ireplace('sites/mc2/content/', 'content/', $text);
    //writeLog('version2Text-9', $text);
   return $text;
}

