<?php
myRequireOnce ('bookmark.php');
myRequireOnce ('publishFiles.php');
myRequireOnce ('writeLog.php');
myRequireOnce ('myGetPrototypeFile.php');


function createSeries($p, $data){
    $debug= "createSeries\n";
    $text = json_decode($data['text']);
    // $debug .= "\n\n In prototypeSeries\n";
     // get language footer in prototypeOEpublish.php
    $footer = publishLanguageFooter($p); // returns $footer
    $b['recnum'] = $p['recnum'];
    $b['library_code'] = $p['library_code'];
    $bookmark  = bookmark($b);
    $selected_css = isset($bookmark['book']->style) ? $bookmark['book']->style :STANDARD_CSS ;
    $json_dir =   $bookmark['language']->folder .'/'.$p['folder_name'] .'/'; // for files.json
    // replace placeholders in template
    // Note: Current  is unique in that only has one book in the series.
    if ($data['folder_name'] == 'current' || $data['folder_name'] == 'youth-basics'){
        $this_template = myGetPrototypeFile('seriesCurrent.html', $p['destination']);
    }else{
        $this_template = myGetPrototypeFile('series.html', $p['destination']);
        // insert nav bar
        $nav = myGetPrototypeFile('navRibbon.html', $p['destination']);
        $this_template = str_replace('[[nav]]', $nav, $this_template);
    }

    //set placeholders
    $placeholders = array(
        '{{ language.rldir }}',
        '{{ book.style }}',
        '{{ navlink }}',
        '{{ ribbon }}',
        '{{ book.image }}',
        '{{ download_ready }}',
        '{{ book.title }}',
        '{{ book.description }}',
        '{{ json }}',
        '{{ download_now }}',
        '{{ version }}',
        '{{ footer }}'
    );
    //if ( $p['destination'] != 'nojs'){
   //     $navlink =  $bookmark['language']->folder .'/';
//}
    //else{
        $navlink = '../';
    //}
    // todo: I know this is bad code, but I need to return current to language because there is only one item in the library
    if ( $p['library_code'] !='library' && $data['folder_name'] != 'current' && $data['folder_name'] != 'youth-basics'){
        $navlink .= $p['library_code'] . '.html';
    }
    else{
        $navlink .= 'index.html';
    }
    $download_ready = isset($bookmark['language']->download_ready )? $bookmark['language']->download_ready : 'Ready for Offline Use';
    $download_now = isset($bookmark['language']->download)? $bookmark['language']->download : 'Download for Offline Use';
    $description = isset($text->description) ? $text->description : NULL;
    $ribbon = isset($bookmark['library']->format->back_button) ? $bookmark['library']->format->back_button->image : DEFAULT_BACK_RIBBON;
    $debug= "ribbon is $ribbon\n";
    $language_dir = '/content/'. $data['country_code'] .'/'. $data['language_iso'] .'/'. $data['folder_name'] .'/';
    $json = $language_dir . 'files.json';
    $p['files_json'] = '[{"url":"'.  $json .'"},' ."\n"; // rest to be filled in with chapters
    // dealing with legacy data
    if (isset($bookmark['book']->image->image)){
        $book_image = $bookmark['book']->image->image;
    }
    else{
        $book_image =  '/content/'. $bookmark['language']->image_dir .'/' . $bookmark['book']->image ;
    }
     // get language footer in prototypeOEpublish.php
    $footer = publishLanguageFooter($p); // returns  $footer
    //
    //writeLog('createSeries-71-navlink', $navlink);
    $replace = array(
        $bookmark['language']->rldir,
        $bookmark['book']->style,
        $navlink,
        $ribbon,
        $book_image,
        $download_ready,
        $bookmark['book']->title,
        $description,
        $json,
        $download_now ,
        $p['version'],
        $footer
    );
    $this_template = str_replace($placeholders, $replace, $this_template);
    //
    // get chapter template
    //
    $chapterText_template = myGetPrototypeFile('chapterText.html', $p['destination']);
    $chapterImage_template = myGetPrototypeFile('chapterImage.html', $p['destination']);
    $placeholders = array(
        '{{ link }}',
        '{{ language.rldir }}',
        '{{ chapter.title }}',
        '{{ chapter.description }}',
        '{{ chapter.image }}',
        '{{ language.rldir }}'
    );
    //
    // replace for each chapter
    //

    $chapters_text = '';
    if (isset($text->chapters)){
        foreach ($text->chapters as $chapter){
            $status = false;
            if ($p['destination'] == 'website'){
                $status = $chapter->publish;
            }
            else{
                if (isset($chapter->prototype)){
                    $status = $chapter->prototype;
                }
            }
            //_write_series_log($p, $chapter);
            if ($status  == true ){ // we only want to process those with this as true
                $filename = $json_dir . $chapter->filename . '.html';
                $filename = str_ireplace('/sites/myfriends', '', $filename);
                //writeLogAppend('createSeries-125-filename', $filename ."\n");
                $p['files_json'] .= '{"url":"'. $filename. '"},' ."\n";
                $image = null;
                if (isset($chapter->image)){
                    if ($chapter->image != ''){
                        $image = '/content/' . $bookmark['language']->folder
                            .'/' . $bookmark['book']->code .'/'. $chapter->image;
                    }
                }
                $description = isset($chapter->description) ? $chapter->description :null;
                $title = $chapter->title;
                if ($chapter->count){
                // $title = $chapter->count . '. '. $chapter->title;
                $title = '<div class="block {{ language.rldir }}">
                        <div class="chapter_number series {{ language.rldir }}">'.  $chapter->count .'.'. '</div>
                        <div class="chapter_title series {{ language.rldir }}">'  . $chapter->title . '</div>
                    </div>';
                }
                $replace = array(
                    $chapter->filename . '.html',
                    $bookmark['language']->rldir,
                    $title,
                    $description ,
                    $image,
                    $bookmark['language']->rldir,
                );
                if ($image){
                    $chapters_text .= str_replace($placeholders, $replace, $chapterImage_template );
                }
                else{
                    $chapters_text .= str_replace($placeholders, $replace, $chapterText_template );
                }
            }
        }
    }
    $out['text'] = str_replace('[[chapters]]', $chapters_text, $this_template);
    $out['p'] = $p;
    //writeLog('creatSeries', $debug);
    return $out;
}
