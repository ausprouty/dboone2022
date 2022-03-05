<?php
myRequireOnce ('dirMake.php');

/*
define("ROOT_EDIT", '/home/globa544/edit.mc2.online/');
define("ROOT_EDIT_CONTENT", '/home/globa544/edit.mc2.online/sites/' . SITE_CODE . '/content/');
define("ROOT_LOG", '/home/globa544/edit.mc2.online/sites/logs/');
define("ROOT_STAGING", '/home/globa544/test_staging.mc2.online/');
define("ROOT_WEBSITE", '/home/globa544/test_publish.mc2.online/');
define("ROOT_SDCARD", ROOT . 'sdcard.mc2');
*/
function dirCreate($scope, $destination,  $p, $folders = null){
    $dir = '';
    switch($destination){
        case 'edit':
            //define("ROOT_EDIT", '/home/globa544/edit.mc2.online/');
            $dir = ROOT_EDIT . '/sites/' . SITE_CODE . '/content/';
            break;
        case 'nojs':
            $dir = ROOT_SDCARD . $p['sdSubDir'] .'/folder/nojs/';
            break;
        case 'staging':
            $dir = ROOT_STAGING . 'content/';
            break;
        case 'website':
            $dir = ROOT_WEBSITE. 'content/';
            break;
        case 'sdcard':
            $dir = ROOT_SDCARD . $p['sdSubDir'] .'/folder/content/';
            break;
        case 'root':
        case 'default':
            $dir ='/';
            break;
    }
    switch ($scope){
        case ('country'):
            $dir.=  $p['country_code'] .'/';
            break;
        case('language'):
            $dir.= $p['country_code']  .'/'.$p['language_iso'] .'/';
            break;
        case('library'):
            $dir.= $p['country_code']  .'/'.$p['language_iso'] .'/'.$p['library_code'].'/';
            break;
        case ('series'):
            $dir.= $p['country_code']  .'/'.$p['language_iso'] .'/'.$p['folder_name'].'/';
            break;
    }
    $dir .= $folders;
    $dir = dirMake($dir);
    return $dir;
}