<?php

function version2Text($text){
    $text = str_ireplace('"/content/ZZ/images/sent67/back.png', '"/sites/sent67/images/standard/back.png', $text);
    $text = str_ireplace('"/content/ZZ/images/sent67/up.png', '"/sites/sent67/images/standard/up.png', $text);
    $text = str_ireplace('"/content/ZZ/images/sent67/forward.png', '"/sites/sent67/images/standard/forward.png', $text);

    $text = str_ireplace('"/sites/sent67/content/ZZ/images/sent67/back.png', '"/sites/sent67/images/standard/back.png', $text);
    $text = str_ireplace('"/sites/sent67/content/ZZ/images/sent67/up.png', '"/sites/sent67/images/standard/up.png', $text);
    $text = str_ireplace('"/sites/sent67/content/ZZ/images/sent67/forward.png', '"/sites/sent67/images/standard/forward.png', $text);
    $text = str_ireplace('"/content/', '"/sites/sent67/content/', $text);

   return $text;
}