<?php
/*
Looking for all images and styles that are in the current file
*/
myRequireOnce('writeLog.php');

function prototypeCopyImagesAndStyles ($text, $scope){
    $source_dir = ROOT_EDIT;
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
    $out['debug'] = 'In prototypeCopyImagesAndStyles' . "\n";
    $out['debug'] .= $scope . "\n";
    $out['debug'] .= $text . "\n\n";

    $find_begin = '"/content/';
    $find_end = '"';
    if (strpos($text, $find_begin)!== false){
        //$p['debug'] .= "Images found\n";
        while (strpos($text, $find_begin) !== false){
            $pos_begin = strpos($text, $find_begin);
            $text = substr($text, $pos_begin);
            $pos_end = strpos($text, $find_end, 2) -1;
            $filename = substr($text, 1, $pos_end);
            $from = $source_dir. $filename;
            $from = str_ireplace('//', '/', $from);
            $out['debug'] .= $from . "\n";
            $to = $destination_dir. $filename;
            if (file_exists($from)){
                createDirectory($to);
                // do not copy html files or you will overwrite current index page
                if (!is_dir($from) && strpos ($to, '.html') === false){
                    copy ($from, $to );
                    $out['debug'] .= ' _copyImagesAndStyles copied ' . $filename . ' from' . $from . ' to '. $to . "\n";
                }
            }
            else{

                $out['message'] .= "$from not found in prototypeCopyImagesAndStyles \n";
                $out['error'] = true;

            }
            $text = substr($text, $pos_end);
        }
    }
    else{
        $out['message'] .= "no images found\n";
        $out['error'] = true;
    }
    writeLog('CopyImagesAndStyles',  $out['debug']);
    return $out;

}