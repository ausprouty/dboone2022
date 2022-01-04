<?php
myRequireOnce ('createLanguages.php');
myRequireOnce ('publishDestination.php');
myRequireOnce ('publishFiles.php');
myRequireOnce ('publishLanguagesAvailable.php');


function publishLanguages($p){
    $publishDestination = publishDestination($p);
    $debug = 'In publishLanguages with publishDestination '. $publishDestination . "\n";
    $creator =   "\n" .'&nbsp; <!--- Created by publishLanguages -->&nbsp; '.  "\n";
    $selected_css = 'sites/default/styles/cardGLOBAL.css';
    $p['country_dir'] = $publishDestination . $p['country_code'] . '/';
     // get language footer in publishOEpublish.php
    $footer = publishLanguageFooter($p);
    //
    //find series data
    //
    $sql = "SELECT * FROM content
        WHERE  country_code = '". $p['country_code'] ."'
         AND filename = 'languages'
        ORDER BY recnum DESC LIMIT 1";
    $debug .= $sql. "\n";
    $data = sqlArray($sql);
    //
    // create page
    //
    $text = createLanguages($p, $data);
    if ($text){
        $fname = publishDestination($p). 'content/'. $p['country_dir']. 'languages.html';
        $debug .= 'Creating ' . $fname. "\n";
        $text =  $text . $creator;
        $debug .= $text. "\n";
        publishFiles( $p['destination'], $p, $fname, $text, STANDARD_CSS, STANDARD_CARD_CSS);
        //
        // update records
        //
        $time = time();
        $sql= null;
        if ($p['destination'] == 'publish'){
             $sql = "UPDATE content
            SET publish_date = '$time', publish_uid = '". $p['my_uid'] . "'
            WHERE country_code = '". $p['country_code']. "'
            AND filename = 'languages'
            AND publish_date IS NULL";
        }
        if ($p['destination'] == 'staging'){
             $sql = "UPDATE content
            SET prototype_date = '$time', prototype_uid = '". $p['my_uid'] . "'
            WHERE country_code = '". $p['country_code']. "'
            AND filename = 'languages'
            AND prototype_date IS NULL";
        }
        if ($sql){
            sqlArray($sql,'update');
        }
        //$debug .= $sql. "\n";

        $debug .= 'About to enter publishLanguagesAvailable' . "\n";
        // now update languages Available
        publishLanguagesAvailable($p);
        writeLog('publishLanguages', $debug);
    }
    return TRUE;
}
