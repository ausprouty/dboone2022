<?php

myRequireOnce ('bibleDbtArray.php');
myRequireOnce ('bibleGetPassage.php');
myRequireOnce ('sql.php');

function getPageOrTemplate ($p){

    $debug = 'In getPageOrTemplate'. "\n";
    $bookmark = json_decode($p['bookmark']);
    $p['template']= null;
    if (isset($bookmark->book->template)){
         $p['template'] = $bookmark->book->template;
    }
    $debug .= 'template is '.  $p['template'] . "\n";

    //
    $sql = "SELECT * from content
            WHERE country_code = '". $p['country_code'] . "'
            AND language_iso = '" . $p['language_iso'] . "'
            AND folder_name = '" . $p['folder_name'] . "'
            AND  filename = '" . $p['filename'] . "'
            ORDER BY recnum DESC LIMIT 1";
    $debug .= $sql . "\n";
    $result = sqlArray($sql);
    $out= $result;
    if (is_array($result)){
        foreach($result as $key=> $value){
        $debug .= $key . ' -- ' . $value . "\n";
        }
    }
    // return latest page (if it exists)
    if (isset($result['recnum'])){
        $debug .='Recnum ' . $result['recnum'] ."\n";
        return $out;
    }
    if ($p['template']){
        $template_file = ROOT_EDIT_CONTENT .  $p['country_code'] . '/'. $p['language_iso']  .'/templates/'. $p['template'];
        $debug .=  $template_file  ."\n";

        if (!file_exists ($template_file)){
            $debug .='NO PAGE or template found' ."\n";
            $out ['text'] = 'Referenced template not found: ' .  $template_file;
            return $out;
        }
        if (file_exists ($template_file)){
            $template = file_get_contents($template_file);
            // see if you can insert Bible Test
            if (strpos($template, '[BiblePassage]') !== FALSE){
                $nt = isset($bookmark->language->bible_nt) ? $bookmark->language->bible_nt : null ;
                $ot = isset($bookmark->language->bible_ot) ? $bookmark->language->bible_ot : null ;
                $ref = isset($bookmark->page->reference) ? $bookmark->page->reference : null ;
                $debug .= 'New Testament: '. $nt . "\n";
                $debug .= 'Old Testament: '. $ot . "\n";
                $debug .= 'Reference: '. $ref . "\n";
                // are all parameters here?
                if (!$ot || !$nt || !$ref){
                    $debug .='template found but missing one or more values' ."\n";
                    $out ['text'] = '<h1>Text from Template but missing something</h1>'. $debug .$template;
                    return $out;
                }

                // create dbt array
                $p['entry'] = $bookmark->page->reference;
                $debug .= $p['entry']. "\n";
                // ok to here
                $dbt = createBibleDbtArrayFromPassage($p);
                $debug .= json_encode($dbt,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) . "\n";
                $out ['text'] = 'please write routine to add ' . $ref . '</br>';
                if ($dbt){ // but this may be an array
                    $debug .= '$dbt found'. "\n";
                    if ($dbt['collection_code'] == 'NT'){
                        $dbt['bid'] = $ot;
                    }
                    else{
                        $dbt['bid'] = $nt;
                    }
                }
                foreach ($dbt as $key=>$value){
                    $debug .= $key . ' -- '. $value . "\n";
                }
                // OK to here
                // get Bible passage
                $passage = bibleGetPassage($dbt);
                $debug .= $passage['debug'];
                // OK to here
                if (isset($passage['content'])){
                    foreach ($passage['content'] as $key=>$value){
                        $debug .= $key . ' -- '. $value . "\n";
                    }
                    $passage['content']['readmore'] = isset($bookmark->language->read_more)? $bookmark->language->read_more :'RED MORE';
                    $bible_block = _create_bible_block($passage['content']);
                    $template = mb_ereg_replace("\[BiblePassage\]", $bible_block['content'], $template);
                    if (strpos($template, '[Reference]') !== false){
                        $template = mb_ereg_replace("\[Reference\]", $passage['content']['reference'] , $template);
                    }
                    $out ['text']  =  $template;
                    $debug .= $bible_block['debug'];
                }
                return $out;
            }

        }
    }
    else{
        $debug .='No page or template found' ."\n";
        $out ['text'] = 'Please enter this page.';

    }
    return $out;
}
function _getBiblePassage(){

}

function _create_bible_block($bible_content){

    $out =
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
    $debug = 'Bible Block reference:' . $bible_content['reference'] ."\n";
    return $out;
}