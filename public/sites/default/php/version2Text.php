<?php
function version2Text($text){
    $text = str_ireplace('"/content/ZZ/images/mc2/mc2back.png', '"/sites/generations/images/back.png', $text);
    $text = str_ireplace('"/content/ZZ/images/mc2/mc2up.png', '"/sites/generations/images/up.png', $text);
    $text = str_ireplace('"/content/ZZ/images/mc2/mc2forward.png', '"/sites/generations/images/forward.png', $text);
    $text = str_ireplace('"/content/', '"/sites/generations/content/', $text);

   return $text;
}