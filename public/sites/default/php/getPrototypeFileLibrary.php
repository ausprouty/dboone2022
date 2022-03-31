<?php
myRequireOnce ('myGetPrototypeFile.php');
function getPrototypeFileLibrary($p){

    $body = myGetPrototypeFile('library.html', $p['destination']);

    return  $body;

}