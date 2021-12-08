<?php

/* requires $p as array:
         'entry' => 'Zephaniah 1:2-3'
          'bookId' => 'Zeph',  
          'chapterId' => 1, 
          'verseStart' => 2, 
          'verseEnd' => 3,
         'collection_code' => 'OT' ,
         'version_ot' => '123', // this is bid 
         'version_nt' => '134'
     )

    returns an array:
        book_id: "John"
        book_name: "يوحنا"
        book_order: "58"
        chapter_id: "3"
        chapter_title: "Chapter 3"
        paragraph_number: "68"
        verse_id: "16"
        verse_text: "أَحَبَّ اللهُ كُلَّ النَّاسِ لِدَرالَ حَيَاةَ الْخُلُودِ."
*/

define("KEY", '3d116e49d7d98c6e20bf0f4a9c88e4cc');
myRequireOnce ('vendor/dbt/dbt.inc');
function bibleGetPassageDBT($p){
    $out = [];
    $out['debug'] = '';
    $dbt = new Dbt (KEY);
   // todo: this link is not right
    $link = 'https://live.bible.is/bible/'.   $p['damId']  . '/'. $p['book_id']. '/'. $p['chapterId'];
    $markup = null;
    $out['error'] = false;
    $v = $dbt->getTextVerse( 
        $p['damId'], 
        $p['bookId'], 
        $p['chapterId'] , 
        $p['verseStart'], 
        $p['verseEnd'],  
        $markup
    );
    $out['debug'] .= 'first pass: '. $v . "\n";
	// try a second time
	if (!$v){
		$v = $dbt->getTextVerse( 
            $p['damId'], 
            $p['bookId'], 
            $p['chapterId'] , 
            $p['verseStart'], 
            $p['verseEnd'],
            $markup
        );
        $out['debug'] .= 'second pass: '. $v . "\n";
	}
	if ($v){
        $verses = json_decode($v);
        $text = convertDbtArrayToPassage($verses);
        $out['debug'] .= $text['debug'];
        $out['content']= [
            'reference' => $text['reference'],
            'text' => $text['text'],
            'link' => $link
        ];
    }
    else{
        $out['message'] = "Verses not found ";
        $out['error'] = true;
    }

    return $out;
}

/* receives an array of verses

        book_id: "John"
        book_name: "يوحنا"
        book_order: "58"
        chapter_id: "3"
        chapter_title: "Chapter 3"
        paragraph_number: "68"
        verse_id: "16"
        verse_text: "أَحَبَّ اللهُ كُلَّ النَّاسِ لِدَرالَ حَيَاةَ الْخُلُودِ."

    returns a passage of text with superscripted verse numbers

*/
function convertDbtArrayToPassage($verses){
    $out = [];
    $out['debug'] = '';
    $count = 0;
    $text = '<p>';
   
    foreach ($verses as $verse){
        if ($count == 0){
            $reference = $verse->book_name . ' ' . $verse->chapter_id . ': ' ;
            $reference .= $verse->verse_id;
            $out['debug'] .= 'title: '. $reference;
            $first_verse = $verse->verse_id;
            $paragraph_number = $verse->paragraph_number;
            $count++;
        }
        if ( $verse->paragraph_number !=  $paragraph_number ){
            $text .= '</p><p>';
            $paragraph_number = $verse->paragraph_number;
        }
        $text .= ' <sup>'. $verse->verse_id . '</sup>';
        $text .= $verse->verse_text;
        $current_verse = $verse->verse_id;
    }
    $text .= '</p>';
    if ($current_verse != $first_verse){
        $reference .= '-'. $current_verse;
    }
    $result = [
        'reference'=> $reference,
        'text'=> $text,
        'debug'=> $out['debug']
    ];
    return $result;
}