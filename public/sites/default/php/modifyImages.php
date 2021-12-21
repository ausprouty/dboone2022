<?php
/*
Looking for all images and styles that are in the current file
*/
myRequireOnce('createDirectory.php');
myRequireOnce('dirList.php');


/* find "/sites/'. SITE_DIRECTORY . '/content/';
and replace with
and copy files to prototype or publish directory
*/
function  modifyImages($text, $scope){
   $out = [];
   $text= modifyContentImages($text, $scope);
   $out['content'] = $text;
   return  $out;
}

function modifyContentImages($text, $scope){
    $source_dir = '../' . ROOT_EDIT;
    switch ($scope){
        case 'prototype':
            $destination_dir = ROOT_PROTOTYPE;
            break;
        case 'publish':
            $destination_dir = ROOT_PUBLISH;
            break;
        default:
            return;
    }

    $out = [];
    $out['message'] = null;
    $debug = 'In modifyImages' . "\n";
    $debug .= $scope . "\n";
    $debug .= $text . "\n\n";

    //  "sites/generations
    $find = 'src="/sites/'. SITE_DIRECTORY . '/content/';
    $remove ='/sites/'. SITE_DIRECTORY . '/';
    $find_end = '"';
    $count = substr_count($text, $find);
    $pos_start = 1;
    for ($i= 1; $i <= $count; $i++){
        $pos_start = strpos($text, $find, $pos_start) + 5;
        $pos_end = strpos($text, $find_end, $pos_start);
        $length= $pos_end - $pos_start;
        $filename = substr($text, $pos_start, $length);
        $from = $source_dir. $filename;
        $from = str_ireplace('//', '/', $from);
        $debug .= $from . "\n";
        if (file_exists($from)){
             $to = $destination_dir. str_ireplace($remove, '', $filename);
            createDirectory($to);
            // do not copy html files or you will overwrite current index page
            if (!is_dir($from) && strpos ($to, '.html') === false){
                copy ($from, $to );
                $debug .= ' _copyImagesAndStyles copied ' . $filename . ' from' . $from . ' to '. $to . "\n";
            }
        }
        else{
            $out['message'] .= "$from not found in prototypeCopyImagesAndStyles \n";
            $out['error'] = true;
        }
    }
    $good = 'src="/content/';
    $text = str_ireplace($find, $good, $text);
    writeLog('Modify Content Images-64',  $debug);
    return $text;

}