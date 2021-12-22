<?php

// see  https://stackoverflow.com/questions/7381900/php-decoding-and-encoding-json-with-unicode-characters

myRequireOnce ('getLatestContent.php');
myRequireOnce ('publishFiles.php');
myRequireOnce ('writeLog.php');

/* returns bookmark values

   requires: $p['recnum'] and $p['library_code];

   returns array $bookmark

*/
function bookmark ($p){
    $debug = 'I entered Bookmark' . "\n";
    $b['bookmark'] = null;
    writeLog ('bookmark-20-', $p);


    if (isset($p['recnum'])){
         // be sure to add this in for library since you can not yet derive it.
        $b['library_code'] = isset($p['library_code'])?$p['library_code']:null;
        $debug .= 'library_code ' . $b['library_code'] . "\n\n";
        // find other parameters for bookmark from recnum
        $debug .= 'recnum is ' . $p['recnum'] . "\n";
        $starting = objectFromRecnum($p['recnum']);
        $b['country_code'] = $starting->country_code;
        $debug .= 'b[country_code] is ' . $b['country_code'] . "\n";
        $b['language_iso'] = $starting->language_iso;
        $debug .= 'b[language_iso] is ' . $b['language_iso'] . "\n";
        $b['folder_name'] = $starting->folder_name;
        $debug .= 'b[folder_name] is ' . $b['folder_name'] . "\n";
        $b['filename'] = $starting->filename;
        $debug .= 'b[filename] ' . $b['filename'] . "\n\n";
    }
    else{
        $b['country_code'] = isset($p['country_code'])?$p['country_code']:null;
        if ($b['country_code'] =='undefined') {$b['country_code']  = null;}
        $b['language_iso'] = isset($p['language_iso'])?$p['language_iso']:null;
        if ($b['language_iso'] =='undefined') {$b['language_iso']  = null;}
        $b['library_code'] = isset($p['library_code'])?$p['library_code']:null;
        $b['folder_name'] = isset($p['folder_name'])?$p['folder_name']:null;
        if ($b['folder_name'] =='undefined') {$b['folder_name']  = null;}
        $b['filename'] = isset($p['filename'])?$p['filename']:null;
        if ($b['folder_name'] =='undefined') {$b['folder_name']  = null;}
    }
  writeLog ('bookmark-48-', $b);
    if ($b['country_code']){
        $b['bookmark']['country'] = checkBookmarkCountry($b);
        if ($b['language_iso']){
            $b ['bookmark'] ['language']  = checkBookmarkLanguage($b);
            if (isset($b['library_code'])){
                $b['bookmark']['library'] = checkBookmarkLibrary($b);
                if ($b['folder_name']){
                     $b['bookmark']['series'] = checkBookmarkSeries($b);
                     $b['bookmark']['book'] = checkBookmarkBook($b);
                    if ($b['filename']){
                       $b['bookmark']['page'] = checkBookmarkPage($b);
                    }
                }
            }
        }
    }
    $out = $b['bookmark'];
    if (isset($p['scope'])){
      writeLog( 'bookmark-'.$p['scope'] , $b['bookmark']);

    }
    else{
        writeLog( 'bookmark', $b['bookmark']);
    }

    return $out;
}
function checkBookmarkCountry($b){

    $debug = 'in checkBookmarkCountry'. "\n";
    $out = null;
    $b['scope'] = 'countries';
    $content = getLatestContent($b);
    $response = json_decode($content);
    if (!$response){
        writeLogError('checkBookmarkCountry', $debug);
        trigger_error("No response in checkBookmarkCountry", E_USER_ERROR);
    }
    foreach ($response as $country){
        if ($country->code == $b['country_code']){
            $out = $country;
        }
    }
    return $out;
}
function checkBookmarkLanguage($b){

    $debug = 'in checkBookmarkLanguage'. "\n";
   $out = null;
    $b['scope'] = 'languages';
    $content = getLatestContent($b);
    $response = json_decode( $content);
    if (!$response){
        writeLog('ERROR - checkBookmarkLanguage', $debug);
        trigger_error("No response in checkBookmarkLanguage", E_USER_ERROR);
    }
    if (isset($response->languages)){
        foreach ($response->languages as $language){
            if ($language->iso == $b['language_iso']){
                $out = $language;
            }
        }
    }else{
        $debug .= 'NO response for languages'.  "\n";
    }

    return $out;
}
// no longer used
function checkBookmarkLibraries($b){
   $debug = 'in checkBookmarkLibraries'. "\n";
   $out = null;
    $b['scope'] = 'libraryNames';
    // find possible libraries with books
    $content = getLatestContent($b);
    $names = json_decode( $content);
    if (!$names){
        writeLog('checkBookmarkLibraries', $debug);
        trigger_error("No names in checkBookmarkLibraries", E_USER_ERROR);
    }
    foreach ($names as $name){
        if ($name !== 'index' && $name !== 'languages'){
            $b['library_code'] = $name;
            $b['scope'] = 'library';
            $content = getLatestContent($b);
            $response = json_decode($content);
            $books = $response->books;
            foreach($books as $book){
                $out[] = $book;
            }
        }
    }
    return $out;
}
function checkBookmarkLibrary($b){
   $debug = 'in checkBookmarkLibrary'. "\n";
   $out = null;
    if ($b['library_code'] !== 'index'){
         $b['scope'] = 'library';
    }
    else{
        $b['scope'] = 'libraryIndex';
    }
    $content = getLatestContent($b);

    $library = json_decode($content);
    if (!$library){
        writeLog('ERROR - checkBookmarkLibrary-parameters', $b);
        writeLog('ERROR - checkBookmarkLibrary-debug', $debug);
         writeLog('ERROR - checkBookmarkLibrary-res',  $res['content']);
         $message = "No r in checkBookmarkLibrary for ". $b['library_code'];
        trigger_error( $message , E_USER_ERROR);
    }
    // legacy data does not have ['books'] so move data there
    if (isset($library->books)){
        $response = $library;
    }
    else{
        $response = new stdClass();
        $response->books = $library;
    }
    $out  = $response;
    return $out;
}
function checkBookmarkBook($b){
    $debug = 'In check BookmarkBook' . "\n";
    $out = null;
    $this_book = $b['folder_name'];
    if ($this_book == 'pages'){
        $this_book = $b['filename'];
    }
    $debug .= 'This book is '.  $this_book . "\n";
    if (isset($b['bookmark']['library']->books)){
        $books = $b['bookmark']['library']->books;
        foreach ($books as $book){
            if (!isset($book->code)){
                $debug .= 'Book code is not set' . "\n";
                $code = isset($book->name) ? $book->name : NULL;
            }
            else{
                $code = $book->code;
            }
            if ($code == $this_book){
                $debug .= 'Found this code in book array' . "\n";
                $debug .= json_encode($book, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) . "\n";
                $out = $book;
            }
        }}
    return $out;
}
function checkBookmarkSeries($b){

    $debug = 'in checkBookmarkSeries'. "\n";
     $out = null;
    $b['scope'] = 'series';
    $content = getLatestContent($b);
    $debug .= 'response is'.  $content ."\n";
    $response = json_decode($content);
    if (!$response){
        writeLog('checkBookmarkSeries', $debug);
        trigger_error("No response in checkBookmarkSeries", E_USER_ERROR);
    }
    $out = $response;
    return $out;
}

function checkBookmarkPage($b){

    $debug = 'in checkBookmarkPage'. "\n";
   $out = null;
    // is this part of a series?
    if (isset($b['bookmark']['series'])){
        // go through series bookmark to find chapter information
        if (isset($b['bookmark']['series']->chapters)){
           $chapters = $b['bookmark']['series']->chapters;
           foreach ($chapters as $chapter){
               if ($chapter->filename == $b['filename']){
                $out = $chapter;
               }
           }
        }
        // this should not happen
        else{
            $out =  $b['bookmark']['book'];
        }
    }
    // this is a basic page from the library and does not have any data?
    else{
        $out =  $b['bookmark']['book'];
    }
    return $out;
}