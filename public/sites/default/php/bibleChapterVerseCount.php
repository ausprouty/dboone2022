<?php
/* requires
 $dbt_array = array(
        'entry' => $passage,
        'bookId' => $book_details['book_id'],
        'bookNumber'=> $book_details['book_number'],
        'bookLookup'=> $book_lookup,
        'chapterId' => $chapterId,
        'verseStart' => $verseStart,
        'verseEnd' => $verseEnd,
        'collection_code' => $book_details['testament'],
    );
    returns number of last verse in this chapter
*/

function bibleChapterVerseCount($dbt_array){
    $book =$dbt_array['bookNumber'];
    $chapter = $dbt_array['chapterId'];
    $sql="SELECT verses FROM bible_verse_count
            WHERE book = '$book' AND chapter= '$chapter'";
    $data= sqlArray($sql);
    return $data['verses'];
}