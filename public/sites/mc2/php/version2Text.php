<?php
function version2Text($text){

    $text = str_ireplace('src="/content/ZZ/images/mc2/mc2back.png"', 'src="/sites/mc2/images/standard/look-back.png"', $text);
    $text = str_ireplace('src="/content/ZZ/images/mc2/mc2up.png"', 'src="/sites/mc2/images/standard/look-up.png"', $text);
    $text = str_ireplace('src="/content/ZZ/images/mc2/mc2forward.png"', 'src="/sites/mc2/images/standard/look-forward.png"', $text);

   return $text;
}