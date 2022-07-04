<?php

 /* <a href="javascript:popUp('pop1')">2 Corinthians 5:17</a>

	<div class="popup" id="pop1"><!-- begin bible -->
	<div>
	<div>
	<p><sup class="versenum">17&nbsp;</sup>Therefore, if anyone is in Christ, the new creation has come: The old has gone, the new is here!</p>
	</div>
	</div>
	<!-- end bible --></div>

    */

function removeBiblePopups($text){
    $find = '<a href="javascript:popUp';
    $find_reference_start =')">';
    $find_reference_end='</a>';
    $count = substr_count($text, $find);
    $template ='<span class="bible-link">[ref]</span>';
    for ($i = 0; $i < $count; $i++){
        // find whole block
        $pos_start = strpos($text,$find);
        $pos_end = strpos($text,'<!-- end bible --></div>');
        $length = $pos_end - $pos_start + strlen($find);
        // find reference
        $pos_ref_start=strpos($text,$find_refernece_start, $pos_start ) + strlen($find_refernece_start);
        $pos_ref_end=strpos($text,$find_refernece_end, $pos_start );
        $length_ref = $pos_ref_end - $pos_ref_start + strlen( $find_reference_start);
        $ref =substr($text, $pos_ref_start, $length_ref );
        $new =str_replace('[ref]', $ref, $template);
        // update $text
        $text = substr_replace($text, $new, $pos_start, $length);
    }
    return $text;
}
