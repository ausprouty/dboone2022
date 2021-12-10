function version2Text($text){
    $text = str_ireplace('"/sites/myfriends/images/back.png', '"/sites/myfriends/images/back.png', $text);
    $text = str_ireplace('"/sites/myfriends/images/up.png', '"/sites/myfriends/images/up.png', $text);
    $text = str_ireplace('"/sites/myfriends/images/forward.png', '"/sites/myfriends/images/forward.png', $text);
    $text = str_ireplace('"/content/', '"/sites/myfriends/content/', $text);

   return $text;
}