<?php
 /* requires $p['language_iso']
 and $p['entry'] in form of 'Zephaniah 1:2-3'

 returns array:
     $dbt = array(
         'entry' => 'Zephaniah 1:2-3'
          'bookId' => 'Zeph',
          'chapterId' => 1,
          'verseStart' => 2,
          'verseEnd' => 3,
         'collection_code' => 'OT' ,
     );
 */
myRequireOnce('writeLog.php');

function createBibleDbtArrayFromPassage($p){
    $out = [];
    $passages = explode(';',$p['entry'] );
    foreach ($passages as $passage){
        $p['passage']= trim($passage);
        $debug .= '$p[passage] is ' .  $p['passage'] . "\n";
        $out[] = createBibleDbtArray($p);
    }
    return $out;
}
function createBibleDbtArray($p){

    $debug= "\n\n" .'I am in createBibleDbtArray'. "\n";
    //OK to here
    $language_iso = $p['language_iso'];
    $passage = $p['passage'];
    $passage = trim($passage);
    $parts = explode(' ', $passage);
    $book = $parts[0];
    if ($book == 1 || $book == 2 || $book == 3){
        $book .= ' '. $parts[1];
    }
    $book_lookup = $book;
    if ($book_lookup == 'Psalm'){
        $book_lookup = 'Psalms';
    }

    // get valid bookId

    $sql = "SELECT book_id, testament FROM hl_online_bible_book
        WHERE  $language_iso  = '$book_lookup' LIMIT 1";
    //writeLog('createBibleDbtArray57-' . time(), $sql);

    $data = sqlBibleArray($sql);
    if (is_array($data)){
        foreach ($data as $key => $value){
            $debug .= $key . '=>' . $value . "\n";
        }
    }
    else{
        $debug .= "No valid data from Bible Array\n";
    }

    //writeLog('createBibleDbtArray64-' . time(), $debug);

    if (isset($data['book_id'])){
        $debug .= $data['book_id'] . "\n";
       // //writeLog('createBibleDbtArray68-' . time(), $debug);
    }
    if (!isset($data['book_id'])){
        $debug .= 'trying to find in English' . "\n";
        //writeLog('createBibleDbtArray72-' . time(), $debug);
        // try English if language_iso does not work
        $sql = "SELECT book_id, testament FROM hl_online_bible_book
        WHERE  eng  = '$book_lookup' LIMIT 1";
        $data = sqlBibleArray($sql);
        if (!isset($data['book_id'])){
           writeLogError('createBibleDbtArray-78-sql', $sql );
            $message = "in createBibleDbtArray Book ID not found ";
            return FALSE;
        }
    }
    //writeLog('createBibleDbtArray78-' . time(), $debug);
    // pull apart chapter
    $pass = str_replace($book, '', $passage);
    $debug .= 'pass is ' . "$pass\n";
    $pass = str_replace(' ' , '', $pass);
    $debug .= 'pass is ' . "$pass\n";
    $i = strpos($pass, ':');
    $debug .= 'i ' . "$i\n";
    if ($i !== FALSE){
        $chapterId = substr($pass, 0, $i);
        $verses = substr($pass, $i+1);
        $i = strpos ($verses, '-');
        if ($i !== FALSE){
            $verseStart = substr($verses, 0, $i);
            $verseEnd = substr($verses, $i+1);
        }
        else{
            $verseStart = $verses;
            $verseEnd = $verses;
        }
    }
    else{
        $chapterId = $p;
        $verseStart = 1;
        $verseEnd = 200;
    }
    $dbt_array = array(
        'entry' => $passage,
        'bookId' => $data['book_id'],
        'chapterId' => $chapterId,
        'verseStart' => $verseStart,
        'verseEnd' => $verseEnd,
        'collection_code' => $data['testament'],
    );
    foreach ($dbt_array as $key => $value){
        $debug .= $key . ' => ' . $value . "\n";
    }
    $debug .= 'at the end of dbt' . "\n";
    //writeLog('createBibleDbtArray', $debug);
    $out = $dbt_array;
    return $out;
}
