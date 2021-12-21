<?php
myRequireOnce ('publishFiles.php');
myRequireOnce ('publishLanguagesAvailable.php');
myRequireOnce ('createLanguages.php');
//
// this updates Languages for a given country
//  AND also updates the Languges Available for the site
//
function publishLanguages($p){
    $p['status'] = 'publish';
    if (!isset ($p['debug'])){
        $p['debug'] = '';
    }
    $p['debug'] .= 'In publishLanguages '. "\n";
    $selected_css = 'sites/default/styles/cardGLOBAL.css';
    $p['country_dir'] = ROOT_PUBLISH_CONTENT . $p['country_code'] . '/';
    // get language footer in prototypeOEpublish.php
    $footer = prototypeLanguageFooter($p);
    //
    //find series data
    //
    $sql = "SELECT * FROM content
        WHERE  country_code = '". $p['country_code'] ."'
         AND filename = 'languages'
        ORDER BY recnum DESC LIMIT 1";
    $p['debug'] .= $sql. "\n";
    $data = sqlArray($sql);
    //
    // create page
    //
    $text = createLanguages($p, $data);
    if ($text){
        $fname = $p['country_dir']. 'languages.html';
        $p['debug'] .= $fname. "\n";
        $result['body'] .= '<!--- Created by prototypeLanguages-->' . "\n";
        publishFiles( $p['destination'], $p, $fname, $text, STANDARD_CSS, STANDARD_CARD_CSS);
        //
        // update records
        //
        $time = time();
        $sql = null;
        if ($p['destination'] == 'publish'){
            $sql = "UPDATE content
                SET publish_date = '$time', publish_uid = '". $p['my_uid'] . "'
                WHERE country_code = '". $p['country_code']. "'
                AND filename = 'languages'
                AND publish_date IS NULL";
        }
        if ($p['destination'] == 'prototype'){
            $sql = "UPDATE content
                SET prototype_date = '$time', prototype_uid = '". $p['my_uid'] . "'
                WHERE country_code = '". $p['country_code']. "'
                AND filename = 'languages'
                AND prototype_date IS NULL";
        }
        if ($sql){
             sqlArray($sql,'update');
        }
        // now update languages Available
        publishLanguagesAvailable($p);
    }
    return true;
}
