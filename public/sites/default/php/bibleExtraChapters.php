<?php
/* requires
 $dbt_array = array(
        'entry' => $passage,
        'bookId' => $book_details['book_id'],
        'bookNumber'=> 15,
        'bookLookup'=> $book_lookup,
        'chapterId' => 2
        'verseStart' => 5,
        'verseEnd' => 3:16,
        'collection_code' => $book_details['testament'],
    );
    returns array of extra chapters to include
*/
myRequireOnce('bibleChapterVerseCount.php');

function bibleExtraChapters($dbt){
    $extra = [];
    $starting_chapter =$dbt['chapterId'] +1 ;
    $parts = explode(':', $dbt['verseEnd']);
    $final_chapter= $parts[0];
    $final_verse = $parts[1];
    for ($i = $starting_chapter; $i <= $final_chapter; $i++){
        if ($i < $final_chapter){
           $dbt['chapterId'] = $i;
           $last_verse = bibleChapterVerseCount();
        }
        else{
            $last_verse = $final_verse;
        }
        $extra[] = array(
            'chapterId'=>$i,
            'verseStart'=> 1,
            'verseEnd'=> $last_verse,
        );
    }
    return $extra;
}