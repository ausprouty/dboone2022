<?php

myRequireOnce('dirCreate.php');

function createLanguages($p, $data){
    $debug = 'In createLanguages'. "\n";
    $debug .= $data['text']. "\n";
    $text = json_decode($data['text']);
    if (!isset($text->languages)){
         $message = "in createLanguages and  no value for text->languages ";
        trigger_error( $message, E_USER_ERROR);
        return null;
    }
     // replace placeholders in template
    $main_template = $book_template = myGetPrototypeFile('languages.html', $p['destination']);
    $placeholders = array(
        '{{ choose_language }}',
        '{{ more_languages }}',
    );
    $choose = isset($text->choose_language) ? $text->choose_language: 'Choose Language';
    $more = isset($text->more_languages) ? $text->more_languages: 'More Languages';
    $replace = array(
       $choose,
       $more
    );
    $main_template = str_replace($placeholders, $replace, $main_template);
    // get chapter template
    $sub_template = myGetPrototypeFile('language.html', $p['destination']);
    $placeholders = array(
        '{{ link }}',
        '{{ language.name }}',
    );
    $temp = '';
    foreach ($text->languages as $language){
        $status = false;
        if ($p['destination'] == 'website'){
            $status = $language->publish;
        }
        else{
            if (isset($book->prototype)){
                $status = $language->prototype;
            }
        }
        if ($status  == true ){
            $replace = array(
                '/content/'.$p['country_code']  . '/'. $language->folder. '/index.html',
                    $language->name
                );
            $temp .= str_replace($placeholders, $replace, $sub_template);
            //
            // make sure Language directory exits? Do I need this????
            //
            dirCreate('language', $p['destination'],  $p);
            //$p['language_dir'] = ' /content/'. $p['country_code']  . '/'.  $language->folder .'/';

        }
    }

    $text = str_replace('[[languages]]',$temp,  $main_template);
    //writeLog('createLanguages-60-text', $text);
    return $text;


}