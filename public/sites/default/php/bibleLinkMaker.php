<?php

myRequireOnce ('create.php');
myRequireOnce ('getLatestContent.php');
myRequireOnce('writeLog.php');

function bibleLinkMaker($p){

    $debug = 'in bibleLinkMaker' . "\n";
    if (!isset($p['text'])){
        trigger_error("'p[text] is not set in bibleLinkMaker", E_USER_ERROR);
        $debug .= 'p[text] is not set' . "\n\n\n";
        return $out;
    }
    $text = $p['text'];
    writeLogError('bibleLinkMaker-16-text', $text);
    // patterns - what is a valid item to appear just before a book name
    $patterns = array (' ', '(', '-', '&mdash;',';', '<td>', '<li>', '>');
    // get booknames
    $sql = "SELECT distinct name  FROM dbm_bible_book_names WHERE language_iso= '" . $p['language_iso'] . "'  ORDER BY name";

    $result = sqlBibleMany($sql);
    $debug_count = 0;
    while($data = $result->fetch_array()){
        $book = $data['name'];
        $spaces_in_bookname = mb_substr_count($book, ' ');
        foreach ($patterns as $pattern){
            $find = $pattern . $book;
            $find_length = mb_strlen($find);
            $pattern_length = mb_strlen($pattern);
            if (mb_strpos($text, $find )){
                $count_find = mb_substr_count($text, $find);
                $start = 1;
                for ($i=1; $i <= $count_find; $i++){
                    $book_start = mb_strpos($text, $find , $start);
                    $next = $book_start;
                    if ($pattern == ' '){
                        $next = $book_start + 1;
                    }
                    $book_end = mb_strpos($text, ' ', $next);
                    if ($spaces_in_bookname >= 1){
                        $book_end = mb_strpos($text, ' ', $book_end + 1);
                    }
                    // we should now be at the end of the book
                    $book_length = $book_end - $book_start;
                    $book_text = mb_substr($text, $book_start, $book_length);
                    // so, is the next char a number, : or -
                    $see_next = true;
                    $count_spaces = 0;
                    $chapter_start = $book_end + 1; // there should be a space after the Bible Book
                    // is there a verse after the book?
                    while ($see_next == true && $count_spaces < 12){
                        $letter_check = $chapter_start + $count_spaces;
                        $next_char = mb_substr($text, $letter_check, 1 );
                        if (is_numeric($next_char) || $next_char == ':' || $next_char == '-'){
                            $count_spaces++;
                            $verses = true;
                        }
                        else{
                            $see_next = false;
                        }
                    }
                    if ($count_spaces == 0){
                        $debug .= "No verses for $book \n";
                    }
                    else{

                        $reference_end = $letter_check - 1;
                        $real_book_start = $book_start +  $pattern_length;
                        $length = $reference_end - $book_start - $pattern_length + 1;
                        $reference_text = mb_substr($text,$real_book_start, $length );
                        $debug .= "$reference_text \n";
                        $real_length = $length + $pattern_length;
                        $new = $pattern. '<span class="bible-link">' . $reference_text . '</span>';
                        $text = substr_replace($text, $new, $book_start, $real_length);
                    }
                    $start = $book_start + mb_strlen($new);
                    $debug= "bookstart was $book_start and the new start is $start for $find";

                    $debug_count++;
                }
            }
        }
    }

    $p['text'] = $text;
    createContent($p);
    $p['scope'] = 'page';
    unset($p['recnum']);
    $out = getLatestContent($p);
     writeLogError('bibleLinkMaker-86-out', $out);
    return $out;
}