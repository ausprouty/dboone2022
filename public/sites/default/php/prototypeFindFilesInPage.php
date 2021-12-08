<?php
/*
  I only want to list files that are in the content directory
*/

function  prototypeFindFilesInPage($text){
    $out['files_in_page'] = [];
    $find_begin = '"/content/';
    $find_end = '"';
    if (strpos($text, $find_begin)!== false){
        //$p['debug'] .= "Images found\n";
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
            //    $out['message'] .= "$mine not found in prototypeCopyImagesAndStyles \n";
            ////    $out['error'] = true;

            //}
            
            $text = substr($text, $pos_end);
           // $p['debug'] .= ' copied ' . $from . ' to '. $to . "\n";
        }
    }
    else{
        if (!isset($p['message'])){
            $out['message'] = '';
        }
        $out['message'] .= "no files found\n";
        $out['error'] = true;

    }
    return $out;
    
}
