<?php

function version2Text($text){
    writeLog('version2Text-3', $text);
    $text = str_ireplace('src="/content/ZZ/images/mc2/mc2back.png"', 'src="/sites/mc2/images/standard/look-back.png"', $text);
    $text = str_ireplace('src="/content/ZZ/images/mc2/mc2up.png"', 'src="/sites/mc2/images/standard/look-up.png"', $text);
    $text = str_ireplace('src="/content/ZZ/images/mc2/mc2forward.png"', 'src="/sites/mc2/images/standard/look-forward.png"', $text);
    $text = str_ireplace('sites/generations', 'sites/mc2', $text);
    $text = str_ireplace('"image":"/content', '"image":"/sites/mc2/content', $text);
    writeLog('version2Text-9', $text);

   return $text;
}