<?php
myRequireOnce ('findLibraries.php');
myRequireOnce ('getLatestContent.php');
myRequireOnce ('writeLog.php');
// MC2 and other clients have multiple libraries
function getBooksForLangauge($p){
    $books =[];
    $p['scope'] = 'library';
    $libraries=findLibraries($p);
    foreach ($libraries as $library){
        $p['library_code'] = $library;
        $data = getLatestContent($p);
        if ($data['text']){
            $library = json_decode($data['text']);
            $book_list = $library->books;
            foreach ($book_list as $book){
                $books[]= $book;
            }

        }
    }
    return $books;
}