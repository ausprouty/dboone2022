<?php
myRequireOnce ('myGetPrototypeFile.php');
function getPrototypeFileLibrary($p){

    if ($p['country_code'] == 'AU'){
         $body = myGetPrototypeFile('library.html', $p['destination']);
    }
    else{
        $body = myGetPrototypeFile('libraryNotAU.html', $p['destination']);
    }
    return  $body;

}