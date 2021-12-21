<?php
myRequireOnce ('publishFiles.php');
myRequireOnce('publishLibrary.php');
myRequireOnce('publishSeriesAndChapters.php');

function publishLibraryAndBooks($p){

    /* Puplish Library and receive an array of book objects
    */
    $debug = "\n\n publishLibraryAndBooks\n";
    $debug .= json_encode($p, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) . "\n";
    $p = publishLibrary($p);
    $debug .= "\n\n Books Parameter AFTER publishLibrary\n";
    $debug .= json_encode($p['books'], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) . "\n";
    $count = 0;
    foreach ($p['books'] as $book){
        $count++;
        $debug .= "count is $count" . "\n";
        //deal with legacy dagta
        if (isset($book->code)){
            $code = $book->code;
        }
        else if (isset($book->name)){
            $code = $book->name;
        }
        if ($book->format == 'series'){

            $sql = "SELECT recnum FROM content
                WHERE  country_code = '" . $p['country_code']. "'
                AND language_iso = '". $p['language_iso'] ."'
                AND folder_name = '". $code ."' AND filename = 'index'
                ORDER BY recnum DESC LIMIT 1";
            //$debug .= $sql . "\n";
             $data = sqlArray($sql);
             $p['recnum'] = isset($data['recnum']) ? $data['recnum'] : null;
             if ($p['recnum']){
                $p['folder_name'] = $code;
                publishSeriesAndChapters($p);
             }
        }
        if ($book->format == 'page'){
            $sql = "SELECT recnum FROM content
            WHERE  country_code = '" . $p['country_code']. "'
            AND language_iso = '". $p['language_iso'] ."'
            AND folder_name = 'pages' AND filename = '". $code . "'
            ORDER BY recnum DESC LIMIT 1";
         $data = sqlArray($sql);
         $p['recnum'] = isset($data['recnum']) ? $data['recnum'] : null;
         if ($p['recnum']){
             $p['library_code'] = $book->library_code;
             publishPage($p);
         }
        }
    }
    return true;
}