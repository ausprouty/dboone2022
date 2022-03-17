<?php

require_once ('../.env.api.remote.php');
require_once ('../../default/php/myRequireOnce.php');
myRequireOnce('sql.php');

echo "In Fix Bible Book Names<br>\n";
$sql = 'SELECT * FROM dbm_bible_book_names';
$query  = sqlMany($sql);
$conn = new mysqli(HOST, USER, PASS, DATABASE_CONTENT);
while($data = $query->fetch_array()){
    $book_id = $data['book_id'];
    $language_iso= $data['language_iso'];
    $book_name= $data['name'];
    $book_name = $conn->real_escape_string($data['name']);
    $sql = "INSERT INTO bible_book_names (book_id, language_iso, book_name) VALUES
          ('$book_id', '$language_iso', '$book_name')";
    $result = $conn->query($sql);
}
echo "done";