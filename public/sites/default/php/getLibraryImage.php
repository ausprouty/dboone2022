<?php

function getLibraryImage($p, $text){
    // checking for legacy data
    $library_image = '';
    if (isset($text->format->image->image)){
        $site = '/sites/' . SITE_CODE;
        if(strpos($text->format->image->image, $site) !== false){
            $library_image =  $text->format->image->image;
        }
        else{
            $library_image =   '/sites/' . SITE_CODE .  $text->format->image->image;
        }
    }

    return  $library_image;
}