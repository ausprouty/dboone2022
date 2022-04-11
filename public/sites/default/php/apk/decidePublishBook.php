<?php
myRequireOnce('folderList.php');

function decidePublishBook($p, $book){
    $status = false;
    $dir_content =  $p['dir_apk'] .'folder/content/'.$p['country_code'] .'/'.  $p['language_iso'] .'/';
    $books_in_apk = folderList($dir_content);
    if ($p['destination'] == 'website'){
        $status = $book->publish;
    }
    else{
        if (isset($book->prototype)){
            $status = $book->prototype;
        }
    }
    if (!in_array($book->code, $books_in_apk )){
        $status = false;
    }
   return $status;
}
