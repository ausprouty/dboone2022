<?php
function version2Text($text){
    $text = str_ireplace('"/sites/default/images/back.png', '"/sites/generations/images/back.png', $text);
    $text = str_ireplace('"/sites/default/images/up.png', '"/sites/generations/images/up.png', $text);
    $text = str_ireplace('"/sites/default/images/forward.png', '"/sites/generations/images/forward.png', $text);
    $text = str_ireplace('"/content/A2/images/standard/','"/sites/generations/content/A2/images/standard/', $text);
    $text = str_ireplace('"/content/', '"/sites/generations/content/', $text);
    $text = str_ireplace('"/sites/generations/content/ZZ/images/mc2/mc2back.png',
                         '"/sites/generations/images/back.png', $text);
    $text = str_ireplace('"/sites/generations/content/ZZ/images/mc2/mc2up.png',
                         '"/sites/generations/images/up.png', $text);
    $text = str_ireplace('"/sites/generations/content/ZZ/images/mc2/mc2forward.png',
                         '"/sites/generations/images/forward.png', $text);
    $text = str_ireplace('"/sites/generations/content/ZZ/images/ribbons/back-ribbon-4G.png',
                         '"/sites/generations/images/ribbons/back-ribbon-4G.png', $text);
    $text = str_ireplace('"/images/action.png',
                           '"/sites/generations/images/css/action.png', $text);
    $text = str_ireplace('"/images/bible.png',
                           '"/sites/generations/images/css/bible.png', $text);
    $text = str_ireplace('"/images/generations-minus.png',
                           '"/sites/generations/images/css/generations-minus.png', $text);
    $text = str_ireplace('"/images/generations-minus-big.png',
                           '"/sites/generations/images/css/generations-minus-big.png', $text);
    $text = str_ireplace('"/images/generations-minus-small.png',
                           '"/sites/generations/images/css/generations-minus-small.png', $text);
    $text = str_ireplace('"/images/generations-plus.png',
                           '"/sites/generations/images/css/generations-plus.png', $text);
    $text = str_ireplace('"/images/generations-plus-big.png',
                           '"/sites/generations/images/css/generations-plus-big.png', $text);
   $text = str_ireplace('"/images/generations-plus-small.png',
                           '"/sites/generations/images/css/generations-plus-small.png', $text);
    $text = str_ireplace('"/images/reveal_big.png',
                           '"/sites/generations/images/css/reveal_big.png', $text);
    $text = str_ireplace('"/images/trainer.png',
                           '"/sites/generations/images/css/trainer.png', $text);
    $text = str_ireplace('"/images/video.png',
                           '"/sites/generations/images/css/video.png', $text);
    $text = str_ireplace('/sites/generations/sites/generations/',
                           '/sites/generations/', $text);






   return $text;
}