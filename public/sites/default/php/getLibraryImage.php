<?php

function getLibraryImage($p, $text){
    $library_image = '';
    if (isset($text->format->image->image)){
        $library_image =   '/sites/' . SITE_CODE .  $text->format->image->image;
    }
    return  $library_image;
}