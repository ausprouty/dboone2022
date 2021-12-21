<?php
myRequireOnce ('publishFiles.php');
myRequireOnce ('prototypeLanguagesAvailable.php');
myRequireOnce ('createLanguages.php');

function prototypeLanguages($p){
    $p['status'] = 'prototype';
    $out = [];
    if ( !isset($out['debug'])){
        $out['debug'] = null;
    }
    $out['debug'] .= 'In prototypeLanguages with ROOT_PROTOTYPE_CONTENT '. ROOT_PROTOTYPE_CONTENT . "\n";
    $creator =   "\n" .'&nbsp; <!--- Created by prototypeLanguages -->&nbsp; '.  "\n";
    $selected_css = 'sites/default/styles/cardGLOBAL.css';
    $p['country_dir'] = ROOT_PROTOTYPE_CONTENT . $p['country_code'] . '/';
     // get language footer in prototypeOEpublish.php
    $footer = prototypeLanguageFooter($p);
    //
    //find series data
    //
    $sql = "SELECT * FROM content 
        WHERE  country_code = '". $p['country_code'] ."' 
         AND filename = 'languages' 
        ORDER BY recnum DESC LIMIT 1";
    $out['debug'] .= $sql. "\n";
    $data = sqlArray($sql);
    //
    // create page
    //
    $result = createLanguages($p, $data);
    if (isset($result['body'])){
        $fname = $p['country_dir']. 'languages.html';
        $out['debug'] .= 'Creating ' . $fname. "\n";
        $text =  $result['body'] . $creator;
        $out['debug'] .= $text. "\n";
        publishFiles( 'prototype', $p, $fname, $text, STANDARD_CSS, STANDARD_CARD_CSS);  
        //
        // update records
        //
        $time = time();
        $sql = "UPDATE content 
            SET prototype_date = '$time', prototype_uid = '". $p['my_uid'] . "'
            WHERE country_code = '". $p['country_code']. "' 
            AND filename = 'languages'
            AND prototype_date IS NULL";
        //$p['debug'] .= $sql. "\n";
        sqlArray($sql,'update');
        $out['debug'] .= 'About to enter prototypeLanguagesAvailable' . "\n";
        // now update languages Available
        $res = prototypeLanguagesAvailable($p);
        if (isset($res['debug'])){
            $out['debug'] .= $res['debug'];

        }
    }
    return $out;
}
