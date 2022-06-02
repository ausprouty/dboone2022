<?php
myRequireOnce ('publishDestination.php');
myRequireOnce ('fileWritePDF.php', 'pdf');

function fileWrite($filename, $text, $p){
    //make sure publishDestination is in $filename exactly once.
    $p['filename'] = $filename;
    $publishDestination =  publishDestination($p);
    if (strpos($filename,  $publishDestination) === false){
        $filename = $publishDestination . $filename;
    }
    $count = substr_count($filename, $publishDestination);
    for ($i = 1; $i < $count; $i++){
       $filename = str_ireplace ($publishDestination, '', $filename);
    }
    $destination = NULL;
    if (isset($p['destination'])){
        $destination = $p['destination'];
    }
    if ( $destination== 'nojs' ||  $destination == 'pdf'){
        $bad =  $publishDestination . 'content/';
       $filename= str_ireplace($bad , $publishDestination, $filename);
    }
    else{
         $message ="filename was $filename and destination is "  .  $destination;
    }
    $filename = dirMake($filename);
    if ( $destination == 'pdf'){
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
         writeLogAppend('fileWrite-34', $message);
         writeLogAppend('fileWrite-34', "$text\n\n");
    }
}