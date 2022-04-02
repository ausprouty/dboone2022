<?php
/* There may be multiple links to the same style sheet
   You want to remove duplicates
    <link rel="stylesheet" href="/sites/default/styles/cardGLOBAL.css" />
*/
myRequireOnce ('publishDestination.php');
myRequireOnce ('writeLog.php');
myRequireOnce ('version2Text.php');

function publishCSS($text, $p){
    // need to make sure it is clean because old CSS may be added from a data file.
    $text = version2Text($text);
    $count = substr_count($text, '<link rel="stylesheet');
    $css = [];
    $one = 1;
    $pos_start = 1;
    $find = '<link rel="stylesheet" href="';
    $length_find = strlen($find);
    // find and extract all styles
    for ($i= 1; $i <= $count; $i++){
        $pos_start = mb_strpos($text, $find, $pos_start) + $length_find;
        $pos_end = mb_strpos($text, '"', $pos_start );
        $length = $pos_end - $pos_start;
        $link = mb_substr($text, $pos_start, $length);
        $css[] = $link;
    }
    $css_files= $css;
    // now get rid of duplicates
    $length = count($css);
    for ($i=1; $i<$length; $i++){
        $link = array_pop($css);
        if (in_array($link, $css)){
            $needle = '<link rel="stylesheet" href="'. $link .'" />';
            // from https://stackoverflow.com/questions/1252693/using-str-replace-so-that-it-only-acts-on-the-first-match
            $pos = strpos($text, $needle);
            if ($pos !== false) {
                $text = substr_replace($text, '', $pos, strlen($needle));
            }
        }
    }

    if ($p['destination'] != 'pdf'){
        //copy css
        $source_file =[];
        foreach ($css_files as $c){
        $source_file[$c] = $c;
        }
        $count= 0;
        foreach ($source_file as $source){
            $count++;
            //define("ROOT_EDIT", '/home/globa544/edit.mc2.online/');
            $from = ROOT_EDIT. $source;
            $from = str_ireplace ('//', '/', $from);
            //writeLog('publishCSS-58-from-'. $count, $from);
            if (file_exists($from)){
                $to = publishDestination($p) . $source;
                $to = str_ireplace('//', '/', $to);
                //writeLog('publishCSS-60-to-'. $count, $to);
                createDirectory($to);
                // do not copy html files or you will overwrite current index page
                if (!is_dir($from) && strpos ($to, '.html') === false){
                    copy ($from, $to );
                    $message = ' publishCSS copied ' . $source . ' from' . $from . ' to '. $to . "\n";
                    //writeLog('publishCSS-69- copy-'. $count, $message);
                }
            }
            else{
                $message = "$from not found in publishCSS";
                writeLogError('publishCSS-74-text', $message . "\n\n" . $text);
                trigger_error( $message, E_USER_ERROR);
            }
        }

    }
    return $text;

}