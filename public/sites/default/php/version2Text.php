<?php
function version2Text($text){
    $text = str_ireplace('"/sites/default/images/back.png', '"/sites/default/images/back.png', $text);
    $text = str_ireplace('"/sites/default/images/up.png', '"/sites/default/images/up.png', $text);
    $text = str_ireplace('"/sites/default/images/forward.png', '"/sites/default/images/forward.png', $text);
    $text = str_ireplace('"/content/', '"/sites/default/content/', $text);

   return $text;
}