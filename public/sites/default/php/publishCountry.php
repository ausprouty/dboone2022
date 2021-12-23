<?php
myRequireOnce ('copyGlobal.php');
myRequireOnce ('publishDestination.php');
myRequireOnce ('publishFiles.php');


function publishCountry($p){
    $debug = 'in prototypeCountry' . "\n";
    //find country page from recnum
    //
    if (!$p['recnum']){
        $message = "in Publish Country no value for recnum ";
        trigger_error( $message, E_USER_ERROR);
        return ($p);
    }
    $sql = 'SELECT * FROM content  WHERE  recnum = "'.  $p['recnum'] . '"';
    $data = sqlArray($sql);
    if (!$data){
        $message = "in Publish Country no record for" . $p['recnum'];
        trigger_error( $message, E_USER_ERROR);
        return ($p);
    }
    //
    // make sure Country directories are current
    //
    // publishDestination in form of  '/home/globa544/usb.mc2.online/');
    $country_dir_source= ROOT_EDIT . DIR_SITE . 'content/' . $p['country_code'];
    $country_dir_destination  = publishDestination() . 'content/' . $p['country_code'];
    if (!file_exists($country_dir_destination)){
        dirMake($country_dir_destination);
    }
	copyGlobal($country_dir_source . '/images/', $country_dir_destination . '/images/');
    copyGlobal($country_dir_source .  '/styles/', $country_dir_destination . '/styles/');
    //
    // make sure Language directories are current
    //
    $language_dir_source  =  $country_dir_source . '/'. $p['language_iso'] .'/';
    $language_dir_destination  =  $country_dir_destination . '/'. $p['language_iso'] .'/';
    if (!file_exists( $language_dir_destination)){
        dirMake($language_dir_destination);
    }
    copyGlobal($language_dir_source . '/images/',$language_dir_destination . '/images/');
    copyGlobal($language_dir_source .  '/styles/', $language_dir_destination . '/styles/');

    $text = json_decode($data['text']);
    $p['country_footer'] = isset($text->footer) ? $text->footer : null;
    // replace placeholders
    $body = '<div class="content">'. "\n";
    $body .= $text->page . "\n";
    $body = str_replace('/preview/library', '/content', $body);
    $body = $body .  $p['country_footer'] ;
    $body .= '</div>'. "\n";

    //

    $file =$country_dir_destination . '/index.html';
    $p['selected_css'] = 'AU/styles/AU-freeform.css';
    // write coutnry file
    $body .= '<!--- Created by prototypeCountry-->' . "\n";
    $p = publishWrite($p, $file, $body,   $p['standard_css'],  $p['selected_css']);
    $file = $language_dir_destination . '/index.html';
    $p = publishWrite($p, $file, $body, $p['standard_css'], $p['selected_css']);

    //
    // update records
    //
    $time = time();
    $sql = null;
    if ($p['destination'] == 'publish'){
        $sql = "UPDATE content
            SET publish_date = '$time', sublish_uid = '". $p['my_uid'] ."'
            WHERE  country_code = '" . $p['country_code'] ."'
            AND folder_name = '' AND filename = 'index'
            AND publish_date IS NULL";
    }
    if ($p['destination'] == 'staging'){
        $sql = "UPDATE content
            SET prototype_date = '$time', prototype_uid = '". $p['my_uid'] ."'
            WHERE  country_code = '" . $p['country_code'] ."'
            AND folder_name = '' AND filename = 'index'
            AND prototype_date IS NULL";
    }
    $debug .= $sql. "\n";
    if ($sql){
     sqlArray($sql, 'update');
    }
    return $p;
}