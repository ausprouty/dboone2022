<?php
myRequireOnce ('writeLog.php');
myRequireOnce ('dirCreate.php');
myRequireOnce ('myGetPrototypeFile.php');

function createLibrary($p, $text) {
     /* Return a container for the books in this library.
    This will be used to prototype these books by prototypeLibraryandBooks.

    */

    $out=[];
    $out['books'] = [];// used by publishLibraryAndBooks
    $debug = "createLibrary\n";
    $filename =  $p['library_code'];
     //
    // get bookmark
    //
    $b['recnum'] = $p['recnum'];
    $b['library_code'] = $p['library_code'];
    $bookmark = bookmark($b);

    //
    // get template for library and fill in library details
    //
    $body = myGetPrototypeFile('library.html', $p['destination']);
    //
    //  Format Navigation area;
    //

    $no_ribbon = isset($text->format->no_ribbon)? isset($text->format->no_ribbon) :false;
    if ($no_ribbon){
        $debug .= 'prototypeLibrary was asked not to set ribbon at top '. "\n";
        $nav = '';
        $ribbon = '';
    }
    else{
        $debug .= 'Ribbon In prototypeLibrary '. "\n";
        $nav = myGetPrototypeFile('navRibbon.html', $p['destination']);
        $ribbon = isset($text->format->back_button)?$text->format->back_button->image : DEFAULT_BACK_RIBBON ;
    }
    $body = str_replace('[[nav]]',$nav, $body);
    //
    //  Replace other variables for Library
    //
    $library_image = '';
    if (isset($text->format->image->image)){
        $library_image =   '/sites/' . SITE_CODE .  $text->format->image->image;
    }
    $body = str_replace('{{ library.image }}', $library_image, $body);

    $library_text= isset($text->text)? $text->text : null;
    $body = str_replace('{{ library.text }}', $library_text, $body);

    $country_index =  dirCreate('country', $p['destination'], $p);
    $root_index = '/content/index.html';
   // if ($p['destination'] !== 'nojs'){
    //        if ($filename == 'library'){
    //        $navlink = $root_index;
    //    }
    //    else{
    //        $navlink = $country_index;
    //    }
    //}
    //else{
        $navlink = '../index.html';
   // }

    // get language footer in prototypeOEpublish.php
    $footer = publishLanguageFooter($p);
    //writeLog('createLibrary-62-navlink', $navlink);
    $placeholders = array(
        '{{ navlink }}',
        '{{ ribbon }}',
        '{{ version }}',
        '{{ footer }}',
        '{{ language.rldir }}'
    );
    $replace = array(
        $navlink,
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
        $debug .= 'Using template for bookImage '. "\n";
    }
    $book_template = myGetPrototypeFile('' . $temp, $p['destination']);
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

            $status = false;
            if ($p['destination'] == 'website'){
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
                    $out['books'][$code] = $book;
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
                        $book_image =   $country_index .  $bookmark['language']->image_dir .'/' . $book->image ;
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

    $out['body'] = str_replace('[[books]]',$books, $body);
    //writeLog('createLibrary', $debug);
    return $out;
}
