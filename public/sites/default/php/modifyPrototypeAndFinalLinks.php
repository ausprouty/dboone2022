<?php

/*  <a href="https://generations.prototype.myfriends.network/content/A2/eng/emc/mc201.html">
      to
    <a href='/content/A2/eng/emc/mc201.html">
*/
function modifyPrototypeAndFinalLinks($text, $replace, $p){
    $text = str_replace ($replace, '', $text);
    return $text;
}