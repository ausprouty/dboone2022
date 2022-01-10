<?php
/*
Looking for all images and styles that are in the current file
*/
myRequireOnce ('createDirectory.php');
myRequireOnce ('publishDestination.php');
myRequireOnce ('writeLog.php');



/* find "/sites/'. SITE_DIRECTORY . '/content/';
and replace with
and copy files to prototype or publish directory
*/
function  modifyImages($text, $p){
   //writeLog('modifyImages-17-text', $text);
   //writeLog('modifyImages-18-p', $p);
   $text= modifyContentImages($text, $p);
   //writeLog('modifyImages-20-text', $text);
   copySiteImages($text, $p);
   $out = $text;
   return  $out;
}

function modifyContentImages($text, $p){
    //writeLog('modifyContentImages-26-p', $p);
    //writeLog('modifyContentImages-2-text', $text);
    //define("ROOT_EDIT", '/home/globa544/edit.mc2.online/');
    $destination_dir = publishDestination($p);
    $debug = 'In modifyContentImages' . "\n";
    $debug .= $p['destination'] . "\n";
    $debug .= $text . "\n\n ============ End of Text ==============\n";
     //writeLog('modifyContentImages-params', $debug);
    //  "sites/generations
    $find = 'src="/'.  SITE_DIRECTORY . 'content/';
    //writeLog('modifyContentImages-35-find', $find);
    $remove = SITE_DIRECTORY ;
    $find_end = '"';
    $count = substr_count($text, $find);
    //writeLog('modifyContentImages-39-count', $count);
    $pos_start = 1;
    // find these images and copy to the target directory
    for ($i= 1; $i <= $count; $i++){
        $pos_start = strpos($text, $find, $pos_start) + 5;
        $pos_end = strpos($text, $find_end, $pos_start);
        $length= $pos_end - $pos_start;
        $filename = substr($text, $pos_start, $length);
        $from = ROOT_EDIT . $filename;
        $from = str_ireplace('//', '/', $from);
        $debug .= $from . "\n";
        //writeLog('modifyContentImages-51-from-'. $i, $from);
        if (file_exists($from)){
            $to = $destination_dir. str_ireplace($remove, '', $filename);
            $to = str_ireplace('//', '/', $to);
            //writeLog('modifyContentImages-54-to-'. $i, $to);
            createDirectory($to);
            // do not copy html files or you will overwrite current index page
            if (!is_dir($from) && strpos ($to, '.html') === false){
                copy ($from, $to );
                $message = ' modifyContentImages copied ' . $filename . ' from' . $from . ' to '. $to . "\n";
                 //writeLog('modifyContentImages-60- copy-'. $i, $message);
            }
        }
        else{
            $message = "$from not found in publishCopyImagesAndStyles";
            writeLogError('modifyContentImages', $message);
            trigger_error( $message, E_USER_ERROR);
        }
    }
    $good = 'src="/content/';
    $text = str_ireplace($find, $good, $text);
    //writeLog('modifyContentImages-64-text',  $text);
    return $text;

}
// looking for  <img src="/sites/mc2/images and  <img src="/sites/images
function copySiteImages($text, $p){
    $debug = '';
    //writeLog('copySiteImages-26-p', $p);
    //writeLog('copySiteImages-2-text', $text);
    //define("ROOT_EDIT", '/home/globa544/edit.mc2.online/');
    $destination_dir = publishDestination($p);
     //writeLog('copySiteImages-params', $debug);
    $sites = array(DIR_SITE, DIR_DEFAULT_SITE);
    foreach ($sites as $site){
        $find = '<img src="/'. $site . 'images';
        $find_end = '"';
        $count = substr_count($text, $find);
        //writeLog('copySiteImages-92-count', $count);
        $pos_start = 1;
        // find these images and copy to the target directory
        for ($i= 1; $i <= $count; $i++){
            $pos_start = strpos($text, $find, $pos_start) + 10;
            $pos_end = strpos($text, $find_end, $pos_start);
            $length= $pos_end - $pos_start;
            $filename = substr($text, $pos_start, $length);
            $from = ROOT_EDIT . $filename;
            $from = str_ireplace('//', '/', $from);
            $debug .= $from . "\n";
            //writeLog('copySiteImages-103-from-'. $i, $from);
            if (file_exists($from)){
                $to = $destination_dir.  $filename;
                $to = str_ireplace('//', '/', $to);
                //writeLog('copySiteImages-107-to-'. $i, $to);
                createDirectory($to);
                // do not copy html files or you will overwrite current index page
                if (!is_dir($from) && strpos ($to, '.html') === false){
                    copy ($from, $to );
                    $message = ' copySiteImages copied ' . $filename . ' from' . $from . ' to '. $to . "\n";
                    //writeLog('copySiteImages-113- copy-'. $i, $message);
                }
            }
            else{
                $message = "$from not found in copySiteImages";
                writeLogError('copySiteImages', $message);
                trigger_error( $message, E_USER_ERROR);
            }
        }

    }
    //writeLog('copySiteImages-125-text',  $text);
    return $text;
}