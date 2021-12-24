<?php
/*
  I only want to list files that are in the content directory
*/

//define("ROOT_EDIT", '/home/globa544/edit.mc2.online/');
myRequireOnce('publishDestination.php');
myRequireOnce('writeLog.php');

function  publishFilesInPage($text, $p){
    writeLog ('publishFilesInPage-11-text', $text);
    $files= [];
    $debug = '';
    $destination = publishDestination($p);
    $find_begin = 'src="';
    $find_end = '"';
    if (strpos($text, $find_begin)!== false){
        //$debug .= "Images found\n";
        while (strpos($text, $find_begin) !== false){
            $pos_begin = strpos($text, $find_begin);
            $text = substr($text, $pos_begin + 5);
            $pos_end = strpos($text, $find_end) -1;
            $filename = substr($text, 1, $pos_end);
            // filename = sites/mc2/images/standard/look-back.png
            $from = ROOT_EDIT . $filename;
            $debug .="from is $from\n";
            if (file_exists($from)){
                // I think I want to include html
                if (!is_dir($from) && strpos ($from, '.html') === false){
                    $files[] = $filename;
                    $to = $destination. $filename;
                    createDirectory($to);
                    copy($from, $to);
                    $debug .="copied from $from to  $to\n";
                }
            }
            else{
              $message = "$from not found in publishFilesInPage";
              writeLogError('PublishFilesInPage-' .$filename, $message );

            }
            $text = substr($text, $pos_end);
           // $debug .= ' copied ' . $from . ' to '. $to . "\n";
        }
    }
    else{
        $message = "no files found ";
        trigger_error( $message, E_USER_ERROR);
    }
     writeLog ('publishFilesInPage-49-copied', $debug);
    writeLog ('publishFilesInPage-49-files', $files);
    return true;

}
