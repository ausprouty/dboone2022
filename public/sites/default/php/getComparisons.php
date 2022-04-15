<?php

function getComparisons($p){
    $debug = 'getFoldersContent'. "\n";
    $out['countries'] = getCountries($p);
    $out['languages'] = getLanguages($p);
    if (isset($out['languages']['change'])){
        $p['language_iso'] = $out['languages']['change'];
        $debug .= 'Changed language_iso to '. $p['language_iso'] ;
    }
    $out['library'] = _getLibraries($p);
    if (isset($out['library']['change'])){
        $p['library_code'] = $out['library']['change'];
        $debug .= 'Changed libary_code to '. $p['library_code'];
    }
    $out['books'] = getBooks($p);
    $debug.=  $out['books']['debug'];
    if (isset($out['books']['change'])){
        $p['folder_name'] = $out['books']['change'];
        $debug .= 'Changed folder_name to '. $p['folder_name'];
    }
    $out['chapters'] = getChapters($p);
    $p['content_type'] = 'series';
    if (isset($out['chapters']['change'])){
        if ($out['chapters']['change'] != ''){
            $p['filename'] = $out['chapters']['change'];
            $debug .= 'Changed filename to '. $p['filename'];
        }
        else{
            $p['content_type'] = 'page';
        }
    }
    $out['versions'] = getVersions($p);
    if (isset($out['versions']['change'])){
        $p['recnum'] = $out['versions']['change'];
        $debug .= 'Changed version to '. $p['version'];
    }
    unset($p['my_uid']);
    unset($p['token']);
    $out['params'] = $p;
    return $out;

}

function getCountries($p){

    $output = [];
    $output['country'] = 'I did not find it';
    $sql = 'SELECT * FROM content
        WHERE  filename = "countries"
        ORDER BY recnum DESC
        LIMIT 1';
    $data = sqlArray($sql);
    $countries = json_decode($data['text']);
    foreach ($countries as $country){
        if ($country->english){
            $name = $country->english;
        }
        else{
            $name = $country->name;
        }
        if ($country->code == $p['country_code'] ){
            $output['country'] = $name;
        }

      //  $details->country_code = $country->code;
      //  $details->country_name = $name;
        $out[$name] = (object) array('country_code' => $country->code, 'country_name' => $name);
    }
    asort($out);
    $output['countries'] = [];
    foreach ($out as $o){
        $output['countries'][] = $o;
    }
    return $output;
}
function getLanguages($p){

    $output = [];
    $output['language'] = null;
    if (!isset($p['country_code'])){
        $output['debug'] = 'Parameter missing';
        return $output;
    }
    $sql = 'SELECT * FROM content
        WHERE  filename = "languages"
        AND country_code ="' . $p['country_code'] . '"
        ORDER BY recnum DESC
        LIMIT 1';
    $data = sqlArray($sql);
    $text = json_decode($data['text']);
    foreach ($text->languages as $language){
        if ($language->iso == $p['language_iso'] ){
            $output['language'] = $language->name;
        }
        $output['languages'][] = (object) array('language_name' => $language->name, 'language_iso' => $language->iso);
    }
    if ($output['language'] == null && isset($output['languages'][0])){
        $output['language'] =   $output['languages'][0]->language_name;
        $output['change'] =  $output['languages'][0]->language_iso;
    }
    if (!isset( $output['languages'][0])){
        $output['languages'][] = (object) array('language_name' => 'No Languages', 'language_iso' => '');
        $output['language'] = 'No Languages';
        $output['change'] = '';
    }
    return $output;
}
function _getLibraries($p){

    $output = [];
    $output['library'] = null;
    if (!isset($p['country_code']) || !isset($p['language_iso']) || !isset($p['library_code'])){
        $output['debug'] = 'Parameter missing';
        $output['parameters'] =$p;
        return $output;
    }
    $found_same_library = false;
   // find library names
   $sql = "SELECT DISTINCT filename  FROM content
    WHERE  country_code = '" . $p['country_code'] . "'
    AND language_iso = '" . $p['language_iso'] ."' AND folder_name = ''";
    //$debug .= $sql. "\n";
    $query = sqlMany($sql, 'query');
    if (!$query){
        $debug .= 'no libraries found'. "\n";
        return $p;
    }
    while($data = $query->fetch_array()){
        $output['libraries'][] = (object) array('library_name' => $data['filename'], 'library_code' => $data['filename']);
        if ($data['filename'] == $p['library_code']){
            $found_same_library = true;
            $output['library'] = $p['library_code'];
        }
    }
    if ($found_same_library === false && isset( $output['libraries'][0])){
        $output['change'] =  $output['libraries'][0]->library_code;
        $output['library'] = $output['libraries'][0]->library_code;
    }
    if (!isset( $output['libraries'][0])){
        $output['libraries'][] =(object) array('library_name' => 'No Libraries', 'library_code' => '');
        $output['library'] = 'No Libraries';
        $output['change'] = '';
    }

    return $output;
}
function getBooks($p){
    if (!isset($p['country_code']) || !isset($p['language_iso'])|| !isset($p['folder_name'])){
        $output['debug'] = 'Parameter missing';
        $output['parameters'] =$p;
        return $output;
    }

    $output = [];
    $output['book'] = null;
    $output['debug'] = null;
    $output['debug'] .= $p['folder_name'] . ' is p{folder] ' . "\n";
    // what libraries do we have?
    $sql = 'SELECT DISTINCT filename FROM content
        WHERE  country_code ="' . $p['country_code'] . '"
        AND language_iso = "' . $p['language_iso'] . '"
        AND folder_name = ""';
    $query = sqlMany($sql);
    while($library = $query->fetch_array()){
        $sql = 'SELECT * FROM content
            WHERE  country_code ="' . $p['country_code'] . '"
            AND language_iso = "' . $p['language_iso'] . '"
            AND filename = "' . $library['filename'] . '"
            AND folder_name = ""
            ORDER BY recnum DESC
            LIMIT 1';
        $data = sqlArray($sql);
        $text = json_decode($data['text']);
        if (isset($text->books)){

            foreach ($text->books as $book){
                if ($book->code == $p['folder_name'] ){
                    $output['book'] = $book->title;
                    $output['debug'] .= $book->title . ' is title '. "\n";
                }
                $output['books'][] = (object) array('book_title' => $book->title, 'book_code' => $book->code);
            }
        }
    }
    if ($output['book'] == null && isset($output['books'][0])){
        $output['debug'] .=  ' output book is null '. "\n";
        $output['book'] =   $output['books'][0]->book_title;
        $output['change'] =   $output['books'][0]->book_code;
    }
    if (!isset( $output['books'][0])){
        $output['books'] [] = (object) array('book_title' => 'No Books', 'book_code' => '');
        $output['book'] = 'No Books';
        $output['change'] = '';
    }
    return $output;
}

function getChapters($p){

    $output = [];
    $output['chapter'] = null;
    if (!isset($p['country_code']) || !isset($p['language_iso']) || !isset($p['folder_name'])){
        $output['debug'] = 'Parameter missing';
        $output['parameters'] =$p;
        return $output;
    }
    $sql = 'SELECT * FROM content
        WHERE  country_code ="' . $p['country_code'] . '"
        AND language_iso = "' . $p['language_iso'] . '"
        AND folder_name =  "' . $p['folder_name'] . '"
        AND filename = "index"
        ORDER BY recnum DESC
        LIMIT 1';
    $data = sqlArray($sql);
    $text = json_decode($data['text']);
    $output['text'][] =  $text;
    if (isset($text->chapters)){
        foreach ($text->chapters as $chapter){
            $title = $chapter->count . '. '. $chapter->title;
            if ($chapter->filename == $p['filename'] ){
                $output['chapter'] = $title;
            }
            $output['chapters'][] = (object) array('chapter_title' => $title, 'chapter_filename' => $chapter->filename);
        }
    }
    if ($output['chapter'] == null && isset( $output['chapters'][0])){
        $output['chapter'] =   $output['chapters'][0]->chapter_title;
        $output['change'] =   $output['chapters'][0]->chapter_filename;
    }
    if (!isset( $output['chapters'][0])){
        $output['chapters'][] =(object) array('chapter_title' => 'No Chapters', 'chapter_filename' => '');
        $output['chapter'] = 'No Chapters';
        $output['change'] = '';
    }
    return $output;
}
function getVersions($p){

    $output = [];
    if (!isset($p['country_code']) || !isset($p['language_iso']) || !isset($p['folder_name'])){
        $output['debug'] = 'Parameter missing';
        $output['parameters'] =$p;
        return $output;
    }
    if ($p['content_type'] == 'series'){
        $sql = 'SELECT recnum, edit_date FROM content
            WHERE  country_code ="' . $p['country_code'] . '"
            AND language_iso = "' . $p['language_iso'] . '"
            AND folder_name =  "' . $p['folder_name'] . '"
            AND filename = "' . $p['filename'] . '"
            ORDER BY recnum DESC';
    }
    else{
        $sql = 'SELECT recnum, edit_date FROM content
            WHERE  country_code ="' . $p['country_code'] . '"
            AND language_iso = "' . $p['language_iso'] . '"
            AND filename = "' . $p['folder_name'] . '"
            ORDER BY recnum DESC';
    }

    $query = sqlMany($sql);
    while($data = $query->fetch_array()){
        $title = date('Y-m-d H:i:s', $data['edit_date']) . '(' . $data['recnum'] . ')';
        if (isset($p['recnum'])){
            if ($data['recnum'] == $p['recnum'] ){
                $output['version'] = $title;
            }
        }
        else{
            if (!isset( $output['version'])){
                $output['version'] = $title;
            }
        }
        $output['versions'][] = (object) array('version_title' => $title, 'version_recnum' => $data['recnum']);
    }
    if (!isset($output['version']) && isset($output['versions'][0])){
        $output['version'] =   $output['versions'][0]->version_title;
        $output['change'] =  $output['versions'][0]->version_recnum;
    }
    if (!isset( $output['versions'][0])){
        $output['versions'][] = (object) array('version_title' => 'No Versions', 'version_recnum' => '');
        $output['version'] = 'No Versions';
        $output['change'] = '';
    }
    return $output;
}