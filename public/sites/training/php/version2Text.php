function version2Text($text){
    $text = str_ireplace('"/sites/training/images/back.png', '"/sites/training/images/back.png', $text);
    $text = str_ireplace('"/sites/training/images/up.png', '"/sites/training/images/up.png', $text);
    $text = str_ireplace('"/sites/training/images/forward.png', '"/sites/training/images/forward.png', $text);
    $text = str_ireplace('"/content/', '"/sites/training/content/', $text);

   return $text;
}