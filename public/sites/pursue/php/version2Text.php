<?php

function version2Text($text){
    $text = str_ireplace('"/content/ZZ/images/sent67/back.png', '"/sites/sent67/images/standard/back.png', $text);
    $text = str_ireplace('"/content/ZZ/images/sent67/up.png', '"/sites/sent67/images/standard/up.png', $text);
    $text = str_ireplace('"/content/ZZ/images/sent67/forward.png', '"/sites/sent67/images/standard/forward.png', $text);
    $text = str_ireplace('"/content/ZZ/images/ribbons/back-ribbon-sent67.png',
                           '"/sites/sent67/images/ribbons/back-ribbon-sent67.png', $text);
    $text = str_ireplace('"/sites/sent67/content/ZZ/images/ribbons/back-ribbon-sent67.png','"/sites/sent67/images/ribbons/back-ribbon-sent67.png', $text);
    $text = str_ireplace('"/sites/sent67/content/ZZ/images/sent67/back.png', '"/sites/sent67/images/standard/back.png', $text);
    $text = str_ireplace('"/sites/sent67/content/ZZ/images/sent67/up.png', '"/sites/sent67/images/standard/up.png', $text);
    $text = str_ireplace('"/sites/sent67/content/ZZ/images/sent67/forward.png', '"/sites/sent67/images/standard/forward.png', $text);
    $text = str_ireplace('"/content/ZZ/styles/appGLOBAL.css"','"/sites/default/styles/appGLOBAL.css"',$text);
     $text = str_ireplace('"/content/ZZ/styles/cardGLOBAL.css"', '"/sites/default/styles/cardGLOBAL.css"',$text);
    $text = str_ireplace('"/content/ZZ/styles/sent67GLOBAL.css"','"/sites/sent67/styles/sent67GLOBAL.css"',$text);
    $text = str_ireplace('"/content/', '"/sites/sent67/content/', $text);
     $text = str_ireplace('/sites/sent67/sites/sent67/', '/sites/sent67/', $text);

   return $text;
}