<?php

myRequireOnce ('publishFiles.php');

function prototypeCountry($p){
    $p['debug'] = 'in prototypeCountry' . "\n";
    $p['status'] = 'prototype';
     //
    //find country page from recnum
    //
    $sql = 'SELECT * FROM content  WHERE  recnum = "'.  $p['recnum'] . '"';
    $p['debug'] .= $sql. "\n";
    $data = sqlArray($sql);
    if (!$data){return $p;}
    //
    // make sure Country directories are current
    //
    $country_dir = $p['root_prototype'] . $p['country_code'];
    if (!file_exists($country_dir)){
        dirMake($country_dir);
    }
	prototypeCopyDir($p['root_edit'] . $p['country_code'] . '/images/', $country_dir . '/images/');
    prototypeCopyDir($p['root_edit'] . $p['country_code'] . '/styles/', $country_dir . '/styles/');
    //
    // make sure Language directories are current
    //
    $p['language_dir'] = $country_dir . '/'. $p['language_iso'] .'/';
    if (!file_exists($p['language_dir'])){
        dirMake($p['language_dir']);
    }
    prototypeCopyDir($p['root_edit'] . $p['country_code'] .'/'. $p['language_iso']. '/images/', $p['language_dir'] . '/images/');
    prototypeCopyDir($p['root_edit'] . $p['country_code'] .'/'. $p['language_iso']. '/styles/', $p['language_dir'] . '/styles/');
   
    $text = json_decode($data['text']);
    $p['country_footer'] = isset($text->footer) ? $text->footer : null;
    // replace placeholders
    $body = '<div class="content">'. "\n";
    $body .= $text->page . "\n";
    $body = str_replace('/preview/library', '/content', $body);
    $body = $body .  $p['country_footer'] ;
    $body .= '</div>'. "\n";

    //

    $file = $country_dir . '/index.html';
    $p['selected_css'] = 'AU/styles/AU-freeform.css';
    // write coutnry file
    $body .= '<!--- Created by prototypeCountry-->' . "\n";
    $p = prototypeWrite($p, $file, $body,   $p['standard_css'],  $p['selected_css']);
    $file = $p['language_dir'] . '/index.html';
    $p = prototypeWrite($p, $file, $body, $p['standard_css'], $p['selected_css']);
    // prepare for library
    $p['country_code'] = $p['country_code'];
    $p['language_iso'] = $p['language_iso'];
   
    //
    // update records
    //
    $time = time();
    $sql = "UPDATE content 
        SET prototype_date = '$time', prototype_uid = '". $p['my_uid'] ."'
        WHERE  country_code = '" . $p['country_code'] ."' 
        AND folder_name = '' AND filename = 'index'
        AND prototype_date IS NULL";
    $p['debug'] .= $sql. "\n";
    sqlArray($sql, 'update');
    return $p;
}