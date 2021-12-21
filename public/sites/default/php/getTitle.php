<?php
function getTitle($recnum){

    $debug = 'In getTitle' ."\n";
    if (isset($recnum)) {
        $sql = 'SELECT * FROM content
            WHERE  recnum  = '. $recnum;
        $debug .= $sql. "\n";
        $data = sqlArray($sql);
    }
    else{
        $debug .= 'FATAL ERROR. No recnum in PrototypPage'. "\n";
        return $out;
    }
    // do we have a page?
    if ($data['filetype'] == 'html'){
        $sql = 'SELECT * FROM content
             WHERE  country_code  = "'. $data['country_code'] .'"
            AND language_iso = "' . $data['language_iso']   .'"
            AND folder_name  = "'. $data['folder_name'] .'"
            AND filename = "index"
            ORDER BY recnum DESC LIMIT 1';
        $debug .= $sql. "\n";
        $result = sqlArray($sql);
       // $debug .= $result['text']. "\n";
        $index = json_decode($result['text']);
        if (isset($index->chapters)){
            foreach ($index->chapters as $chapter){
                if ($chapter->filename == $data['filename']){
                    $title = $chapter->title;
                    $out = $title;
                    return $out;
                }
            }
        }
        $message = "in getTitle No Title Found ";
        trigger_error( $message, E_USER_ERROR);
        return NULL;

    }
    // do we have a series index?
   if ($data['folder_name']){
        $sql = 'SELECT * FROM content
        WHERE  country_code  = "'. $data['country_code'] .'"
        AND language_iso = "' . $data['language_iso']   .'"
        AND filename = "library"
        ORDER BY recnum DESC LIMIT 1';
        $debug .= $sql. "\n";
        $result = sqlArray($sql);
        if ($result['text']){
            $debug .= $result['text']. "\n";
            $index = json_decode($result['text']);
            foreach ($index->books as $book){
                if ($book->code == $data['folder_name']){
                    $title = $book->title;
                    $out = $title;
                    return $out;
                }
            }
           $message = "in getTitle No Title Found ";
           trigger_error( $message, E_USER_ERROR);
           return NULL;
        }
        else{
           $message = "in getTitle No Title Found ";
           trigger_error( $message, E_USER_ERROR);
           return NULL;
        }
   }
   // do we have a language index?
   if ($data['language_iso']){
        $sql = 'SELECT * FROM content
            WHERE  country_code  = "'. $data['country_code'] .'"
            AND filename = "languages"
            ORDER BY recnum DESC LIMIT 1';
        $debug .= $sql. "\n";
        $result = sqlArray($sql);
        $debug .= $result['text']. "\n";
        $index = json_decode($result['text']);
        foreach ($index->languages as $language){
            if ($language->iso == $data['language_iso']){
                $title = $language->name;
                $out = $title . ' index';
                return $out;
            }
        }
    $out = null;
        $message = "in getTitle No Title Found ";
        trigger_error( $message, E_USER_ERROR);
        return NULL;
    }
    // do we have a country index?
   if ($data['country_code']){
        $title = 'COUNTRY INDEX';
        $out = $title;
        return $out;
    }
    $title = 'HOME';
    $out = $title;
    return $out;
}