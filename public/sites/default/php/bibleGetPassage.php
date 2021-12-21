<?php
myRequireOnce ('sql.php');
/*requires $p as array:
        'entry' => 'Zephaniah 1:2-3'
        'bookId' => 'Zeph',  
        'chapterId' => 1, 
        'verseStart' => 2, 
        'verseEnd' => 3,
        'bid' => '123' // this is bid being used.
        'collection_code' => 'OT' ,
        'version_ot' => '123', // this is bid 
        'version_nt' => '134'
        )


    return aut as an array:
        debug:
       content: as an array with 
		'passage_name' => 'Zephaniah 1:2-3'
		'bible' => 'bible verse text',
        'link' => 'https://biblegateway.com/passage/?search=John%201:1-14&version=LSG',
        'publisher' => 'Louis Segond (LSG) by Public Domain'
       
*/

function bibleGetPassage($p){
    
    $debug = 'I came into  bibleGetPassage' . "\n";
    // make sure bid is set
    if (!isset($p['bid'])){
        if (isset($p['collection_code'])){
            if ($p['collection_code'] == 'OT'){
                if (isset($p['version_ot'])){
                    $p['bid'] = $p['version_ot'];
                }
            }
            if ($p['collection_code'] == 'NT'){
                if (isset($p['version_nt'])){
                    $p['bid'] = $p['version_nt'];
                }
            }
        }
        if (!isset($p['bid'])){
            $debug .= 'p[bid] is not set' . "\n\n\n";
            //writeLog ('bibleGetPassage45-' . time(), $debug);
            return $out;
        }
    }
    $sql = "SELECT * FROM dbm_bible WHERE bid = " . $p['bid'];
    //$debug = $sql . "\n";
    $data = sqlBibleArray($sql);
    if ($data['right_to_left'] != 't'){
        $p['rldir'] = 'ltr';
    }
    else{
        $p['rldir'] = 'rtl';
    }
   
    //$debug = $data['source']  . "\n";
    if ($data['source'] == 'bible_gateway'){
        myRequireOnce ('bibleGetPassageBiblegateway.php');
        $p['version_code'] = $data['version_code'];
        $res = bibleGetPassageBiblegateway($p);
        $out = $res['content'];
        $debug .= $res['debug'];
        return $out;
    }
    if ($data['source']  == 'dbt'){
        myRequireOnce ('bibleGetPassageDBT.php');
        $p['damId'] = $data['dam_id'];
        $out = bibleGetPassageDBT($p);
        return $out;
    }
    return $out;
}