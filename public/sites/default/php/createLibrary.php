<?php
function createLibrary($p, $text) {
     /* Return a container for the books in this library. 
    This will be used to prototype these books by prototypeLibraryandBooks.
    */
    $p['books'] = []; 
    $out=[];
    $filename =  $p['library_code']; 
     //
    // get bookmark
    //
    $b['recnum'] = $p['recnum'];
    $b['library_code'] = $p['library_code'];
    $bm = bookmark($b);
    $bookmark = $bm['content'];
    //
    // get template for library and fill in library details
    //
    $body = myGetPrototypeFile('library.html');
    //
    //  Format Navigation area;
    //
   
    $no_ribbon = isset($text->format->no_ribbon)? isset($text->format->no_ribbon) :false;
    if ($no_ribbon){
        $p['debug'] .= 'prototypeLibrary was asked not to set ribbon at top '. "\n";
        $nav = '';
        $ribbon = '';
    }
    else{
        $p['debug'] .= 'Ribbon In prototypeLibrary '. "\n";
        $nav = myGetPrototypeFile('navRibbon.html');
        $ribbon = isset($text->format->back_button)?$text->format->back_button->image : DEFAULT_BACK_RIBBON ;
    } 
    $body = str_replace('[[nav]]',$nav, $body);
    //
    //  Replace other variables for Library
    //
  
    $val = isset($text->format->image->image) ? $text->format->image->image : null;
    $body = str_replace('{{ library.image }}',$val, $body);
    
    $val = isset($text->text)? $text->text : null;
    $body = str_replace('{{ library.text }}',$val, $body);

    $country_index = '/content/' . $p['country_code'] .'/'. $p['language_iso'];
    $root_index = '/content/index.html';
    if ($filename == 'library'){
        $link = $root_index;
    }
    else{
        $link = $country_index;
    }
    // get language footer in prototypeOEpublish.php
    $footer = prototypeLanguageFooter($p);
   
    $placeholders = array(
        '{{ link }}',
        '{{ ribbon }}',
        '{{ version }}',
        '{{ footer }}',
        '{{ language.rldir }}'
    );
    $replace = array(
        $link,
        $ribbon,
        $p['version'],
        $footer ,
        $bookmark['language']->rldir
    );
    $body = str_replace($placeholders, $replace, $body);
    //
    // select appropriate book template
    //
    $temp = 'bookTitled.html';
    if ($bookmark['language']->titles){
        $temp = 'bookImage.html';
        $p['debug'] .= 'Using template for bookImage '. "\n";
    }
    $book_template = myGetPrototypeFile('' . $temp);
    // 
    //  replace for values in book templage for each book
    //
    $books = '';
    $placeholders = array(
        '{{ link }}', 
        '{{ book.image }}', 
        '{{ book.title }}',
        '{{ language.rldir }}'
    );
    if (isset($text->books)){
        foreach ($text->books as $book){
           // _write_library_log($p, $book);
            $status = false;
            if ($p['status'] == 'publish'){
                $status = $book->publish;
            }
            else{
                if (isset($book->prototype)){
                    $status = $book->prototype;
                } 
            }
            if ($status  == true ){
                if (!isset($book->hide)){$book->hide = false;}
                if (!$book->hide){
                    // deal with legacy data
                    $code = '';
                    if (isset($book->code)){
                        $code = $book->code;
                    }
                    else if (isset($book->name)){
                        $code = $book->name;
                        $book->code = $code;
                    }
                    // you will need library code in bookmark
                    $book->library_code =  $b['library_code'];
                    // deal with any duplicates
                    $p['books'][$code] = $book;
                    // create link for series, library or page
                    if ($book->format == 'series'){
                        $this_link =  $code . '/index.html';
                    }
                    elseif($book->format == 'library'){
                        $this_link =  $code . '.html';
                    }
                    else{
                        $this_link = 'pages/'. $code . '.html';
                    }
                    // dealing with legacy data
                    if (isset($book->image->image)){
                        $book_image =  $book->image->image;
                    }
                    else{
                        $book_image =  '/content/'. $bookmark['language']->image_dir .'/' .$book->image ;
                    }
                    $replace = array(
                        $this_link,
                        $book_image,
                        $book->title,
                        $bookmark['language']->rldir
                    );
                    $books .= str_replace($placeholders, $replace, $book_template);
                }
            }
        }
    }
    $out['p'] = $p;
    $out['body'] = str_replace('[[books]]',$books, $body);
    
    return $out;
}
function _write_library_log($p, $book){
    $content = "p\n";
    foreach ($p as $key=> $value){
        $content .= "$key => $value \n";
    }
    $content = "\n\nbook\n";
    foreach ($book as $key=> $value){
        $content .= "$key => $value \n";
    }
    writeLog($filename, $content);
}
