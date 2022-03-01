<?php
myRequireOnce ('writeLog.php');
myRequireOnce ('myGetPrototypeFile.php');
// see https://www.w3schools.com/howto/tryit.asp?filename=tryhow_js_collapsible

/* You should have something like this:

<div class="reveal">&nbsp;
<hr />
<p>Louange</p>

*/
function modifyRevealSummary($text, $p){
    //writeLog('modifyRevealSummary-12-text', $text);
    $debug = '';
    $template =myGetPrototypeFile('revealSummary.html');
    if ($p['destination'] == 'nojs' || $p['destination'] == 'pdf'){
        $template = '<div class="summary">[TagOpen] [Word][TagClose]</div>'. "\n";
        $template .= '<div>'. "\n";
    }
    $count = substr_count($text,'<div class="reveal">' );
    $debug .= "I have  $count segments \n";
    $pos_start = 0;
    for ($i = 0; $i < $count; $i++){
        $debug .= "\n\n\nCount: $i \n\n";
        $pos_start = strpos($text,'<div class="reveal">', $pos_start );
        $debug .= "\n\nPos Start: $pos_start \n";
        // what is the opening tag? p or h
        $tag = [];
        if (strpos($text,'<p',$pos_start !== false)){
            $tag['p'] =  strpos($text,'<p',$pos_start );
        }
        if (strpos($text,'<h2',$pos_start ) !== false){
            $tag['h2'] =  strpos($text,'<h2',$pos_start );
        }
        if (strpos($text,'<h3', $pos_start ) !== false){
            $tag['h3'] =  strpos($text,'<h3',$pos_start );
        }
        if (strpos($text,'<h4', $pos_start ) !== false){
            $tag['h4'] =  strpos($text,'<h4',$pos_start );
        }
        if (strpos($text,'<ul',$pos_start ) !== false){
            $tag['ul'] =  strpos($text,'<ul',$pos_start );
        }
        $debug .= "---------------tag\n";
        foreach ($tag as $key => $value){
            $debug .= "$key  => $value \n";
        }
        $debug .= "tag----------------\n";
        $pos_tag_start = min($tag);
        $debug .= "pos_tag_start: $pos_tag_start \n";
        $smallest = array_search($pos_tag_start, $tag);
        $debug .= "Smallest: $smallest \n";
        switch ($smallest){
            case "h2": // can be h1 h2 or h3
                $tag_type = 'h2';
                break;
            case "h3": // can be h1 h2 or h3
                $tag_type = 'h3';
                break;
            case "h4": // can be h1 h2 or h3
                    $tag_type = 'h4';
                    break;
            case "ul":
                $tag_type = 'ul';
                break;
            default:
            case "p":
                $tag_type = 'p';
                break;
        }
        $tag_close = '</'.  $tag_type . '>';

        $tag_end = strpos($text,'>', $pos_tag_start );
        $tag_length = $tag_end - $pos_tag_start + 1;
        $tag_open = substr($text, $pos_tag_start , $tag_length);
        $debug .= 'Tag Open: ' . $tag_open ."\n";
        $debug .= 'Tag Close: ' . $tag_close ."\n";
        // find  word
        $word_end_pos = strpos($text,$tag_close, $pos_tag_start );
        $word_length = $word_end_pos - $tag_end - 1;
        $word = substr($text, $tag_end +1 , $word_length);
        $debug .= 'Word: ' . $word ."\n";
        // do we have multiple tags?
        if (strpos($word, '<') !== false){
            $tag_indent = substr_count($word,'<' )/2;
            $debug .= "Tag Indent:  $tag_indent\n";
            $end_first_indent_pos = 0;;
            for ($j = 0; $j < $tag_indent; $j++){
                $end_first_indent_pos = strpos($word, '>', $end_first_indent_pos+1);
                $debug .= "end_first_indent_pos:   $end_first_indent_pos \n";
            }
            $begin_second_indent_pos = 0;
            for ($j = 0; $j < $tag_indent; $j++){
                $begin_second_indent_pos = strpos($word, '<', $begin_second_indent_pos+1);
                $debug .= "begin_second_indent_pos:   $begin_second_indent_pos \n";
            }


            $real_word_length =  $begin_second_indent_pos - $end_first_indent_pos -1;
            $real_word = substr($word, $end_first_indent_pos + 1 , $real_word_length);
            $debug .= 'Real Word: ' . $real_word ."\n";

            $begin_indent_tag = substr($word, 0 , $end_first_indent_pos + 1);
            $debug .= 'Begin Indent Tag: ' . $begin_indent_tag ."\n";

            $end_indent_tag = substr($word, $begin_second_indent_pos);
            $debug .= 'End Indent Tag: ' . $end_indent_tag ."\n";

            $word = $real_word;
            $tag_open .= $begin_indent_tag;
            $tag_close = $end_indent_tag . $tag_close;
        }
        $old = array(
            '[id]',
            '[TagOpen]',
            '[Word]',
            '[TagClose]'
        );
        $new = array(
            $i,
            $tag_open,
            $word,
            $tag_close
        );
        $new = str_replace($old, $new, $template);
        $debug .= $new;
        $pos_end = strpos($text, $tag_close, $pos_start);
        $length = $pos_end - $pos_start + strlen($tag_close) +1;
        $text = substr_replace($text, $new, $pos_start, $length);
        $pos_start = $pos_end;
    }
    //writeLog('modifyRevealSummary-131-debug', $debug);
    //writeLog('modifyRevealSummary-131-text', $text);
    return $text;
}