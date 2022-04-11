<?php

function decidePublishBook($p, $book){
    $status = false;
    if ($p['destination'] == 'website'){
        $status = $book->publish;
    }
    else{
        if (isset($book->prototype)){
            $status = $book->prototype;
        }
    }
   return $status;
}