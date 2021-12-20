<?php
myRequireOnce ('prototypeORpublish.php');

/* This should only show countries which have been authorized for publishing.

   The link should take you to one of two locations:

    if more than one language is authorized for publishing:   CountryCode/languages.html
    if only one language is authorized for publishing:  CountryCode/LanguageIso/index.html
*/
function prototypeCountries($p){
    // declare variables

    $p['debug'] = 'in prototypeCountries' . "\n";
    $p['status'] = 'prototype';
     //
    //find country page from recnum
    //
    $sql = 'SELECT * FROM content
        WHERE  recnum = "'.  $p['recnum'] . '"';
    $p['debug'] .= $sql. "\n";
    $data = sqlArray($sql);
    if (!$data){return $p;}
    //
    // create page
    //
    // get main template and do some replacing
    $main_template = myGetPrototypeFile('countries.html');
    $placeholders = '{{ version }}';
    $replace = $p['version'];
    $main_template = str_replace($placeholders, $replace, $main_template);
    // get sub template and do some replacing
    $sub_template = myGetPrototypeFile('country.html');
    $p['debug'] .=  $sub_template . "\n";
    $countries = json_decode($data['text']);
    $country_template = '';
    foreach ($countries as $country){
        if ($country->prototype){
            $p['debug'] .=  $country->code . "\n";
            $image = '/images/country/' . $country->image;
            $link = '/content/'. prototypeCountryLink($country->code);
            $placeholders = ['{{ link }}', '{{ country.image }}','{{ country.name }}','{{ country.english }}'];
            $replace = [ $link, $image , $country->name , $country->english];
            $country_template .= str_replace($placeholders, $replace, $sub_template);
        }
    }
    // add sub template content
    $p['debug'] .=  $country_template . "\n";
    $main_template = str_replace('[[countries]]', $country_template, $main_template);

    // write countries file
    $main_template .= '<!--- Created by prototypeCountries-->' . "\n";
    $file = ROOT_PROTOTYPE_CONTENT .'index.html';
    publishFiles( 'prototype', $p, $file, $main_template,   STANDARD_CSS,  STANDARD_CARD_CSS);

    $fname = ROOT_PROTOTYPE  .'index.html';
    publishFiles( 'prototype', $p, $fname, $main_template,   STANDARD_CSS,  STANDARD_CARD_CSS);

    //
    // update records
    //
    $time = time();
    $sql = "UPDATE content
        SET prototype_date = '$time', prototype_uid = '". $p['my_uid'] ."'
        WHERE  filename = 'countries'
        AND prototype_date IS NULL";
    //$p['debug'] .= $sql. "\n";
    sqlArray($sql, 'update');
    return $p;
}

function prototypeCountryLink($country_code){
    $sql = "SELECT text FROM content
    WHERE  country_code = '". $country_code ."'
    AND filename = 'languages'
    AND folder_name = ''
    AND prototype_date != ''
    ORDER BY recnum DESC LIMIT 1";
    $data = sqlArray($sql);
    $languages = json_decode($data['text']);
    $link = null;
    $count = 0;
    foreach ($languages->languages as $language){
        if (isset($language->prototype)){
            if ($language->prototype == true){
                    $link = $language->folder . '/index.html';
                    $count++;
                }
        }
    }
    if ($count != 1){
        $link = $country_code . '/languages.html';
    }
    return $link;
}