<?php
myRequireOnce ('createLanguages.php');
myRequireOnce ('publishDestination.php');
myRequireOnce ('publishFiles.php');
myRequireOnce ('publishLanguagesAvailable.php');


function publishLanguages($p, $allowed = ['all']){
    $publishDestination = publishDestination($p);

    $creator =   "\n" .'&nbsp; <!--- Created by publishLanguages -->&nbsp; '.  "\n";
    $selected_css = 'sites/default/styles/cardGLOBAL.css';
    $p['country_dir'] = '/content/' . $p['country_code'] . '/';
     // get language footer in publishOEpublish.php
    $footer = publishLanguageFooter($p);
    //
    //find series data
    //
    $sql = "SELECT * FROM content
        WHERE  country_code = '". $p['country_code'] ."'
         AND filename = 'languages'
        ORDER BY recnum DESC LIMIT 1";

    $data = sqlArray($sql);
    //
    // create page
    //
    $text = createLanguages($p, $data, $allowed);
    if ($text){
        // "/content/M2/languages.html"
        $fname =   $p['country_dir'] . 'languages.html';
        $text =  $text . $creator;
        publishFiles( $p['destination'], $p, $fname, $text, STANDARD_CSS, STANDARD_CARD_CSS);
        //
        // update records
        //
        $time = time();
        $sql= null;
        if ($p['destination'] == 'website'){
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

        publishLanguagesAvailable($p);

    }
    return TRUE;
}
