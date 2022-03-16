<?php
myRequireOnce ('publishFiles.php');
myRequireOnce ('publishLibrary.php');
myRequireOnce ('publishSeriesAndChapters.php');

function publishLibraryAndBooks($p){
    //writeLog('publishLibraryAndBooks-7-p', $p);
    /* Puplish Library and receive an array of book objects
    */
    $p = publishLibrary($p);
    //writeLog('publishLibraryAndBooks-15-books', $p['books']);
    $count = 0;
    foreach ($p['books'] as $book){
        $count++;
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
                 //writeLog('publishLibraryAndBooks-34-book-'. $code, $code);
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
        if ($book->format == 'library'){
            $message = "You will need to publish separately the libary that this library refers to";
            writeLogAppend('ERROR- publishLibraryAndBooks-51', $message);
             writeLogAppend('ERROR- publishLibraryAndBooks-51', $p);
        }
    }
    return true;
}