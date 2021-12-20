<?php
function version2Text($text){


    $text = str_ireplace('/sites/default/images/menu/header-hamburger.png',
                         '/sites/myfriends/images/menu/header-hamburger.png', $text);

    $text = str_ireplace('"/sites/myfriends/sites/mc2/', '"/sites/myfriends/', $text);
     $text = str_ireplace('/sites/mc2/', '/sites/myfriends/', $text);
     $text = str_ireplace('"/sites/myfriends/sites/myfriends/', '"/sites/myfriends/', $text);

    $text = str_ireplace('"/content/ZZ/styles/', '"/sites/myfriends/styles/', $text);
    $text = str_ireplace('"/images/', '"/sites/default/images/', $text);
    $text = str_ireplace('"/content/', '"/sites/myfriends/content/', $text);

   return $text;
}