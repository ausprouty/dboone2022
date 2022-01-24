<?php
function version2Text($text){
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
  $text= str_ireplace ('/sites/default/images/gospel','/sites/myfriends/images/gospel', $text);
  $text= str_ireplace ('/sites/default/images/firststeps','/sites/myfriends/images/firststeps', $text);
  $text= str_ireplace ('/sites/default/images/compass','/sites/myfriends/images/compass', $text);
  $text= str_ireplace ('/sites/default/images/chip','/sites/myfriends/images/chip', $text);
  $text= str_ireplace ('/sites/default/images/multiply','/sites/myfriends/images/multiply', $text);
   return $text;
}