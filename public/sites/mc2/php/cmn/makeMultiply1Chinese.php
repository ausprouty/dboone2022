<?php
return;
require_once ('../../.env.api.remote.mc2.php');
myRequireOnce ('sql.php');
myRequireOnce ('.env.cors.php');
myRequireOnce ('bookmark.php');
myRequireOnce ('bibleDbtArray.php');
myRequireOnce ('bibleGetPassage.php');
myRequireOnce ('create.php');

// get Bible passages and Video details
$text = [];
$item = [];
$p = array(
    'country_code' => 'M2',
    'language_iso' => 'cmn',
    'folder_name'=> 'multiply1',
    'filetype' => 'html',
    'series' => 'multiply',
    'starting_value' => 101,
    'ending_value' => 199,
    'my_uid'=> 999, //computer
);
$response = bookmark($p);
$p['bookmark']  = $response['content'];
$text_file = file_get_contents(ROOT_EDIT .  '/api/chinese/M2cmnDotted.txt');
$lessons = explode("<--->", $text_file);
$index = 0;
$ul_start = FALSE;
$new = 'This is new';
$reference = null;
foreach ($lessons as $lesson){
    $lines = explode("\n", $lesson);
    foreach ($lines as $line){
        // see if ordered list is done.
        if ($ul_start == TRUE){
            if (mb_strpos($line, '*') === FALSE){
                $new .= '</ul>'. "\n";
                $ul_start = FALSE;
            }
        }
        if (mb_strpos($line, '<bible text>') !== false){
            $new .= _bible_text($line, $reference);
        }
        elseif  (mb_strpos($line, '<h2') !== false){
            $new .= _h2($line);
        }
        elseif (mb_strpos($line, '<h3') !== false){
            $new .= _h3($line);
        }
        elseif (mb_strpos($line, '<p') !== false){
            $new .= _p($line);
        }
        elseif (mb_strpos($line, '<reference>') !== false){
            $reference = _reference($line);
        }
        elseif (mb_strpos($line, '<reveal') !== false){
            $new .= _reveal($line);
        }
        elseif (mb_strpos($line, '*') === 0 || mb_strpos($line, '<li') !== false){
            if ($ul_start == FALSE){
                $new .= _ul_start($line);
                $ul_start = TRUE;
            }
            else{
                $new .= _ul_continue($line); 
            }
        }
        else{
            $new .= '<p>'. $line . '</p>';
        }
    }
   
    echo $new;
    return $new;
  
}

return;

function _h1($line){
    $end = mb_strpos($line, '>');
    $instruction = trim(mb_substr($line, 0 , $end + 1));
    $word = trim( mb_substr($line, $end + 1));
    $line = '<h1>'. $word . '</h1>' ."\n";
    $line = ''; // we don't use the title
    return $line;

}
function _h2($line){
    $end = mb_strpos($line, '>');
    $instruction = trim(mb_substr($line, 0 , $end + 1));
    $word = trim( mb_substr($line, $end + 1));
    $line = '<h2>'. $word . '</h2>' ."\n";
    return $line;

}
function _h3($line){
    $end = mb_strpos($line, '>');
    $instruction = trim(mb_substr($line, 0 , $end + 1));
    $word = trim( mb_substr($line, $end + 1));
    $line = '<h3>'. $word . '</h3>' ."\n";
    return $line;

}
function _p($line){
    $end = mb_strpos($line, '>');
    $instruction = trim(mb_substr($line, 0 , $end + 1));
    $word = trim( mb_substr($line, $end + 1));
    $line = '<p>'. $word . '</p>' ."\n";
    return $line;

}
function _reference($line){
    $end = mb_strpos($line, '>');
    $instruction = trim(mb_substr($line, 0 , $end + 1));
    $word = trim( mb_substr($line, $end + 1));
    return $word;

}
function _reveal($line){
    $end = mb_strpos($line, '>');
    $instruction = trim(mb_substr($line, 0 , $end + 1));
    $word = trim( mb_substr($line, $end + 1));
    if ($instruction == '<reveal summary>'){
        $heading = '';
        $start = mb_strpos($word, ':');
        $word = trim( mb_substr($word, $start + 1));
        $line = '<div class="reveal">&nbsp;
<hr />
<p class="up">小结</p>
<p class="up">'. $word . '</p>
<hr /></div>'. "\n";
    }
    return $line;

}
function _ul_start($line){
    $out = '<ul>' . "\n";
    $out .= _ul_continue($line);
    return $out;
}
function _ul_continue($line){
    if (mb_strpos($line, '<li') !== false){
        $end = mb_strpos($line, '>');
        $instruction = trim(mb_substr($line, 0 , $end + 1));
        $word = trim( mb_substr($line, $end + 1));
    }
    else{
        $word = trim (mb_substr($line, 1));
    }
    $out = '<li>' . $word . '</li>' . "\n";
    return $out;
}
function _bible_text($line, $reference){
    for ($i = 0; $i <= 9; $i++){
        $line = str_replace($i, '<sup>' . $i . '</sup>', $line);
    }
    $line = str_replace('<sup></sup>', '', $line);
    $out = '<div class="reveal bible">&nbsp;
    <hr />
    <p>'. $reference . '</p>';
    $out .= '<p>'. $line . '</p>' . "\n";
    $out .= '<!-- end bible -->

    <p><a class="readmore" href="[Link]" target="_blank">[Read More] </a></p>
    
    <hr /></div>';

    return $out;
}
