<?php
myRequireOnce ('publishDestination.php');

function languageSpecificJavascripts($p){
    $out = '';
    $folder = publishDestination($p) . $p['country_code'] .'/'. $p['language_iso'] . '/javascript';
    if (file_exists($folder)){
        $out = '<!--- Language Specific Javascripts-->' ."\n";
        $files = scandir ($folder);
        foreach ($files as $file){
            if (substr($file, -3) == '.js'){
                $out .=  '<script src="../javascript/' . $file . '"></script>'  . "\n";
            }
        }
    }
    writeLog('languageSpecific', $out);
    return $out;
}