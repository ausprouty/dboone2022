<?php
/*
  I only want to list files that are in the content directory
*/

//define("ROOT_EDIT", '/home/globa544/edit.mc2.online/');

function  publishFindFilesInPage($text){
    $out['files_in_page'] = [];
    $find_begin = '"/content/';
    $find_end = '"';
    if (strpos($text, $find_begin)!== false){
        //$debug .= "Images found\n";
        while (strpos($text, $find_begin) !== false){
            $pos_begin = strpos($text, $find_begin);
            $text = substr($text, $pos_begin);
            $pos_end = strpos($text, $find_end, 2) -1;
            $filename = substr($text, 1, $pos_end);
            $mine = ROOT_EDIT . $filename;
            //TODO: put this if statement back into play
            //if (file_exists($mine)){
                // I think I want to include html
               // if (!is_dir($mine) && strpos ($mine, '.html') === false){
                if (is_dir($mine) === false){
                    $out['files_in_page'][$filename] = $filename;
                }
            //}
            //else{
            //    if (!isset($p['message'])){
            //        $out['message'] = '';
            //    }
            //    $out['message'] .= "$mine not found in publishCopyImagesAndStyles \n";
            ////    $out['error'] = true;

            //}

            $text = substr($text, $pos_end);
           // $debug .= ' copied ' . $from . ' to '. $to . "\n";
        }
    }
    else{
        $message = "no files found ";
        trigger_error( $message, E_USER_ERROR);


    }
    return $out;

}
