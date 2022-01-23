<?php
function version2Text($text){
  writeLog('version2Text-3', $text);

  $text = str_ireplace('/sites/default/images/menu/header-hamburger.png',
                         '/sites/myfriends/images/menu/header-hamburger.png', $text);

  $text = str_ireplace('/images/default/menu/header-front.png',
                    '/sites/myfriends/images/menu/header-front.png', $text);

  $text = str_ireplace('/images/default/country/world.jpg',
                     '/sites/default/images/country/world.jpg', $text);
  $text = str_ireplace('/images/country/YT.png',
                     '/sites/default/images/country/YT.png', $text);


  $text = str_ireplace('"/sites/myfriends/sites/mc2/', '"/sites/myfriends/', $text);
  $text = str_ireplace('/sites/mc2/', '/sites/myfriends/', $text);
  $text = str_ireplace('"/sites/myfriends/sites/myfriends/', '"/sites/myfriends/', $text);

  $text = str_ireplace('"/content/ZZ/styles/', '"/sites/myfriends/styles/', $text);
  $text = str_ireplace('"/images/', '"/sites/default/images/', $text);
  $text = str_ireplace('"/content/', '"/sites/myfriends/content/', $text);
  writeLog('version2Text-15', $text);
   return $text;
}