<?php

// see  https://stackoverflow.com/questions/7381900/php-decoding-and-encoding-json-with-unicode-characters

myRequireOnce ('prototypeORpublish.php');
myRequireOnce ('getLatestContent.php');
myRequireOnce ('writeLog.php');

/* returns bookmark values

   requires: $p['recnum'] and $p['library_code];

   returns array $bookmark

*/
function bookmark ($p){
    $bm = [];
    $out['debug'] = 'I entered Bookmark' . "\n";
    $b['bookmark'] = null;

    writeLog ('bookmark-20-', $p);


    if (isset($p['recnum'])){
         // be sure to add this in for library since you can not yet derive it.
        $b['library_code'] = isset($p['library_code'])?$p['library_code']:null;
        $out['debug'] .= 'library_code ' . $b['library_code'] . "\n\n";
        // find other parameters for bookmark from recnum
        $out['debug'] .= 'recnum is ' . $p['recnum'] . "\n";
        $starting = objectFromRecnum($p['recnum']);
        $b['country_code'] = $starting->country_code;
        $out['debug'] .= 'b[country_code] is ' . $b['country_code'] . "\n";
        $b['language_iso'] = $starting->language_iso;
        $out['debug'] .= 'b[language_iso] is ' . $b['language_iso'] . "\n";
        $b['folder_name'] = $starting->folder_name;
        $out['debug'] .= 'b[folder_name] is ' . $b['folder_name'] . "\n";
        $b['filename'] = $starting->filename;
        $out['debug'] .= 'b[filename] ' . $b['filename'] . "\n\n";
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
        $response = checkBookmarkCountry($b);
        $b['bookmark']['country'] = $response['content'];
        $out['debug'] .= $response['debug'] . "\n";
         // writeLog ('bookmark-53-country-', $out['debug']);

        if ($b['language_iso']){
            $response = checkBookmarkLanguage($b);
            $b ['bookmark'] ['language'] = $response['content'];
            $out['debug'] .=  $response['debug']. "\n";
            // writeLog ('bookmark-59-language', $out['debug']);

            if (isset($b['library_code'])){
                $response = checkBookmarkLibrary($b);
                $b['bookmark']['library']  = $response['content'];
                $out['debug'] .=  $response['debug']. "\n";

                if ($b['folder_name']){
                    $response = checkBookmarkSeries($b);
                    $b['bookmark']['series'] = $response['content'];
                    $out['debug'] .=  $response['debug']. "\n";
                    $response = checkBookmarkBook($b);
                    $b['bookmark']['book'] = $response['content'];
                    $out['debug'] .=  $response['debug']. "\n";
                    if ($b['filename']){
                        $response = checkBookmarkPage($b);
                        $b['bookmark']['page'] = $response['content'];
                        $out['debug'] .=  $response['debug'];
                    }
                }
            }
        }
    }
    $out['content'] = $b['bookmark'];
    if (isset($p['scope'])){
      writeLog( 'bookmark-'.$p['scope'] , $b['bookmark']);

    }
    else{
        writeLog( 'bookmark', $b['bookmark']);
    }

    return $out;
}
function checkBookmarkCountry($b){
    $out = [];
    $out['debug'] = 'in checkBookmarkCountry'. "\n";
    $out['content'] = null;
    $b['scope'] = 'countries';
    $res = getLatestContent($b);
    $out['debug'] .= $res['debug'];
    $out['debug'] .= 'response is'.  $res['content']['text']."\n";
    $response = json_decode($res['content']['text']);
    if (!$response){
        writeLog('checkBookmarkCountry', $out['debug']);
        trigger_error("No response in checkBookmarkCountry", E_USER_ERROR);
    }
    foreach ($response as $country){
        if ($country->code == $b['country_code']){
            $out['content'] = $country;
        }
    }
    return $out;
}
function checkBookmarkLanguage($b){
    $out = [];
    $out['debug'] = 'in checkBookmarkLanguage'. "\n";
    $out['content'] = null;
    $b['scope'] = 'languages';
    $res = getLatestContent($b);
    $response = json_decode($res['content']['text']);
    if (!$response){
        writeLog('ERROR - checkBookmarkLanguage', $out['debug']);
        trigger_error("No response in checkBookmarkLanguage", E_USER_ERROR);
    }
    if (isset($response->languages)){
        foreach ($response->languages as $language){
            if ($language->iso == $b['language_iso']){
                $out['content'] = $language;
            }
        }
    }else{
        $out['debug'] .= 'NO response for languages'.  "\n";
    }

    return $out;
}
// no longer used
function checkBookmarkLibraries($b){
    $out = [];
    $out['debug'] = 'in checkBookmarkLibraries'. "\n";
    $out['content'] = null;
    $b['scope'] = 'libraryNames';
    // find possible libraries with books
    $names = getLatestContent($b);
    if (!$names){
        writeLog('checkBookmarkLibraries', $out['debug']);
        trigger_error("No names in checkBookmarkLibraries", E_USER_ERROR);
    }
    foreach ($names as $name){
        if ($name !== 'index' && $name !== 'languages'){
            $b['library_code'] = $name;
            $b['scope'] = 'library';
            $res = getLatestContent($b);
            $out['debug'] .= $res['debug'];
            $out['debug'] .= 'response is'.  $res['content']['text'] ."\n";
            $response = json_decode($res['content']['text']);
            $books = $response->books;
            foreach($books as $book){
                $out['content'][] = $book;
            }
        }
    }
    return $out;
}
function checkBookmarkLibrary($b){
    $out = [];
    $out['debug'] = 'in checkBookmarkLibrary'. "\n";
    $out['content'] = null;
    if ($b['library_code'] !== 'index'){
         $b['scope'] = 'library';
    }
    else{
        $b['scope'] = 'libraryIndex';
    }
    $res = getLatestContent($b);
    $out['debug'] .= $res['debug'];
    $out['debug'] .= 'response is'.  $res['content']['text'] ."\n";
    $r = json_decode($res['content']['text']);
    if (!$r){
        writeLog('ERROR - checkBookmarkLibrary-parameters', $b);
        writeLog('ERROR - checkBookmarkLibrary-debug', $out['debug']);
         writeLog('ERROR - checkBookmarkLibrary-res',  $res['content']);
         $message = "No r in checkBookmarkLibrary for ". $b['library_code'];
        trigger_error( $message , E_USER_ERROR);
    }
    // legacy data does not have ['books'] so move data there
    if (isset($r->books)){
        $response = $r;
    }
    else{
        $response = new stdClass();
        $response->books = $r;
    }
    $out['content'] = $response;
    return $out;
}
function checkBookmarkBook($b){
    $out = [];
    $out['debug'] = 'In check BookmarkBook' . "\n";
    $out['content'] = null;
    $this_book = $b['folder_name'];
    if ($this_book == 'pages'){
        $this_book = $b['filename'];
    }
    $out['debug'] .= 'This book is '.  $this_book . "\n";
    if (isset($b['bookmark']['library']->books)){
        $books = $b['bookmark']['library']->books;
        foreach ($books as $book){
            if (!isset($book->code)){
                $out['debug'] .= 'Book code is not set' . "\n";
                $code = isset($book->name) ? $book->name : NULL;
            }
            else{
                $code = $book->code;
            }
            if ($code == $this_book){
                $out['debug'] .= 'Found this code in book array' . "\n";
                $out['debug'] .= json_encode($book, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) . "\n";
                $out['content'] = $book;
            }
        }}
    return $out;
}
function checkBookmarkSeries($b){
    $out = [];
    $out['debug'] = 'in checkBookmarkSeries'. "\n";
    $out['content'] = null;
    $b['scope'] = 'series';
    $res = getLatestContent($b);
    $out['debug'] .= $res['debug'];
    $out['debug'] .= 'response is'.  $res['content']['text'] ."\n";
    $response = json_decode($res['content']['text']);
    if (!$response){
        writeLog('checkBookmarkSeries', $out['debug']);
        trigger_error("No response in checkBookmarkSeries", E_USER_ERROR);
    }
    $out['content'] = $response;
    return $out;
}

function checkBookmarkPage($b){
    $out = [];
    $out['debug'] = 'in checkBookmarkPage'. "\n";
    $out['content'] = null;
    // is this part of a series?
    if (isset($b['bookmark']['series'])){
        // go through series bookmark to find chapter information
        if (isset($b['bookmark']['series']->chapters)){
           $chapters = $b['bookmark']['series']->chapters;
           foreach ($chapters as $chapter){
               if ($chapter->filename == $b['filename']){
                $out['content'] = $chapter;
               }
           }
        }
        // this should not happen
        else{
            $out['content'] =  $b['bookmark']['book'];
        }
    }
    // this is a basic page from the library and does not have any data?
    else{
        $out['content'] =  $b['bookmark']['book'];
    }
    return $out;
}