<?php
myRequireOnce ('sql.php');
myRequireOnce('bibleGetPassage.php');
myRequireOnce('bibleDbtArray.php');

function getPageOrTemplate ($p){
    $out = [];
    $out['debug'] = 'In getPageOrTemplate'. "\n";
    $bookmark = json_decode($p['bookmark']);
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
    $out['content']= $result;
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
    if ($p['template']){
        $template_file = ROOT_EDIT_CONTENT .  $p['country_code'] . '/'. $p['language_iso']  .'/templates/'. $p['template'];
        $out['debug'] .=  $template_file  ."\n";

        if (!file_exists ($template_file)){
            $out['debug'] .='NO PAGE or template found' ."\n";
            $out['content'] ['text'] = 'Referenced template not found: ' .  $template_file;
            return $out;
        }
        if (file_exists ($template_file)){
            $template = file_get_contents($template_file);
            // see if you can insert Bible Test
            if (strpos($template, '[BiblePassage]') !== FALSE){
                $nt = isset($bookmark->language->bible_nt) ? $bookmark->language->bible_nt : null ;
                $ot = isset($bookmark->language->bible_ot) ? $bookmark->language->bible_ot : null ;
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
                $out['debug'] .= $p['entry']. "\n";
                // ok to here
                $dbt = createBibleDbtArrayFromPassage($p);
                $out['debug'] .= $dbt['debug']. "\n";
                $out['debug'] .= json_encode($dbt['content'], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) "\n";
                $out['content'] ['text'] = 'please write routine to add ' . $ref . '</br>';
                if ($dbt['content']){ // but this may be an array
                    $out['debug'] .= '$dbt[content] found'. "\n";
                    if ($dbt['content']['collection_code'] == 'NT'){
                        $dbt['content']['bid'] = $ot;
                    }
                    else{
                        $dbt['content']['bid'] = $nt;
                    }
                }
                foreach ($dbt['content'] as $key=>$value){
                    $out['debug'] .= $key . ' -- '. $value . "\n";
                }
                // OK to here
                // get Bible passage
                $passage = bibleGetPassage($dbt['content']);
                $out['debug'] .= $passage['debug'];
                // OK to here
                if (isset($passage['content'])){
                    foreach ($passage['content'] as $key=>$value){
                        $out['debug'] .= $key . ' -- '. $value . "\n";
                    }
                    $passage['content']['readmore'] = isset($bookmark->language->read_more)? $bookmark->language->read_more :'RED MORE';
                    $bible_block = _create_bible_block($passage['content']);
                    $template = mb_ereg_replace("\[BiblePassage\]", $bible_block['content'], $template);
                    if (strpos($template, '[Reference]') !== false){
                        $template = mb_ereg_replace("\[Reference\]", $passage['content']['reference'] , $template);
                    }
                    $out['content'] ['text']  =  $template;
                    $out['debug'] .= $bible_block['debug'];
                }
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
function _getBiblePassage(){

}

function _create_bible_block($bible_content){
    $out = [];
    $out['content'] =
    '<div class="bible_container bible">' .
        '<p class ="reference">' .
        $bible_content['reference'] .
        '</p>' .
        $bible_content['text'] .
        '<p class = "bible">
            <a class="bible-readmore" href="' .
            $bible_content['link'].
            '">'.
            $bible_content['readmore'] .
            '</a>
        </p>
    </div>';
    $out['debug'] = 'Bible Block reference:' . $bible_content['reference'] ."\n";
    return $out;
}