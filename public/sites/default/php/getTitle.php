<?php
function getTitle($recnum){
    if (isset($recnum)) {
        $sql = 'SELECT * FROM content
            WHERE  recnum  = '. $recnum;
        $data = sqlArray($sql);
    }
    else{
        $message = "no recnum in getTitle ";
        writeLogError('getTitle-10-'. time(), $message);
        return FALSE;
    }
    // do we have a page in a series?
    if ($data['filetype'] == 'html'){
        $sql = 'SELECT * FROM content
             WHERE  country_code  = "'. $data['country_code'] .'"
            AND language_iso = "' . $data['language_iso']   .'"
            AND folder_name  = "'. $data['folder_name'] .'"
            AND filename = "index"
            ORDER BY recnum DESC LIMIT 1';
        $result = sqlArray($sql);
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
        // this will return null for an index
        return NULL;

    }
    // do we have a series index so we can give the book title?
   if ($data['folder_name'] == '' || !$data['folder_name'] ){
        $sql = 'SELECT * FROM content
        WHERE  country_code  = "'. $data['country_code'] .'"
        AND language_iso = "' . $data['language_iso']   .'"
        AND filename = "library"
        ORDER BY recnum DESC LIMIT 1';
        $result = sqlArray($sql);
        if (isset($result['text'])){
            $index = json_decode($result['text']);
            foreach ($index->books as $book){
                if ($book->code == $data['folder_name']){
                    $title = $book->title;
                    $out = $title;
                    return $out;
                }
            }
             return $data['filename'];
        }
        else{
            return $data['filename'];
        }
   }
 // do we have a library?
   if ($data['folder_name'] !== ''  ){
       return 'Library';
   }
   // do we have a language index?
   if ($data['language_iso']){
        $sql = 'SELECT * FROM content
            WHERE  country_code  = "'. $data['country_code'] .'"
            AND filename = "languages"
            ORDER BY recnum DESC LIMIT 1';
        $result = sqlArray($sql);;
        $index = json_decode($result['text']);
        foreach ($index->languages as $language){
            if ($language->iso == $data['language_iso']){
                $title = $language->name;
                $out = $title . ' index';
                return $out;
            }
        }
        $out = null;
        writeLogError('getTitle-90-data', $data);
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