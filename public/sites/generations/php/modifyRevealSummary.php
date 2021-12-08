<?php

// This differs from standard in that we allow both Big and Small Markers for reveals

myRequireOnce('writeLog.php');
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
    $out = [];
    $out['debug'] = "In _revealSummary Today\n";
    if (strpos($text, '<div class="reveal">') !== false){
        $mark = '<div class="reveal">';
        $size ='small';
        $script = 'toggleSummarySmall';
        $response = modifyRevealSummaryMaker($text, $mark, $size, $script);
        $text = $response['content'];
        if ($response['debug']){
            $out['debug'] .= $response['debug'];
        }
    }
    if (strpos($text, '<div class="reveal_big">') !== false){
        $mark = '<div class="reveal_big">';
        $size ='big';
        $script = 'toggleSummaryBig';
        $response = modifyRevealSummaryMaker($text, $mark, $size, $script);
        $text = $response['content'];
        if ($response['debug']){
            $out['debug'] .= $response['debug'];
        }
    }
    $out['content'] = $text;
    return $out;

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
    $out = [];
    $out['debug'] = "modifyRevealSummaryMaker with $mark\n";
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
    $out['debug'] .= "I have  $count segments \n";
    $pos_start = 0;
    for ($i = 0; $i < $count; $i++){
        $out['debug'] .= "\n\n\nCount: $i \n\n";
        $pos_start = strpos($text, $mark, $pos_start );
        //$out['debug'] .= "\n\nPos Start: $pos_start \n";
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
        //$out['debug'] .= "---------------tag\n";
        //foreach ($tag as $key => $value){
        //    $out['debug'] .= "$key  => $value \n";
        //}
        //$out['debug'] .= "tag----------------\n";
        if (count($tag) <1){
            $out['debug']  .= 'did not find p, h2, h3 or ul'. "\n";

        }
        $pos_tag_start = min($tag);
        //$out['debug'] .= "pos_tag_start: $pos_tag_start \n";
        $smallest = array_search($pos_tag_start, $tag);
        //$out['debug'] .= "Smallest: $smallest \n";
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
        //$out['debug'] .= 'Tag Open: ' . $tag_open ."\n";
        //$out['debug'] .= 'Tag Close: ' . $tag_close ."\n";
        // find  word
        $word_end_pos = strpos($text,$tag_close, $pos_tag_start );
        $word_length = $word_end_pos - $tag_end - 1;
        $word = substr($text, $tag_end +1 , $word_length);
        //$out['debug'] .= 'Word: ' . $word ."\n";
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
            //$out['debug'] .= 'Real Word: ' . $real_word ."\n";

            $begin_indent_tag = substr($word, 0 , $end_first_indent_pos + 1);
            //$out['debug'] .= 'Begin Indent Tag: ' . $begin_indent_tag ."\n";

            $end_indent_tag = substr($word, $begin_second_indent_pos);
            //$out['debug'] .= 'End Indent Tag: ' . $end_indent_tag ."\n";

            $word = $real_word;
            $tag_open .= $begin_indent_tag;
            $tag_close = $end_indent_tag . $tag_close;
        }
        // find text
        $hidden_begin = strpos($text, '<', $tag_end) +1;
        $hidden_begin = strpos($text, '<', $hidden_begin); // we need the second one.
        $hidden_end = strpos($text, '<hr',  $hidden_begin);
        $hidden_length = $hidden_end - $hidden_begin;
        $out['debug'] .= 'Tag End:'. $tag_end . "\n";
        $out['debug'] .= 'HiddenBegin:'. $hidden_begin . "\n";
        $out['debug'] .= 'HiddenEnd:'. $hidden_end . "\n";
        $out['debug'] .= 'HiddenLength:'. $hidden_length . "\n";
        $hidden_text = substr($text, $hidden_begin, $hidden_length);
        $out['debug'] .= 'HiddenText:'. $hidden_text . "\nend of Hidden\n";
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
        $out['debug'] .= 'New:'. $new . "\nend of New\n";
        //$pos_end = strpos($text, $tag_close, $pos_start);
        $pos_end =  $hidden_end + 6; // <hr /> is 6 characters
        $length = $pos_end - $pos_start + 7;
        $out['debug'] .= 'Pos Start:'.$pos_start . "\n";
        $out['debug'] .= 'Pos End:'.$pos_end . "\n";
        $out['debug'] .= 'Length:'.$length . "\n";
        $text = substr_replace($text, $new, $pos_start, $length);
        $out['debug'] .= 'Text:'. $text . "\n";
        $pos_start = $pos_end;
        $out['debug'] = null;
    }
    $out['content'] = $text;
    return $out;
}
