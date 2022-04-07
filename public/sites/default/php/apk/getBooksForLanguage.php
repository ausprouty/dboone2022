<?php
myRequireOnce ('findLibraries.php');
myRequireOnce ('getLatestContent.php');
myRequireOnce ('writeLog.php');
// MC2 and other clients have multiple libraries
function getBooksForLanguage($p){
    //writeLogDebug('getBooksForLanguage-p', $p);
    $books =[];
    $p['scope'] = 'library';
    $libraries=findLibraries($p);
    foreach ($libraries as $library){
        $p['library_code'] = $library;
        $data = getLatestContent($p);
        if ($data['text']){
            $library_data = json_decode($data['text']);
            if (isset($library_data->books)){
                $book_list = $library_data->books;
                 //writeLogDebug('getBooksForLanguage-library', $book_list);
                foreach ($book_list as $book){
                    $recnum=_getBooksForLanguageRecnum ($book);
                    writeLogAppend('getBooksForLanguage-19', $book);
                     writeLogAppend('getBooksForLanguage-19', "recnum = $recnum");
                    // only include series that are to be published
                    // TODO: How will we work with pages?
                    if ($book->publish && $recnum){
                        $book->library_code = $p['library_code'];
                        $book->language_iso = $p['language_iso'];
                        $book->country_code = $p['country_code'];
                        $book->folder_name = $book->code;
                        $book->recnum=$recnum;
                        $books[]= $book;
                    }

                }
            }

        }
    }
    usort($books, function($a, $b) {return strcmp($a->title, $b->title);});
    return $books;
}

function _getBooksForLanguageRecnum ($book){
  $params = array(
     'country_code'=> $book->country_code,
     'language_iso' => $book->language_iso,
     'folder_name' => $book->code,
     'scope' => 'series'
  );
  $content = getLatestContent($params);
  writeLogAppend ('_getBooksForLanguageRecnum', $content);
  if (!isset($content['recnum'])){
      // most likely this is a page
      writeLogAppend('_getBooksForLanguageRecnum-48', $params);
      return null;
  }
  return $content['recnum'];
}