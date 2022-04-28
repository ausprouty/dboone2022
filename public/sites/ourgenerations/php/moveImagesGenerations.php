<?php
function moveImagesGenerations($text){

    $text = str_ireplace ('/images/share-generations.png','/sites/generations/images/share-generations.png', $text);
    $text = str_ireplace ('/images/facebook-generations.png','/sites/generations/images/facebook-generations.png', $text);
    $text = str_ireplace ('/images/generations-plus-small.png','/sites/generations/images/generations-plus-small.png', $text);
    $text = str_ireplace ('/images/generations-plus-big.png','/sites/generations/images/generations-plus-big.png', $text);


    $text = str_ireplace ('/sites/generations/images/forward.png','/sites/default/images/forward.png', $text);
    $text = str_ireplace ('/sites/generations/images/up.png','/sites/default/images/up.png', $text);
    $text = str_ireplace ('/sites/generations/images/back.png','/sites/default/images/back.png', $text);


    $text = str_ireplace ('/images/Add-to-homepage-Portrait.png','/sites/generations/images/Add-to-homepage-Portrait.png', $text);
    $text = str_ireplace ('/images/Add-to-homepage-Landscape.png','/sites/generations/images/Add-to-homepage-Landscape.png', $text);

    $text = str_ireplace ('src="/content/A2/images/','src="/sites/generations/content/A2/images', $text);

    return $text;
}