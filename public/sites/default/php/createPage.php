<?php
myRequireOnce ('bookmark.php');
myRequireOnce ('writeLog.php');

//content is an array of one record content data

function createPage($p, $content){

    $debug = "I am in createPage\n";
    writeLog('createPage-8-p', $p);
    writeLog('createPage-9-data', $content);

    // get bookmark
    $b['recnum'] =  $p['recnum'];
    $b['library_code'] = $p['library_code'];
    $bookmark = bookmark($b);
    writeLog('createPage-18-bookmark', $bookmark);
    $p['selected_css'] = isset($bookmark['book']->style)? $bookmark['book']->style: STANDARD_CSS;
    if (!isset($bookmark['book']->format)){
       $debug = 'Bookmark[book]->format not set for  recnum ' . $b['recnum'] .' in create page with library code ' . $b['library_code'] ."\n";
       $debug .= json_encode($out, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
       writeLogError('createPage-20', $debug);
    }

    if ($bookmark['book']->format == 'series'){
        $debug .= 'This is in a series for ' . $content['folder_name'] . "\n";
         writeLog('createPage-27-debug', $debug);
        $this_template = myGetPrototypeFile('pageInSeries.html', $p['destination']);
        writeLog('createPage-29-template',$this_template);
        // insert nav bar and set ribbon value and link value
        $nav = myGetPrototypeFile('navRibbon.html', $p['destination']);
        writeLog('createPage-31-nav', $nav);
        $this_template = str_replace('[[nav]]', $nav, $this_template);
        $ribbon = isset($bookmark['library']->format->back_button) ? $bookmark['library']->format->back_button->image : DEFAULT_BACK_RIBBON;
        writeLog('createPage-36-ribbon', $ribbon);
        // this is always going back to the index; and we don't want that with Transferable Concepts
        // TODO: allow going back to previous study
        $link_value =   $bookmark['language']->folder . '/'. $content['folder_name'].'/index.html';
        writeLog('createPage-39-linkvalue',  $link_value);
        // compute $page_title_and_image for series
        if (isset($bookmark['page']->image)){
            if ($bookmark['page']->image){
                $page_image = $bookmark['page']->image;
                $page_title_and_image_value  = '<img  src ="'. $page_image  .  '"/>';
                writeLog('createPage-45-pagetitle',  $page_title_and_image_value);
            }
        }
        // you do not have an image to insert
        if (!isset($page_title_and_image_value)){
            $title = $bookmark['page']->title;
              writeLog('createPage-52-title', $title);
            if (isset($bookmark['page']->count)){
                if ($bookmark['page']->count != ''){
                    $page_title_and_image_value =
                    '<div class="block {{ dir }}">
                        <div class="chapter_number {{ dir }}"><h1>'. $bookmark['page']->count .'.'. '</h1></div>
                        <div class="chapter_title {{ dir }}"><h1>'  . $title . '</h1></div>
                    </div>';
                }
                else{
                    $page_title_and_image_value  = '<h1>' . $title . '</h1>';
                }
            }
            else{
                $page_title_and_image_value  = '<h1>' . $title . '</h1>';
            }
             writeLog('createPage-66-title',  $page_title_and_image_value);

        }
    }
    writeLog('createPage-72-debug', $debug);
    // values for page that is not part of a series
    if ($bookmark['book']->format == 'page'){
        $debug .= 'This is a page' . "\n";
        writeLog('createPage-77-debug', $debug);
        $this_template = myGetPrototypeFile('page.html', $p['destination']);
         // insert nav bar
         $nav = myGetPrototypeFile('navRibbon.html', $p['destination']);
         $this_template = str_replace('[[nav]]', $nav, $this_template);
         $ribbon = isset($bookmark['library']->format->back_button->image) ? $bookmark['library']->format->back_button->image : DEFAULT_BACK_RIBBON;
         $debug .= "ribbon is $ribbon\n";
        // this will work if there is no special library index.
        $index = 'index.html';
        if ($p['library_code'] != 'library'){
            $index = $p['library_code'] . '.html';
        }
        $link_value =  '/content/'. $bookmark['language']->folder .  '/'.$index;
        $page_text_value = $content['text'];
        // compute $page_title_and_image_value
        if (isset($bookmark['book']->image->image)){
            $page_image = $bookmark['book']->image->image;
        }
        else{ // legacy data
            $page_image = $bookmark['book']->image;
        }


        $img =  $page_image;
        $page_title_and_image_value  = '<img  src ="'. $img  .  '"/>';
        $page_title_and_image_value .= '<h1>'. $bookmark['book']->title . '</h1>';
    }
   writeLog('createPage-103-debug', $debug);
    if (!isset($this_template)){
        $debug .= 'FATAL ERROR. No Page Template for recnum'. $p['recnum'] . "\n";
        writeLog('createPage-77-debug', $debug);
        writeLogError('createPage', $debug);
        return NULL;
    }
    writeLog('createPage-109-debug', $debug);
    $local_js = '<script> This is my script </script>';
    $dir_value = $bookmark['language']->rldir;
    $card_style_value = '/sites/default/styles/cardGLOBAL.css';
    $book_style_value  =  $bookmark['book']->style;
    $page_text_value = $content['text'];
    $version_value = $p['version'];
     // get language footer
    $debug .= "I am about to go to publishLanguageFooter\n ";
      // get language footer in prototypeOEpublish.php
    $footer = publishLanguageFooter($p); // returns  $footer

    $debug .= "Here is my template\n";
    $debug .= $this_template . "\n";
    $debug .= "That was my template\n";
    writeLog('createPage-122-debug', $debug);
     // define placeholders
    $placeholders = array(
        '{{ dir }}',
        '{{ card.style }}',
        '{{ book.style }}',
        '{{ link }}',
        '{{ ribbon }}',
        '{{ page.title_and_image }}',
        '{{ page.text }}',
        '{{ version }}',
        '{{ footer }}'
    ); //
    $replace = array(
        $dir_value,
        $card_style_value,
        $book_style_value,
        $link_value,
        $ribbon,
        $page_title_and_image_value,
        $page_text_value,
        $version_value,
        $footer
    );
    $debug .= "Ribbon:  $ribbon\n ";
    $text = str_replace($placeholders, $replace, $this_template);
    $text = str_replace('{{ dir }}',  $dir_value, $text); // because dir is inside of page_title_and_image_valu

    $debug .= "text:\n  $text\n ";
    writeLog('createPage-148-debug', $debug);
    writeLog('createPage-149-text', $text);
    return $text;
}