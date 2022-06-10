<?php
/*
Expects
    language_iso
    entries separated by ;)
    ot
    nt
*/
myRequireOnce('bibleDbtArray.php');

function getBibleBlockToInsert($p){
    writeLogAppend('getBibleBlockToInsert', $p);
    $output = array();
    $output['reference']= 'This is a reference';
    $output['bible_block'] = 'This is our Bible Block';
    $entries = explode(';', $p['entries']);
    foreach ($entries as $entry){
        $p['entry'] = $entry;
        $dbt = createBibleDbtArrayFromPassage($p);
        writeLogAppend('getBibleBlockToInsert-18', $dbt);
    }
    return $output;

}