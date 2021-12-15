<?php
/* There may be multiple links to the same style sheet
   You want to remove duplicates
    <link rel="stylesheet" href="/sites/default/styles/cardGLOBAL.css" />
*/
function prototypeRemoveDuplicateCSS($text){
    $out= [];
    $out['debug'] = 'In prototypeRemoveDuplicateCSS' . "\n";
    $count = substr_count($text, '<link rel="stylesheet');
    $out['debug'] .= "count is $count \n";
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
        $out['debug'] .= "link is $link \n";
        $css[] = $link;
    }
    // now get rid of duplicates
    $length = count($css);
    $out['debug'] .= "length is $length \n";
    for ($i=1; $i<$length; $i++){
        $link = array_pop($css);
        $out['debug'] .= "link is $link \n";
        if (in_array($link, $css)){
            $needle = '<link rel="stylesheet" href="'. $link .'" />';
            $out['debug'] .= "needle is $needle \n";
            $out['debug'] .= "I only want to remove $one \n";
            // from https://stackoverflow.com/questions/1252693/using-str-replace-so-that-it-only-acts-on-the-first-match
            $pos = strpos($text, $needle);
            if ($pos !== false) {
                $text = substr_replace($text, '', $pos, strlen($needle));
            }
            $out['debug'] .= $text;
        }
    }
    $out['content']= $text;
    return $out;

}