<?php

function version2Text($text){
    $text = str_ireplace('"/sites/sent67/images/back.png', '"/sites/sent67/images/back.png', $text);
    $text = str_ireplace('"/sites/sent67/images/up.png', '"/sites/sent67/images/up.png', $text);
    $text = str_ireplace('"/sites/sent67/images/forward.png', '"/sites/sent67/images/forward.png', $text);
    $text = str_ireplace('"/content/', '"/sites/sent67/content/', $text);

   return $text;
}