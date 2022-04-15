<?php
myRequireOnce ('findLibraries.php');
myRequireOnce ('getLatestContent.php');
myRequireOnce ('writeLog.php');
// MC2 and other clients have multiple libraries
function getBooksForLanguage($p){
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
                foreach ($book_list as $book){
                    if ($book->publish){
                        $book->library_code = $p['library_code'];
                        $book->language_iso = $p['language_iso'];
                        $book->country_code = $p['country_code'];
                        $book->folder_name = $book->code;
                        $book->recnum= _getBooksForLanguageRecnum ($book);
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
  $content =getLatestContent($params);
  return $content['recnum'];
}