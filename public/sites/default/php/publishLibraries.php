<?php

myRequireOnce ('publishLibrary.php');
myRequireOnce ('findLibraries.php');
myRequireOnce('write.Log');


function publishLibraries($p){
     $library_codes =findLibraries($p);
     foreach ($library_codes as $library_code){
        $p['library_code'] = $library_code;
        publishLibrary($p);
     }
}
