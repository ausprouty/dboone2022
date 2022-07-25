<?php
/*
Expects
    language_iso
    entries separated by ;)
    version_ot
    version_nt
*/
myRequireOnce('bibleDbtArray.php');
myRequireOnce('bibleGetPassage.php');
myRequireOnce('myGetPrototypeFile.php');

function getBibleBlockToInsert($p){
    writeLogAppend('getBibleBlockToInsert-14', $p);
    $template = myGetPrototypeFile('bibleBlock.html');
    $output = array();
    $block = '';
    $passages = createBibleDbtArrayFromPassage($p);
     writeLogAppend('getBibleBlockToInsert-19', $passages);
    foreach ($passages as $passage){
        $passage['version_ot'] =$p['version_ot'];
        $passage['version_nt'] =$p['version_nt'];
        if ($passage['collection_code'] == 'NT'){
            $passage['bid']=$p['version_nt'];
        }
        else{
            $passage['bid']=$p['version_nt'];
        }
        writeLogAppend('getBibleBlockToInsert-29', $passage);
        $response = bibleGetPassage($passage);
        writeLogAppend('getBibleBlockToInsert-31', $response);
        $replace= array(
            '[Reference]',
            '[Text]',
            '[Link]',
            '[ReadMore]'
        );
        $good= array(
            $response['reference'],
            $response['text'],
            $response['link'],
            $p['read_more']
        );
        $block .= str_replace($replace, $good, $template);
        writeLogAppend('getBibleBlockToInsert-27', $block);
        $output['bible_block'] = $block;
    }
    $output['reference']= $p['entry'];
    return $output;

}