<?php
function version2Text($text){
    $text = str_ireplace('"/content/AU', '"/sites/myfriends/content/AU', $text);

    $text = str_ireplace('/sites/default/images/menu/header-hamburger.png','/sites/myfriends/images/menu/header-hamburger.png', $text);

   return $text;
}