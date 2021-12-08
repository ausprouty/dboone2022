<?php
function getTitle($recnum){
    $out = [];
    $out['debug'] = 'In getTitle' ."\n";
    if (isset($recnum)) {
        $sql = 'SELECT * FROM content 
            WHERE  recnum  = '. $recnum;
        $out['debug'] .= $sql. "\n";
        $data = sqlArray($sql);
    }
    else{
        $out['debug'] .= 'FATAL ERROR. No recnum in PrototypPage'. "\n";
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
        $out['debug'] .= $sql. "\n";
        $result = sqlArray($sql);
       // $out['debug'] .= $result['text']. "\n";
        $index = json_decode($result['text']);
        if (isset($index->chapters)){
            foreach ($index->chapters as $chapter){
                if ($chapter->filename == $data['filename']){
                    $title = $chapter->title;
                    $out['content'] = $title;
                    return $out;
                }
            }
        }
        $out['content'] = null;
        $out['error'] = 'No Title Found';
        $out['debug'] .= 'No Title Found'. "\n";
        return $out;

    }
    // do we have a series index?
   if ($data['folder_name']){
        $sql = 'SELECT * FROM content 
        WHERE  country_code  = "'. $data['country_code'] .'"
        AND language_iso = "' . $data['language_iso']   .'" 
        AND filename = "library"
        ORDER BY recnum DESC LIMIT 1';
        $out['debug'] .= $sql. "\n";
        $result = sqlArray($sql);
        if ($result['text']){
            $out['debug'] .= $result['text']. "\n";
            $index = json_decode($result['text']);
            foreach ($index->books as $book){
                if ($book->code == $data['folder_name']){
                    $title = $book->title;
                    $out['content'] = $title;
                    return $out;
                }
            }
            $out['content'] = null;
            $out['error'] = 'No Title Found';
            $out['debug'] .= 'No Title Found'. "\n";
            return $out;
        }
        else{
            $out['content'] = null;
            $out['error'] = 'No Title Found';
            $out['debug'] .= 'No Title Found'. "\n";
            return $out; 
        }
   }
   // do we have a language index?
   if ($data['language_iso']){
    $sql = 'SELECT * FROM content 
        WHERE  country_code  = "'. $data['country_code'] .'"
        AND filename = "languages"
        ORDER BY recnum DESC LIMIT 1';
    $out['debug'] .= $sql. "\n";
    $result = sqlArray($sql);
    $out['debug'] .= $result['text']. "\n";
    $index = json_decode($result['text']);
    foreach ($index->languages as $language){
        if ($language->iso == $data['language_iso']){
            $title = $language->name;
            $out['content'] = $title . ' index';
            return $out;
        }
    }
    $out['content'] = null;
    $out['error'] = 'No Title Found';
    $out['debug'] .= 'No Title Found'. "\n";
    return $out;
    }
    // do we have a country index?
   if ($data['country_code']){
        $title = 'COUNTRY INDEX';
        $out['content'] = $title;
        return $out;
    }
    $title = 'HOME';
    $out['content'] = $title;
    return $out;
}