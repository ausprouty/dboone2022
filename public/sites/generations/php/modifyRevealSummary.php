<?php

// This differs from standard in that we allow both Big and Small Markers for reveals

myRequireOnce ('writeLog.php');
// see https://www.w3schools.com/howto/tryit.asp?filename=tryhow_js_collapsible

/* You should have something like this:

<div class="reveal">&nbsp;
<hr />
<p>Louange</p>
Some text
<hr />

OR

<div class="reveal_big">&nbsp;
<hr />
<p>Louange</p>
Some text
<hr />

*/

function modifyRevealSummary($text){
    writeLogError('modifyRevealSummary-Generations-27', $text);
    if (strpos($text, '<div class="reveal">') !== false){
        $mark = '<div class="reveal">';
        $size ='small';
        $script = 'toggleSummarySmall';
        $text = modifyRevealSummaryMaker($text, $mark, $size, $script);
    }
    if (strpos($text, '<div class="reveal_big">') !== false){
        $mark = '<div class="reveal_big">';
        $size ='big';
        $script = 'toggleSummaryBig';
        $text = modifyRevealSummaryMaker($text, $mark, $size, $script);
    }
    writeLogError('modifyRevealSummary-Generations-40', $text);
    return $text;

}
/*
<div id="SummarytoggleSummaryBig0" class="summary">
	<div onclick="toggleSummaryBig('SummarytoggleSummaryBig0')" class="summary-visible">
		<img class = "generations_plus_big" src="/images/generations-plus-big.png">
		<div class="summary-title">
			<span class = "summary_heading" >The 5 Ps of Phase One</span>
		</div>
	</div>
	<div class="collapsed" id ="TexttoggleSummaryBig0">
	text

	</div>
</div>
*/
function modifyRevealSummaryMaker($text, $mark, $size, $script){
    $debug = '';
    $template = '<div id="Summary[id]" class="summary-[size]">'. "\n"; // this div is matched by existing content.
    $template .= '<div onclick="'. $script . '(\'Summary[id]\')" class="summary-visible-[size]">'. "\n";
    $template .= '<img class = "generations-plus-[size]" src="/images/generations-plus-[size].png">'. "\n";
    $template .= '<div class="summary-title-[size]">'. "\n";
    $template .= '<span class = "summary-heading-[size]" >[Word]</span>'. "\n";
    $template .= '</div></div>'. "\n";
    $template .= '<div class="collapsed-[size]" id ="Text[id]">'. "\n";
    $template .= '[HiddenText]</div>'. "\n";
    $template .= '</div>'. "\n";
    $count = substr_count($text, $mark );
    $debug .= "I have  $count segments \n";
    $pos_start = 0;
    for ($i = 0; $i < $count; $i++){
        $debug .= "\n\n\nCount: $i \n\n";
        $pos_start = strpos($text, $mark, $pos_start );
        //$debug .= "\n\nPos Start: $pos_start \n";
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
        if (strpos($text,'<ul',$pos_start ) !== false){
            $tag['ul'] =  strpos($text,'<ul',$pos_start );
        }
        //$debug .= "---------------tag\n";
        //foreach ($tag as $key => $value){
        //    $debug .= "$key  => $value \n";
        //}
        //$debug .= "tag----------------\n";
        if (count($tag) <1){
            $debug  .= 'did not find p, h2, h3 or ul'. "\n";

        }
        $pos_tag_start = min($tag);
        //$debug .= "pos_tag_start: $pos_tag_start \n";
        $smallest = array_search($pos_tag_start, $tag);
        //$debug .= "Smallest: $smallest \n";
        switch ($smallest){
            case "h2": // can be h1 h2 or h3
                $tag_type = 'h2';
                break;
            case "h3": // can be h1 h2 or h3
                $tag_type = 'h3';
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
        //$debug .= 'Tag Open: ' . $tag_open ."\n";
        //$debug .= 'Tag Close: ' . $tag_close ."\n";
        // find  word
        $word_end_pos = strpos($text,$tag_close, $pos_tag_start );
        $word_length = $word_end_pos - $tag_end - 1;
        $word = substr($text, $tag_end +1 , $word_length);
        //$debug .= 'Word: ' . $word ."\n";
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
            //$debug .= 'Real Word: ' . $real_word ."\n";

            $begin_indent_tag = substr($word, 0 , $end_first_indent_pos + 1);
            //$debug .= 'Begin Indent Tag: ' . $begin_indent_tag ."\n";

            $end_indent_tag = substr($word, $begin_second_indent_pos);
            //$debug .= 'End Indent Tag: ' . $end_indent_tag ."\n";

            $word = $real_word;
            $tag_open .= $begin_indent_tag;
            $tag_close = $end_indent_tag . $tag_close;
        }
        // find text
        $hidden_begin = strpos($text, '<', $tag_end) +1;
        $hidden_begin = strpos($text, '<', $hidden_begin); // we need the second one.
        $hidden_end = strpos($text, '<hr',  $hidden_begin);
        $hidden_length = $hidden_end - $hidden_begin;
        $debug .= 'Tag End:'. $tag_end . "\n";
        $debug .= 'HiddenBegin:'. $hidden_begin . "\n";
        $debug .= 'HiddenEnd:'. $hidden_end . "\n";
        $debug .= 'HiddenLength:'. $hidden_length . "\n";
        $hidden_text = substr($text, $hidden_begin, $hidden_length);
        $debug .= 'HiddenText:'. $hidden_text . "\nend of Hidden\n";
        $id = $script . $i;
        $old = array(
            '[id]',
            '[size]',
            '[TagOpen]',
            '[Word]',
            '[TagClose]',
            '[HiddenText]'
        );
        $new = array(
            $id,
            $size,
            $tag_open,
            $word,
            $tag_close,
            $hidden_text
        );
        $new = str_replace($old, $new, $template);
        $debug .= 'New:'. $new . "\nend of New\n";
        //$pos_end = strpos($text, $tag_close, $pos_start);
        $pos_end =  $hidden_end + 6; // <hr /> is 6 characters
        $length = $pos_end - $pos_start + 7;
        $debug .= 'Pos Start:'.$pos_start . "\n";
        $debug .= 'Pos End:'.$pos_end . "\n";
        $debug .= 'Length:'.$length . "\n";
        $text = substr_replace($text, $new, $pos_start, $length);
        $debug .= 'Text:'. $text . "\n";
        $pos_start = $pos_end;
    }

    return $text;
}
