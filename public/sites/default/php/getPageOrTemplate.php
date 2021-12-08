<?php
myRequireOnce ('sql.php');
myRequireOnce('bibleGetPassage.php');
myRequireOnce('bibleDbtArray.php');
myRequireOnce('version2Text.php');

function getPageOrTemplate ($p){
    $out = [];
    $out['debug'] = 'In getPageOrTemplate'. "\n";
    $ok = true;
    if (!$p['filename']){
        $out['debug'] .= "No filename\n";
        $ok = false;
    }
    if (!$p['folder_name']){
        $out['debug'] .= "No folder name\n";
        $ok = false;
    }
    if (!$p['language_iso']){
        $out['debug'] .= "No Language\n";
        $ok = false;
    }
    if (!$ok){
        return $out;
    }
    if (isset($p['bookmark'])){
        $bookmark = json_decode($p['bookmark']);
    }
    else{
        myRequireOnce('bookmark.php');
        $out['debug'] = "No bookmark given, so looking for it now";
        $res = bookmark ($p);
        $bookmark = $res['content'];
        $out['debug'] .= $res['debug'];
    }
    $p['template']= null;
    if (isset($bookmark->book->template)){
         $p['template'] = $bookmark->book->template;
    }
    $out['debug'] .= 'template is '.  $p['template'] . "\n";
    //
    $sql = "SELECT * from content
            WHERE country_code = '". $p['country_code'] . "'
            AND language_iso = '" . $p['language_iso'] . "'
            AND folder_name = '" . $p['folder_name'] . "'
            AND  filename = '" . $p['filename'] . "'
            ORDER BY recnum DESC LIMIT 1";
    $out['debug'] .= $sql . "\n";
    $result = sqlArray($sql);
    $result['text']= version2Text($result['text']);
    $out['content']= $result;
    // show values for debug
    if (is_array($result)){
        foreach($result as $key=> $value){
        $out['debug'] .= $key . ' -- ' . $value . "\n";
        }
    }
    // return latest page (if it exists)
    if (isset($result['recnum'])){
        $out['debug'] .='Recnum ' . $result['recnum'] ."\n";
        return $out;
    }
    // is there a template?
    if ($p['template']){
        $template_file = ROOT_EDIT_CONTENT .  $p['country_code'] . '/'. $p['language_iso']  .'/templates/'. $p['template'];
        $out['debug'] .=  $template_file  ."\n";
        if (!file_exists ($template_file)){
            $out['debug'] .='NO PAGE or template found' ."\n";
            $out['content'] ['text'] = 'Referenced template not found: ' .  $template_file;
            return $out;
        }
        if (file_exists ($template_file)){
            $out['debug'] .='Template found' ."\n";
            $template = file_get_contents($template_file);
            // see if you can insert Bible Test
            if (strpos($template, '[BiblePassage]') !== FALSE){
                $nt = isset($bookmark->language->bible_nt) ? $bookmark->language->bible_nt : null ;
                $ot = isset($bookmark->language->bible_ot) ? $bookmark->language->bible_ot : null ;
                $read_more = isset($bookmark->language->read_more)? $bookmark->language->read_more :'RED MORE';
                $ref = isset($bookmark->page->reference) ? $bookmark->page->reference : null ;
                $out['debug'] .= 'New Testament: '. $nt . "\n";
                $out['debug'] .= 'Old Testament: '. $ot . "\n";
                $out['debug'] .= 'Reference: '. $ref . "\n";
                // are all parameters here?
                if (!$ot || !$nt || !$ref){
                    $out['debug'] .='template found but missing one or more values' ."\n";
                    $out['content'] ['text'] = '<h1>Text from Template but missing something</h1>'. $out['debug'] .$template;
                    return $out;
                }
                // create dbt array
                $p['entry'] = $bookmark->page->reference;
                $out['debug'] = 'Entry is ' . $p['entry']. "\n";
                // ok to here
                $dbt_study = createBibleDbtArrayFromPassage($p);
                $out['debug'] .= $dbt_study['debug']. "\n";
                $out['debug'] .= json_encode($dbt_study['content']). "\n";
                $out['debug'] .= "\n" .'I am about to enter _bible_block driver with '. "\n";
                $out['debug'] .= "nt of $nt, and ot of $ot and read_more of $read_more\n";
                // get Bible Block
                $bible_block = _bible_block_driver($dbt_study['content'], $nt, $ot, $read_more);
                $out['debug'] .= $bible_block['debug'];
                $template = mb_ereg_replace("\[BiblePassage\]", $bible_block['content'], $template);
                if (strpos($template, '[Reference]') !== false){
                    $template = mb_ereg_replace("\[Reference\]", $bookmark->page->reference , $template);
                }
                $out['content'] ['text']  =  $template;
                $out['debug'] .= $bible_block['debug'];
                return $out;
            }
            else{
                $out['content'] ['text']  =  $template;
                return $out;
            }
        }
    }
    else{
        $out['debug'] .='No page or template found' ."\n";
        $out['content'] ['text'] = 'Please enter this page.';
    }
    return $out;
}
function _bible_block_driver($dbt_study, $nt, $ot, $read_more){
    $out= [];
    $out['debug']  = 'In _bible_block_driver' . "\n";
    $bible_block = '';

    foreach ($dbt_study as $dbt){
        if ($dbt['collection_code'] == 'NT'){
            $dbt['bid'] = $nt;
        }
        else{
            $dbt['bid'] = $ot;
        }
        foreach ($dbt as $key=>$value){
            $out['debug'] .= $key . ' -- '. $value . "\n";
        }
        $passage = bibleGetPassage($dbt);
        $out['debug'] .= $passage['debug'];
        if (isset($passage['content'])){
            $passage['content']['read_more'] = $read_more;
            foreach ($passage['content'] as $key=>$value){
                $out['debug'] .= $key . ' -- '. $value . "\n";
            }
            $result = _create_bible_block($passage['content']);
            $out['debug'] .= $result['debug'];
            $out['content'] = $result['content'];
        }
    }
    return $out;
}

function _create_bible_block($bible_content){
    $out = [];
    $out['content'] =
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
    $out['debug'] = 'Bible Block reference:' . $bible_content['reference'] ."\n";
    return $out;
}