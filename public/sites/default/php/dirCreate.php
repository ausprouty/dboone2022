<?php
myRequireOnce('dirMake.php');

/*
define("ROOT_EDIT", '/home/globa544/edit.mc2.online/');
define("ROOT_EDIT_CONTENT", '/home/globa544/edit.mc2.online/sites/' . SITE_CODE . '/content/');
define("ROOT_LOG", '/home/globa544/edit.mc2.online/sites/logs/');
define("ROOT_STAGING", '/home/globa544/test_staging.mc2.online/');
define("ROOT_PUBLISH", '/home/globa544/test_publish.mc2.online/');
define("ROOT_SDCARD", '/home/globa544/sdcard.mc2.online/folder/');
define("ROOT_NOJS", '/home/globa544/sdcard.mc2.online/nojs/');
*/
function dirCreate($scope, $destination,  $p, $folders = null){
    switch($destination){
        case 'edit':
            $dir = ROOT_EDIT;
            break;
        case 'nojs':
            $dir = ROOT_NOJS;
            break;
        case 'prototype':
            $dir = ROOT_PROTOTYPE;
            break;
        case 'publish':
            $dir = ROOT_PUBLISH;
            break;
        case 'website':
            $dir = ROOT_WEBSITE;
            break;
        case 'sdcard':
            $dir = ROOT_SDCARD;
            break;
        case 'root':
        case 'default':
            $dir ='/';
            break;
    }
    switch ($scope){
        case ('country'):
            $dir.='content/'. SITE_CODE .'/'.$p['country_code'] .'/';
            break;
        case('language'):
            $dir.='content/'. SITE_CODE .'/'.$p['country_code']  .'/'.$p['language_iso'] .'/';
            break;
        case('library'):
            $dir.='content/'. SITE_CODE .'/'.$p['country_code']  .'/'.$p['language_iso'] .'/'.$p['library_code'].'/';
            break;
        case ('series'):
            $dir.='content/'. SITE_CODE .'/'.$p['country_code']  .'/'.$p['language_iso'] .'/'.$p['folder_name'].'/';
            break;
    }
    $dir .= $folders;
    $dir = dirMake($dir);
    return $dir;
}