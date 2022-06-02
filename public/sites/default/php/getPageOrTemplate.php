<?php
myRequireOnce ('bibleDbtArray.php');
myRequireOnce ('bibleGetPassage.php');
myRequireOnce ('sql.php');
myRequireOnce ('version2Text.php');

function getPageOrTemplate ($p){

    $debug = 'In getPageOrTemplate'. "\n";
    $ok = true;
    if (!isset($p['filename'])){
        $debug .= "No filename\n";
        $ok = false;
    }
    if (!isset($p['folder_name'])){
        $debug .= "No folder name\n";
        $ok = false;
    }
    if (!isset($p['language_iso'])){
        $debug .= "No Language\n";
        $ok = false;
    }
    if (!$ok){
        $message = "Missing filename, foldername or language in  getPageOrTemplate ";
        writeLogError('getPageOrTemplate', $p);
        trigger_error( 'getPageOrTemplate', E_USER_ERROR);
        return NULL;
    }
    if (isset($p['bookmark'])){
        $bookmark = json_decode($p['bookmark']);
    }
    else{
        myRequireOnce ('bookmark.php');
        $debug = "No bookmark given, so looking for it now";
        $bookmark = bookmark ($p);
    }
    $p['template']= null;
    if (isset($bookmark->book->template)){
         $p['template'] = $bookmark->book->template;
    }
    $sql = "SELECT * from content
            WHERE country_code = '". $p['country_code'] . "'
            AND language_iso = '" . $p['language_iso'] . "'
            AND folder_name = '" . $p['folder_name'] . "'
            AND  filename = '" . $p['filename'] . "'
            ORDER BY recnum DESC LIMIT 1";

    $result = sqlArray($sql);
    if ($result['recnum']){
        $result['text']= version2Text($result['text']);
        $out= $result;
        return $out;
    }
    // if no content is there a template?
    if ($p['template']){
        $template_file = ROOT_EDIT_CONTENT .  $p['country_code'] . '/'. $p['language_iso']  .'/templates/'. $p['template'];
        $debug .=  $template_file  ."\n";
        if (!file_exists ($template_file)){
            $debug .='NO PAGE or template found' ."\n";
            $out ['text'] = 'Referenced template not found: ' .  $template_file;
            return $out;
        }
        if (file_exists ($template_file)){
            //writeLogDebug('getPageOrTemplate-64', $template_file);
            $template = file_get_contents($template_file);
            // see if you can insert Bible Test
            if (strpos($template, '[BiblePassage]') !== FALSE){
                $nt = isset($bookmark->language->bible_nt) ? $bookmark->language->bible_nt : null ;
                $ot = isset($bookmark->language->bible_ot) ? $bookmark->language->bible_ot : null ;
                $read_more = isset($bookmark->language->read_more)? $bookmark->language->read_more :'RED MORE';
                $ref = isset($bookmark->page->reference) ? $bookmark->page->reference : null ;
                $debug .= 'New Testament: '. $nt . "\n";
                $debug .= 'Old Testament: '. $ot . "\n";
                $debug .= 'Reference: '. $ref . "\n";
                 //writeLogDebug('getPageOrTemplate-75', $debug);
                // are all parameters here?
                if (!$ot || !$nt || !$ref){
                    $debug .='template found but missing one or more values' ."\n";
                    $out ['text'] = '<h1>Text from Template but missing something</h1>'. $debug .$template;
                    return $out;
                }
                // create dbt array
                $p['entry'] = $bookmark->page->reference;
                $debug = 'Entry is ' . $p['entry']. "\n";
                // ok to here
                $dbt_study = createBibleDbtArrayFromPassage($p);
                $debug .= json_encode($dbt_study, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ."\n";
                $debug .= "\n" .'I am about to enter _bible_block driver with '. "\n";
                $debug .= "nt of $nt, and ot of $ot and read_more of $read_more\n";
                //writeLogDebug('getPageOrTemplate-90', $debug);
                // are all parameters here?
                // get Bible Block
                $bible_block = _bible_block_driver($dbt_study, $nt, $ot, $read_more);
                //writeLogDebug('getPageOrTemplate-94', $bible_block);
                $template = mb_ereg_replace("\[BiblePassage\]", $bible_block, $template);
                if (strpos($template, '[Reference]') !== false){
                    $template = mb_ereg_replace("\[Reference\]", $bookmark->page->reference , $template);
                }
                //writeLogDebug('getPageOrTemplate-99', $template);
                $out ['text']  =  $template;
                $debug .= $bible_block['debug'];
                return $out;
            }
            else{
                $out ['text']  =  $template;
                //writeLogDebug('getPageOrTemplate-106', $template);
                return $out;
            }
        }
    }
    else{
        $debug .='No page or template found' ."\n";
        //writeLogDebug('getPageOrTemplate-113', $debug);
        $out ['text'] = 'Please enter this page.';
    }
    return $out;
}
function _bible_block_driver($dbt_study, $nt, $ot, $read_more){
    $out= '';
    $bible_block = '';
    foreach ($dbt_study as $dbt){
        if ($dbt['collection_code'] == 'NT'){
            $dbt['bid'] = $nt;
        }
        else{
            $dbt['bid'] = $ot;
        }
        $passage = bibleGetPassage($dbt);
        //writeLogDebug('_bible_block_driver-129', $passage);
        if (isset($passage['text'])){
            $passage['read_more'] = $read_more;
            $out.= _create_bible_block($passage);
        }
    }
    return $out;
}

function _create_bible_block($bible_content){

    $out =
        '<p class ="reference">' .
        $bible_content['reference'] .
        '</p>' .
        $bible_content['text'] .
        '<p class = "bible">
            <a class="bible-readmore" href="' .
            $bible_content['link'].
            '">'.
            $bible_content['read_more'] .
            '</a>
        </p>';
    //writeLogDebug('_create_bible_block-152', $out);
    return $out;
}