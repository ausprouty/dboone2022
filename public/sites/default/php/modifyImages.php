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
    //define("ROOT_EDIT", '/home/globa544/edit.mc2.online/');
    $destination_dir = publishDestination($p);
    $debug = 'In modifyContentImages' . "\n";
    $debug .= $p['destination'] . "\n";
    $debug .= $text . "\n\n ============ End of Text ==============\n";
    //  "sites/generations
    $find = 'src="/'.  SITE_DIRECTORY . 'content/';
    $remove = SITE_DIRECTORY ;
    $find_end = '"';
    $count = substr_count($text, $find);
    $pos_start = 1;
    // find these images and copy to the target directory
    for ($i= 1; $i <= $count; $i++){
        $pos_start = strpos($text, $find, $pos_start) + 5;
        $pos_end = strpos($text, $find_end, $pos_start);
        $length= $pos_end - $pos_start;
        $filename = substr($text, $pos_start, $length);
        $from = ROOT_EDIT . $filename;
        $from = str_ireplace('//', '/', $from);
        if (file_exists($from)){
            $to = $destination_dir. str_ireplace($remove, '', $filename);
            $to = str_ireplace('//', '/', $to);
            createDirectory($to);
            // do not copy html files or you will overwrite current index page
            if (!is_dir($from) && strpos ($to, '.html') === false){
                $message = 'copied ' . $filename . ' from' . $from . ' to '. $to . "\n";
                 copy ($from, $to );
            }
        }
        else{
            $message = "$from not found in publishCopyImagesAndStyles";
            writeLogAppend('ERROR- modifyContentImages-66', $from);
        }
    }
    $good = 'src="/content/';
    $text = str_ireplace($find, $good, $text);
    return $text;

}
// looking for  <img src="/sites/mc2/images and  <img src="/sites/images
function copySiteImages($text, $p){
    //define("ROOT_EDIT", '/home/globa544/edit.mc2.online/');
    $destination_dir = publishDestination($p);
    $sites = array(DIR_SITE, DIR_DEFAULT_SITE);
    foreach ($sites as $site){
        $find = '<img src="/'. $site . 'images';
        $find_end = '"';
        $count = substr_count($text, $find);
        $pos_start = 1;
        // find these images and copy to the target directory
        for ($i= 1; $i <= $count; $i++){
            $pos_start = strpos($text, $find, $pos_start) + 10;
            $pos_end = strpos($text, $find_end, $pos_start);
            $length= $pos_end - $pos_start;
            $filename = substr($text, $pos_start, $length);
            $from = ROOT_EDIT . $filename;
            $from = str_ireplace('//', '/', $from);
            $from = trim($from); // remove any trailing space

            if (file_exists($from)){
                $to = $destination_dir.  $filename;
                $to = str_ireplace('//', '/', $to);
                createDirectory($to);
                // do not copy html files or you will overwrite current index page
                if (!is_dir($from) && strpos ($to, '.html') === false){
                    copy ($from, $to );
                    $message = ' copySiteImages copied ' . $filename . ' from' . $from . ' to '. $to . "\n";
                }
            }
            else{
                writeLogAppend('ERROR- copySiteImages', $from);
            }
        }
    }
    return $text;
}