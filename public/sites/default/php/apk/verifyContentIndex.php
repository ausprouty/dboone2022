<?php
myRequireOnce('getBuild.php', 'apk');
myRequireOnce('writeLog.php');
myRequireOnce('getLatestContent.php');
myRequireOnce('bookmark.php');
myRequireOnce('folderList.php');
myRequireOnce('fileWrite.php');
myRequireOnce('dirCreate.php');


function verifyContentIndex($p){
    $build = getBuild($p);
    $p['dir_apk'] = ROOT_APK .'/'.  $build. '/';
    verifyContentIndexRoot($p);
    $folders = folderList( $p['dir_apk']); // tellsl us which series to include
    $p['scope'] = 'library';
    $data = getLatestContent($p);
    $text = json_decode($data['text']);
    // get bookmark
    if (isset($root_library['recnum'])){
        $b['recnum'] = $root_library['recnum'];
    }
    else{
        $b = $p;
    }
     $b['library_code'] ='library';
    $bookmark  = bookmark($b);
    //
    // get template for library and fill in library details
    //
    $body = file_get_contents(ROOT_EDIT . 'sites/'. SITE_CODE.'/prototype/apk/contentIndex.html');
    writeLogDebug('verifyContentIndex-32', $body);
    //
    //  Replace other variables for Library
    //
    $library_image = '';
    if (isset($text->format->image->image)){
        $library_image =   '/folders/sites/' . SITE_CODE .  $text->format->image->image;
    }
    $body = str_replace('{{ library.image }}', $library_image, $body);

    $library_text= isset($text->text)? $text->text : null;
    $body = str_replace('{{ library.text }}', $library_text, $body);

    $country_index =  dirCreate('country', $p['destination'], $p);
    $root_index = '/folder/content/index.html';


    // get language footer in prototypeOEpublish.php
    $footer = publishLanguageFooter($p);
    //writeLog('createLibrary-62-navlink', $navlink);
    $placeholders = array(

        '{{ version }}',
        '{{ footer }}',
        '{{ language.rldir }}'
    );
    $replace = array(
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
    }
    $book_template = myGetPrototypeFile('' . $temp, $p['destination']);
    //
    //  replace for values in book templage for each book
    //
    $books_in_apk = verifyContentIndexBooks( $p);
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
            if (!in_array($book->code, $books_in_apk )){
                $status = false;
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
    $body = str_replace('[[books]]',$books, $body);
    $filename = $p['dir_apk'] . 'folder/content/'. $p['country_code'] . '/'. $p['language_iso'] .'/'. 'index.html';
    $fh = fopen($filename, 'w');
    if ($fh){
        fwrite($fh, $body);
        fclose($fh);
    }
    return 'done';
}
//find valid books from directory
function verifyContentIndexBooks( $p){
  // $p['dir_apk'] = ROOT_APK .'/'.  $build. '/';
  $dir_content =  $p['dir_apk'] .'folder/content/'.$p['country_code'] .'/'.  $p['language_iso'] .'/';
  $folders = folderList($dir_content);
  return $folders;

}

function verifyContentIndexRoot($p){
  $template_file = ROOT_EDIT . 'sites/'. SITE_CODE.'/prototype/apk/rootIndex.html';
  if (!file_exists($template_file)){
    writeLogError('verifyContentIndexRoot'. $template_file);
  }
  $text = file_get_contents($template_file);
  $find = [
    '{{ country_code }}',
    '{{ language_iso }}'
  ];
  $replace = [
    $p['country_code'],
    $p['language_iso']
  ];
  $text = str_replace($find, $replace, $text);
  $filename = $p['dir_apk'] . 'index.html';
  fileWrite($filename, $text, $p);
  return;

}