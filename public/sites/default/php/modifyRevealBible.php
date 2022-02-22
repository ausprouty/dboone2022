<?php

/* <div class="reveal bible">&nbsp;
<hr />
<p>John 10:22-30</p>
<div id="bible">
<div class="bible">
<div class="bible"><sup>22&nbsp;</sup>Then came the Festival of Dedication at Jerusalem. It was winter,<sup class="versenum">23&nbsp;</sup>and Jesus was in the temple courts walking in Solomon&rsquo;s Colonnade.<sup class="versenum">24&nbsp;</sup>The Jews who were there gathered around him, saying, &ldquo;How long will you keep us in suspense? If you are the Messiah, tell us plainly.&rdquo;
<p><sup class="versenum">25&nbsp;</sup>Jesus answered, &ldquo;I did tell you, but you do not believe. The works I do in my Father&rsquo;s name testify about me,<sup class="versenum">26&nbsp;</sup>but you do not believe because you are not my sheep.<sup class="versenum">27&nbsp;</sup>My sheep listen to my voice; I know them, and they follow me.<sup class="versenum">28&nbsp;</sup>I give them eternal life, and they shall never perish; no one will snatch them out of my hand.<sup class="versenum">29&nbsp;</sup>My Father, who has given them to me, is greater than all; no one can snatch them out of my Father&rsquo;s hand.<sup class="versenum">30&nbsp;</sup>I and the Father are one.&rdquo;</p>
</div>
<!-- end bible --><a class="readmore" href="https://biblegateway.com/passage/?search=John%2010:22-30&amp;version=NIV" target="_blank">Read More </a></div>
</div>
<hr /></div>
*/
function modifyRevealBible($text, $bookmark, $p){
    $read_phrase = trim($bookmark['language']->read);
    $template = '<button id="Button[id]" type="button" class="collapsible bible">[Show]</button>';
    $template .= '<div class="collapsed" id ="Text[id]">';
    if($p['destination'] == 'nojs' || $p['destination'] == 'pdf'){
        $template = '<h3>[Reference]</h3>';
        $template .= '<div>';
    }
    $count = substr_count($text,'<div class="reveal bible">' );
    for ($i = 0; $i < $count; $i++){
        $pos_start = mb_strpos($text,'<div class="reveal bible"');
        $pos_end = mb_strpos($text, '</p>', $pos_start);
        $length = $pos_end - $pos_start + 4;
        $old = mb_substr($text, $pos_start, $length);
        $word = trim(strip_tags($old));
        $word = str_replace('&nbsp;', '', $word);
        $word = str_replace("\n", '', $word);
        $reference = str_replace('  ', ' ', $word);
        $show = str_replace('%', $reference, $read_phrase);
        $new = str_replace('[id]', $i, $template);
        $new = str_replace('[Show]', $show, $new);
        $new = str_replace('[Reference]', $reference, $new);
         // from https://stackoverflow.com/questions/1252693/using-str-replace-so-that-it-only-acts-on-the-first-match
         // recalculate because not using multibyte function
        $pos_start = strpos($text,'<div class="reveal bible"');
        $pos_end = strpos($text, '</p>', $pos_start);
        $length = $pos_end - $pos_start + 4;
        $text = substr_replace($text, $new, $pos_start, $length);
    }

    return $text;
}
