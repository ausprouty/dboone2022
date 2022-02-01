<?php
function version2Text($text){
    $text = str_ireplace('"/sites/default/images/back.png', '"/sites/generations/images/back.png', $text);
    $text = str_ireplace('"/sites/default/images/up.png', '"/sites/generations/images/up.png', $text);
    $text = str_ireplace('"/sites/default/images/forward.png', '"/sites/generations/images/forward.png', $text);
    $text = str_ireplace('"/content/A2/images/standard/','"/sites/generations/content/A2/images/standard/', $text);
    $text = str_ireplace('"/content/', '"/sites/generations/content/', $text);

   return $text;
}