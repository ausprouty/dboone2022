<?php
myRequireOnce ('copyGlobal.php');
myRequireOnce ('dirCreate.php');
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
	copyGlobal(dirCreate('country', 'edit',  $p, 'images/'),
               dirCreate('country', $p['destination'],  $p, 'images/'));
    copyGlobal(dirCreate('country', 'edit',  $p, 'styles/'),
               dirCreate('country', $p['destination'],  $p, 'styles/'));

    //
    // make sure Language directories are current
    //
    copyGlobal(dirCreate('language', 'edit',  $p, 'images/'),
               dirCreate('language', $p['destination'],  $p, 'images/'));
    copyGlobal(dirCreate('language', 'edit',  $p, 'styles/'),
               dirCreate('language', $p['destination'],  $p, 'styles/'));

    $text = json_decode($data['text']);
    $p['country_footer'] = isset($text->footer) ? $text->footer : null;
    // replace placeholders
    $body = '<div class="page_content">'. "\n";
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
    if ($p['destination'] == 'website'){
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