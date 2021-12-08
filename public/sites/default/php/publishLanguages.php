<?php
myRequireOnce ('prototypeORpublish.php');
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
    $selected_css = 'ZZ/styles/cardGLOBAL.css';
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
    $result = createLanguages($p, $data);
    if (isset($result['body'])){
        $fname = $p['country_dir']. 'languages.html';
        $p['debug'] .= $fname. "\n";
        $result['body'] .= '<!--- Created by prototypeLanguages-->' . "\n";
        publishFiles( 'publish', $p, $fname, $result['body'], STANDARD_CSS, STANDARD_CARD_CSS);  
        //
        // update records
        //
        $time = time();
        $sql = "UPDATE content 
            SET publish_date = '$time', prototype_uid = '". $p['my_uid'] . "'
            WHERE country_code = '". $p['country_code']. "' 
            AND filename = 'languages'
            AND publish_date IS NULL";
        //$p['debug'] .= $sql. "\n";
        sqlArray($sql,'update');

        // now update languages Available
        $p = publishLanguagesAvailable($p);
    }
    return $p;
}
