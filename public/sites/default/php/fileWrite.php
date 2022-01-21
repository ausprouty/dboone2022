<?php
myRequireOnce('publishDestination.php');
myRequireOnce('fileWritePDF.php');

function fileWrite($filename, $text, $destination){
    //make sure publishDestination is in $filename exactly once.
    $d['destination']= $destination;
    $publishDestination =  publishDestination($d);
    if (strpos($filename,  $publishDestination) === false){
        $filename =$publishDestination . $filename;
    }
    $count = substr_count($filename, $publishDestination);
    for ($i = 1; $i < $count; $i++){
       $filename = str_ireplace ($publishDestination, '', $filename);
    }
    if ($destination == 'nojs' || $destination == 'pdf'){
        $bad =  $publishDestination . 'content/';
       $filename= str_ireplace($bad , $publishDestination, $filename);
    }
    else{
         $message ="filename was $filename and destination is $destination";
    }
    $filename = dirMake($filename);
    if ($destination == 'pdf'){
        $output = fileWritePDF($filename, $text);
        return $output;
    }

    $fh = fopen($filename, 'w');
    if ($fh){
        fwrite($fh, $text);
        fclose($fh);
    }
    else{
        $message = " 'NOT able to write' .  $filename . ' with destination of '. $destination ";
         writeLogError('fileWrite-34-'. random_int(0, 99999), $message);
        trigger_error( $message, E_USER_ERROR);
    }

}