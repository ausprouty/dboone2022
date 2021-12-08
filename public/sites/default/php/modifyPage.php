<?php
myRequireOnce('prototypeFindFilesInPage.php');
myRequireOnce('createPage.php');
myRequireOnce('getTitle.php');
myRequireOnce('modifyHeader.php');
myRequireOnce('modifyJavascript.php');
myRequireOnce('modifyLinks.php');
myRequireOnce('modifyNoteArea.php');
myRequireOnce('modifyRevealAudio.php');
myRequireOnce('modifyRevealSummary.php');
myRequireOnce('modifyRevealTrainer.php');
myRequireOnce('modifyRevealVideo.php');
myRequireOnce('writeLog.php');
//myRequireOnce('revealVideos.php');

function modifyPage($text, $p, $data, $bookmark){
    $debug_modifyLinks = false;
    $debug_insertBibleLinks = false;
    $debug_revealSummary = true;
    $debug_revealBible = false;
    $debug_revealVideo = false;
    $debug_trainer = false;
    $debug_sendAction = false;
    $out = [];
    $out['debug'] = "\n\nIn modifyPage\n\n";
    $out['debug'] .= 'Begin bookmark'. "\n";// what is bookmark?
    foreach ($bookmark as $key => $value){
        $out['debug'] .= $key . "\n";
        if (is_array($value)){
            foreach ($value as $key2 => $value2){
                $out['debug'] .= '    '. $key2 . "\n";
                if (is_string($value2)){
                    $out['debug'] .= '        '. $value2 . "\n";
                }
            }
        }
        else{
            //$out['debug'] .= ' '. $value . "\n";
        }

    }
    $out['debug'] .= 'end of bookmark'. "\n";
    $out['debug'] .= 'read is ' . isset($bookmark['language']->read) ? $bookmark['language']->read : 'Not Set' . "\n";
    //
    // modify note fields
    //

    if (strpos($text, '"note-area"')  !== false){
        $response =  modifyNoteArea($text, $bookmark);
        $text = $response['content'];
        //add markers used by javascript
        $page = $p['country_code'] . '-'. $p['language_iso'] . '-'. $p['folder_name'] . '-'. $data['filename'] .'.html';
        $note_form_begin = '<form>'. "\n";
        $note_page =  '<input type="hidden" name ="notes_page"  id ="notes_page" value="' . $page . '">'. "\n";
        $note_form_end = '</form>';
        $text .= $note_form_begin. $note_page.  $note_form_end;
    }
    //
    // strip out open new tab
    //
    $text = str_replace('target="_blank"' ,'', $text);
    $text = str_replace('target="blank"' ,'', $text);
    $text = str_replace('<a  ' ,'<a ', $text);
    //$text = str_replace('href="http' ,' target="_blank" href="http', $text);
    //
    writeLog('modify-page-65', $text);
    //  change internal links for easy return:
    if (strpos($text, '<a href=') !== FALSE){
        $response= modifyLinks($text);
        $text = $response['content'];
        if (isset( $response['debug']) && $debug_modifyLinks){
           $out['debug'] .= $response['debug'];
        }
    }

    // popup text needs to be visible to editor but hidden in prototype and production
    if (strpos($text, 'class="popup"')!== FALSE){
        $text = str_ireplace('class="popup"', 'class="popup invisible"', $text);
    }

    if (strpos($text, '<span class="bible-link">')!== FALSE){
        $response= _insertBibleLinks($text, $p);
        $text = $response['content'];
        if (  $response['debug'] && $debug_insertBibleLinks)
        $out['debug'] .= $response['debug'];
    }

    if (strpos($text, '<div class="reveal">') !== FALSE ||  strpos($text, '<div class="reveal_big">') !== FALSE){
        $out['debug'] = "I have reveal segments \n";
        $response= modifyRevealSummary($text);
        $text = $response['content'];
        if (isset( $response['debug']) && $debug_revealSummary){
            $out['debug'] .= $response['debug'];
        }
    }

    if (strpos($text, '<div class="reveal bible">')!== FALSE){
        $response= _revealBible($text, $bookmark);
        $text = $response['content'];
        if (isset( $response['debug']) &&  $debug_revealBible){
            $out['debug'] .= $response['debug'];
        }
    }
    $out['debug'] .= 'I am about to check for film' . "\n";
    // reveal_big is used by generations
    if (strpos($text, '<div class="reveal film') !== FALSE || strpos($text, '<div class="reveal_big film') !== FALSE){
        $out['debug'] = 'I found film' . "\n";
        $response=  modifyRevealVideo($text, $bookmark);
        $text = $response['content'];
        if (isset( $response['debug']) && $debug_revealVideo){
            $out['debug'] = 'I have debug' . "\n";
            $out['debug'] .= $response['debug'];
        }
        else{
            $out['debug'] = 'I do NOT have debug' . "\n";
        }
    }
    else{
        $out['debug'] .= 'I did not find film so here is text' . "\n";
        $out['debug'] .= $text. "\n";
    }

    if (strpos($text, '<div class="reveal audio">')!== FALSE){
        $response= modifyRevealAudio($text, $bookmark);
        $text = $response['content'];
        if (isset( $response['debug'])  && $debug_trainer){
            $out['debug'] .= $response['debug'];
        }

    }
    if (strpos($text, '<div class="javascript') !== false){
        $response = modifyJavascript($text);
        $text = $response['text'];
        if (isset( $response['debug'])  && $debug_javascript){
            $out['debug'] .= $response['debug'];
        }
    }
    /* if (strpos($text, '<div class="header') !== false){
This needs to come later in the process
    }
    */
    if (strpos($text, '<div class="trainer">')!== FALSE){
        $response= modifyRevealTrainer($text);
        $text = $response['content'];
        if (isset( $response['debug'])  && $debug_trainer){
            $out['debug'] .= $response['debug'];
        }

    }
    $bad = ['<div id="bible">','<div class="bible_container bible">' ];
    $text = str_replace($bad, '<div class="bible_container">', $text );
    $text = str_replace('bible-readmore', 'readmore', $text );
   /* if (strpos($text, '<div class="bible_container">') !== FALSE){
        $response=  _bibleButtons($text);
        $text = $response['content'];
        if (isset( $response['debug'])){
            $out['debug'] .= $response['debug'];
        }
    }
    */
    //action button
    if (strpos($text, '<button class="action">') !== FALSE){
        $response=   _sendAction($text, $p, $data);
        $text = $response['content'];
        if (isset( $response['debug']) && $debug_sendAction){
            $out['debug'] .= $response['debug'];
        }

    }
    // get rid of horizontal lines and other odd things
    $text = str_replace('<hr />', '', $text);
    $text = str_replace('<li>&nbsp;', '<li>', $text);
    $text = str_replace('</a> )', '</a>)', $text);
    $out['content'] = $text;
    return $out;
}
function _insertBibleLinks($text, $p){
    $out = [];
    $source = 'biblegateway';
    $version = 'NIV';
    $out['debug'] = 'In _insertBibleLinks';
    $template = '<a class="bible-ref external-link"  target="_blank" href="[href]"> [passage] </a>';
    $href = array(
        'biblegateway' => 'https://www.biblegateway.com/passage/?version=[version]&search=[search]',
    );
    $template = str_replace('[href]',$href[$source], $template );
    $template = str_replace('[version]', $version, $template);
    $count = substr_count($text,'<span class="bible-link">' );
    for ($i = 0; $i < $count; $i++){
        $pos_start = mb_strpos($text,'<span class="bible-link">');
        $pos_end = mb_strpos($text, '</span>', $pos_start);
        $length = $pos_end - $pos_start - 25;
        $start = $pos_start + 25;
        $passage = mb_substr($text, $start, $length);
        $out['debug'] .=  "passage is $passage\n";
        $search= str_replace(' ', '%20', $passage);
        $new = str_replace('[search]', $search, $template);
        $new = str_replace('[passage]', $passage, $new);
        $out['debug'] .=  "new is $new\n";
        $length = $pos_end + 7 - $pos_start;
         // from https://stackoverflow.com/questions/1252693/using-str-replace-so-that-it-only-acts-on-the-first-match
         // recalculate because not using multibyte function
         $pos_start = strpos($text,'<span class="bible-link">');
         $pos_end = strpos($text, '</span>', $pos_start);
         $length = $pos_end + 7 - $pos_start;
        $text = substr_replace($text, $new, $pos_start, $length);
        //$out['debug'] .= "\n\n\n\n\nafteR replace\n";
        //$out['debug'] .= "$text" ."\n";
    }
    $out['content'] = $text;
    return $out;
}


/* <div class="reveal bible">&nbsp;
<hr />
<p>John 10:22-30</p>
<div id="bible">
<div class="bible">
<div class="bible"><sup>22&nbsp;</sup>Then came the Festival of Dedication at Jerusalem. It was winter,<sup class="versenum">23&nbsp;</sup>and Jesus was in the temple courts walking in Solomon&rsquo;s Colonnade.<sup class="versenum">24&nbsp;</sup>The Jews who were there gathered around him, saying, &ldquo;How long will you keep us in suspense? If you are the Messiah, tell us plainly.&rdquo;
<p><sup class="versenum">25&nbsp;</sup>Jesus answered, &ldquo;I did tell you, but you do not believe. The works I do in my Father&rsquo;s name testify about me,<sup class="versenum">26&nbsp;</sup>but you do not believe because you are not my sheep.<sup class="versenum">27&nbsp;</sup>My sheep listen to my voice; I know them, and they follow me.<sup class="versenum">28&nbsp;</sup>I give them eternal life, and they shall never perish; no one will snatch them out of my hand.<sup class="versenum">29&nbsp;</sup>My Father, who has given them to me, is greater than all; no one can snatch them out of my Father&rsquo;s hand.<sup class="versenum">30&nbsp;</sup>I and the Father are one.&rdquo;</p>
</div>
<!-- end bible --><a class="readmore" href="https://biblegateway.com/passage/?search=John%2010:22-30&amp;version=NIV" target="_blank">Read More </a></div>
</div>
<hr /></div>
*/
function _revealBible($text, $bookmark){
    $out = [];
    $out['debug'] = "In _revealBible\n";
    $read_phrase = trim($bookmark['language']->read);
    $out['debug'] .= "read phrase is $read_phrase\n";
    $template = '<button id="Button[id]" type="button" class="collapsible bible">[Show]</button>';
    $template .= '<div class="collapsed" id ="Text[id]">';
    $count = substr_count($text,'<div class="reveal bible">' );
    $out['debug'] .=  "count is $count\n";
    for ($i = 0; $i < $count; $i++){
        $pos_start = mb_strpos($text,'<div class="reveal bible"');
        $out['debug'] .=  "pos_start is $pos_start\n"; // old is correct
        $pos_end = mb_strpos($text, '</p>', $pos_start);
        $length = $pos_end - $pos_start + 4;
        $old = mb_substr($text, $pos_start, $length);
        $out['debug'] .=  "old is $old\n"; // old is correct
        $word = trim(strip_tags($old));
        $word = str_replace('&nbsp;', '', $word);
        $word = str_replace("\n", '', $word);
        $word = str_replace('  ', ' ', $word);
        $word = str_replace('%', $word, $read_phrase);
        $new = str_replace('[id]', $i, $template);
        $new = str_replace('[Show]', $word, $new);
        $out['debug'] .=  "length is $length\n";
        $out['debug'] .=  "word is $word\n";
        $out['debug'] .=  "new is $new\n";
         // from https://stackoverflow.com/questions/1252693/using-str-replace-so-that-it-only-acts-on-the-first-match
         // recalculate because not using multibyte function
        $pos_start = strpos($text,'<div class="reveal bible"');
        $pos_end = strpos($text, '</p>', $pos_start);
        $length = $pos_end - $pos_start + 4;
        $text = substr_replace($text, $new, $pos_start, $length);
        $out['debug'] .=  "\n\n$text\n";
    }
    $out['content'] = $text;
    return $out;
}

function _sendAction($text, $p, $data){
    $out = [];
    $out['debug'] = 'In _sendAction'. "\n";
    $page = $p['country_code'] . '-'. $p['language_iso'] . '-'. $p['folder_name'] . '-'. $data['filename'] .'.html';
    $action = '<button id="sendAction" class="action hidden" onclick="sendAction(\'[filename]\', \'[title]\' )" >';
    $action = str_replace('[filename]', $page,  $action );
    $response = getTitle($p['recnum']);
    $title = $response['content'];
    $action = str_replace('[title]', $title,  $action );
    $text = str_replace('<button class="action">', $action, $text);
    $out['debug'] .= 'Leaving _sendAction'. "\n";
    $out['content'] = $text;
    return $out;
}
