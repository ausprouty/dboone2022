<?php
// see https://www.w3schools.com/howto/tryit.asp?filename=tryhow_js_collapsible

/* You should have something like this:

<div class="reveal">&nbsp;
<hr />
<p>Louange</p>

*/
function modifyRevealSummary($text){
    $out = [];
    $out['debug'] = "In _revealSummary Today\n";
    $template = '<div onclick="appRevealSummary(\'[id]\');" id="Summary[id]" class="summary">[TagOpen]+ [Word][TagClose]</div>'. "\n";
    $template .= '<div class="collapsed" id ="Text[id]">'. "\n";
    $count = substr_count($text,'<div class="reveal">' );
    $out['debug'] .= "I have  $count segments \n";
    $pos_start = 0;
    for ($i = 0; $i < $count; $i++){
        $out['debug'] .= "\n\n\nCount: $i \n\n";
        $pos_start = strpos($text,'<div class="reveal">', $pos_start );
        $out['debug'] .= "\n\nPos Start: $pos_start \n";
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
        $out['debug'] .= "---------------tag\n";
        foreach ($tag as $key => $value){
            $out['debug'] .= "$key  => $value \n";
        }
        $out['debug'] .= "tag----------------\n";
        $pos_tag_start = min($tag);
        $out['debug'] .= "pos_tag_start: $pos_tag_start \n";
        $smallest = array_search($pos_tag_start, $tag);
        $out['debug'] .= "Smallest: $smallest \n";
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
        $out['debug'] .= 'Tag Open: ' . $tag_open ."\n";
        $out['debug'] .= 'Tag Close: ' . $tag_close ."\n";
        // find  word
        $word_end_pos = strpos($text,$tag_close, $pos_tag_start );
        $word_length = $word_end_pos - $tag_end - 1;
        $word = substr($text, $tag_end +1 , $word_length);
        $out['debug'] .= 'Word: ' . $word ."\n";
        // do we have multiple tags?
        if (strpos($word, '<') !== false){
            $tag_indent = substr_count($word,'<' )/2;
            $out['debug'] .= "Tag Indent:  $tag_indent\n";
            $end_first_indent_pos = 0;;
            for ($j = 0; $j < $tag_indent; $j++){
                $end_first_indent_pos = strpos($word, '>', $end_first_indent_pos+1);
                $out['debug'] .= "end_first_indent_pos:   $end_first_indent_pos \n";
            }
            $begin_second_indent_pos = 0;
            for ($j = 0; $j < $tag_indent; $j++){
                $begin_second_indent_pos = strpos($word, '<', $begin_second_indent_pos+1);
                $out['debug'] .= "begin_second_indent_pos:   $begin_second_indent_pos \n";
            }


            $real_word_length =  $begin_second_indent_pos - $end_first_indent_pos -1;
            $real_word = substr($word, $end_first_indent_pos + 1 , $real_word_length);
            $out['debug'] .= 'Real Word: ' . $real_word ."\n";

            $begin_indent_tag = substr($word, 0 , $end_first_indent_pos + 1);
            $out['debug'] .= 'Begin Indent Tag: ' . $begin_indent_tag ."\n";

            $end_indent_tag = substr($word, $begin_second_indent_pos);
            $out['debug'] .= 'End Indent Tag: ' . $end_indent_tag ."\n";

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
        $out['debug'] .= $new;
        $pos_end = strpos($text, $tag_close, $pos_start);
        $length = $pos_end - $pos_start + strlen($tag_close) +1;
        $text = substr_replace($text, $new, $pos_start, $length);
        $pos_start = $pos_end;
    }
    $out = $text;
    return $out;
}