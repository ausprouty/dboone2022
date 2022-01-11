<?php
myRequireOnce('publishDestination.php');

function fileWrite($filename, $file, $destination){
    $d['destination']= $destination;
    if (strpos($fname,  publishDestination($p)) == false){
        $filename = publishDestination($p) . $fname;
    }
    $filename = dirMake($filename);
    $fh = fopen($filename, 'w');
    if ($fh){
        fwrite($fh, $output);
        fclose($fh);
    }
    else{
        $message = " 'NOT able to write' .  $fname";
         writeLogError('fileWrite'. random_int(0, 99999), $message);
        trigger_error( $message, E_USER_ERROR);
    }

}