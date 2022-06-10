<?php

function bibleBrainGetBooks($p){
	$output = '';
    $url = 'https://4.dbt.io/api/bibles/'. $p['fileset'] . '/book?';
    $url .= 'v=4&key=';
    $response =  bibleBrainGet($url);
	foreach ($response->data as $book){
       $output .= $book->book_id .'  '. $book->book_id_osis . "\n";
	    $sql = "INSERT into bible_brain_book_id (book_id,bible_brain_book_id) values
            ('$book->book_id_osis', '$book->book_id')";
        $result = sqlBibleInsert($sql);
	}
	return $output;
}
