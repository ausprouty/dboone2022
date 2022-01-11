<?php
/*
  I only want to list files that are in the content directory
*/

//define("ROOT_EDIT", '/home/globa544/edit.mc2.online/');
myRequireOnce('publishDestination.php');
myRequireOnce('writeLog.php');
myRequireOnce('version2Text.php');

function  publishFilesInPage($text, $p){
    $files_in_pages = [];
    $text = version2Text($text);
    //writeLog ('publishFilesInPage-11-text', $text);
    $find_begin = 'src="';
    $result= publishFilesInPageFind($find_begin, $text, $p);
    if (is_array($result)){
        $files_in_pages = array_merge($files_in_pages,$result);
    }
    $find_begin = 'href="';
    $result= publishFilesInPageFind($find_begin, $text, $p);
    if (is_array($result)){
        $files_in_pages = array_merge($files_in_pages,$result);
    }
    return  $files_in_pages;

}

function publishFilesInPageFind($find_begin, $text, $p){
    $destination = publishDestination($p);
    $files_in_pages = [];
    $debug = '';
    $find_end = '"';
    if (strpos($text, $find_begin)!== false){
        $count = 0;
        while (strpos($text, $find_begin) !== false){
            $count++;
            $pos_begin = strpos($text, $find_begin);
            $text = substr($text, $pos_begin + strlen($find_begin));
            $pos_end = strpos($text, $find_end) -1;
            $filename = substr($text, 1, $pos_end);
            // filename = sites/mc2/images/standard/look-back.png
            $from = ROOT_EDIT . $filename;
            $debug .="from is $from\n";
            if (file_exists($from)){
                 $files_in_pages[] = $from;
                // I think I want to include html
                if (!is_dir($from) && strpos ($from, '.html') === false){
                    $files[] = $filename;
                    $to = $destination. $filename;
                    createDirectory($to);
                    copy($from, $to);
                    $debug .="copied from $from to  $to\n";
                }
            }
            else{// we do not need to copy html files; they may not have been rendered yet.
                if (strpos($filename, '.html') == false){
                    $message ="  pos_begin is   $pos_begin\n";
                    $message .="  pos_end is   $pos_end\n";
                    $message .= "$from not found \n\n\n\n";
                    $message .="  text is   $text\n";
                    writeLogError('PublishFilesInPage-'. $count. '-' . $find_begin , $message );
                }
            }
            $text = substr($text, $pos_end);
           // $debug .= ' copied ' . $from . ' to '. $to . "\n";
        }
    }
    //writeLog ('publishFilesInPageFind-54-'. $find_begin . 'copied', $debug);
    //writeLog ('publishFilesInPageFind-54-'. $find_begin . 'files', $files);
    return $files_in_pages;
}
