<?php
/*
Input is:
  <div class="trainer">

Output is
  <div id="Trainer#" class="trainer-hide">

And append to text
<form>
    <input type="hidden" name ="TrainerNoteCount"  id ="TrainerNoteCount" value="#">
</form>


*/
function modifyRevealTrainer($text, $p){
    $template = '<div id="TrainerNote#" class="trainer-hide">';
    $count = substr_count($text, '<div class="trainer"');
    $debug = "count is $count" ."\n";
    for ($i = 1; $i<= $count; $i++){
        $pos_start = strpos($text,'<div class="trainer"');
        $pos_end = strpos($text, '>', $pos_start);
        $length= $pos_end - $pos_end + 21;
        $new_template = str_replace('#', $i, $template);
        $text = substr_replace($text, $new_template, $pos_start, $length);
    }
    $end_template = '
    <form>
        <input type="hidden" name ="TrainerNoteCount"  id ="TrainerNoteCount" value="#">
    </form>
    ';
    $text .= "\n". '<!-- Note Count Start -->';
    $new_end_template = str_replace('#', $count, $end_template );
    $text .= $new_end_template;
    $text .= '<!-- Note Count End -->'. "\n";
    return $text;
}