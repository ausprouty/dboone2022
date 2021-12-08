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
function createBibleDbtArrayFromPassage($p){
    $out = [];
    $out['debug']= 'I am in createBibleDbtArrayFromPassage'. "\n";
    $out['debug'] .= '$p[entry] is ' . $p['entry'] . "\n";
    //writeLog('createBibleDbtArrayFromPassage19-' . time(), $out['debug']);
    $passages = explode(';',$p['entry'] );
    foreach ($passages as $passage){
        $p['passage']= trim($passage);
        $out['debug'] .= '$p[passage] is ' .  $p['passage'] . "\n";
       // writeLog('createBibleDbtArrayFromPassage24-' . time(), $out['debug']);
        $one = createBibleDbtArray($p);
        $out['debug'] .= $one['debug'];
        $out['content'][] = $one['content'];
        //writeLog('createBibleDbtArrayFromPassage28-' . time(), $one['content']);
        if ($one['error']){
            $out['error'] = $one['error'];
        }
    }
    return $out;
}
function createBibleDbtArray($p){
    $out = [];
    $out['debug']= "\n\n" .'I am in createBibleDbtArray'. "\n";
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
            $out['debug'] .= $key . '=>' . $value . "\n";
        }
    }
    else{
        $out['debug'] .= "No valid data from Bible Array\n";
    }
    
    //writeLog('createBibleDbtArray64-' . time(), $out['debug']);

    if (isset($data['book_id'])){
        $out['debug'] .= $data['book_id'] . "\n";
       // writeLog('createBibleDbtArray68-' . time(), $out['debug']);
    }
    if (!isset($data['book_id'])){
        $out['debug'] .= 'trying to find in English' . "\n";
        //writeLog('createBibleDbtArray72-' . time(), $out['debug']);
        // try English if language_iso does not work
        $sql = "SELECT book_id, testament FROM hl_online_bible_book 
        WHERE  eng  = '$book_lookup' LIMIT 1";
        $data = sqlBibleArray($sql);
        if (!isset($data['book_id'])){
            $out['content'] = null;
            $out['error'] = true;
            $out['debug'] .=  'Book ID not found' . "\n";
            return $out;
        }
    }
    //writeLog('createBibleDbtArray78-' . time(), $out['debug']);
    // pull apart chapter
    $pass = str_replace($book, '', $passage);
    $out['debug'] .= 'pass is ' . "$pass\n";
    $pass = str_replace(' ' , '', $pass);
    $out['debug'] .= 'pass is ' . "$pass\n";
    $i = strpos($pass, ':');
    $out['debug'] .= 'i ' . "$i\n";
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
        $out['debug'] .= $key . ' => ' . $value . "\n";
    }
    $out['debug'] .= 'at the end of dbt' . "\n";
   // writeLog('createBibleDbtArray118-' . time(), $out['debug']);
    $out['error'] = false;
    $out['content'] = $dbt_array;
    return $out;
}
