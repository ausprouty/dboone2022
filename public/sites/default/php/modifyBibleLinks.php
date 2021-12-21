<?php


function modifyBibleLinks($text, $p){
    $source = 'biblegateway';
    $version = 'NIV';
    $debug = 'In _insertBibleLinks';
    $template = '<a class="bible-ref external-link"  target="_blank" href="[href]"> [passage] </a>';
    $href = array(
        'biblegateway' => 'https://www.biblegateway.com/passage/?version=[version]&search=[search]',
    );
    $template = str_replace('[href]',$href[$source], $template );
    $template = str_replace('[version]', $version, $template);
    $count = substr_count($text,'<span class="bible-link">' );
    for ($i = 0; $i < $count; $i++){
        $pos_start = mb_strpos($text,'<span class="bible-link">');
        $pos_end = mb_strpos($text, '</span>', $pos_start);
        $length = $pos_end - $pos_start - 25;
        $start = $pos_start + 25;
        $passage = mb_substr($text, $start, $length);
        $debug .=  "passage is $passage\n";
        $search= str_replace(' ', '%20', $passage);
        $new = str_replace('[search]', $search, $template);
        $new = str_replace('[passage]', $passage, $new);
        $debug .=  "new is $new\n";
        $length = $pos_end + 7 - $pos_start;
         // from https://stackoverflow.com/questions/1252693/using-str-replace-so-that-it-only-acts-on-the-first-match
         // recalculate because not using multibyte function
         $pos_start = strpos($text,'<span class="bible-link">');
         $pos_end = strpos($text, '</span>', $pos_start);
         $length = $pos_end + 7 - $pos_start;
        $text = substr_replace($text, $new, $pos_start, $length);
        //$debug .= "\n\n\n\n\nafteR replace\n";
        //$debug .= "$text" ."\n";
    }
    return $text;
}
