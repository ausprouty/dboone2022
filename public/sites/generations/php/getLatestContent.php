<?php

myRequireOnce ('prototypeORpublish.php');
myRequireOnce ('sql.php');
myRequireOnce('moveImagesGenerations.php');

/* return latest content
   This varies from default in that we are changing the location of images.
*/
function getLatestContent($p){
    $out = [];
    $out['debug'] ='In getLatestContent' . "\n";
    if (!isset($p['scope'])){
        $out['debug'] .=  'No scope was set';
        return $out;
    }

    switch($p['scope']){
        case "countries":
            $out['debug'] .='Case is countries' . "\n";
            $sql = 'SELECT * FROM content
                WHERE filename = "countries"
                ORDER BY recnum DESC LIMIT 1';
            break;
        case "languages":
        $out['debug'] .='Case is languages' . "\n";
            $sql = "SELECT * from content
                WHERE country_code = '". $p['country_code'] . "'
                AND filename = 'languages'
                AND folder_name = ''
                ORDER BY recnum DESC LIMIT 1";
            break;
        case "library":
            $out['debug'] .='Case is library' . "\n";
            if (!isset($p['library_code'])){
                $p['library_code'] = 'library';
            }
            $sql = "SELECT * from content
                WHERE country_code = '". $p['country_code'] . "'
                AND language_iso = '" . $p['language_iso'] . "'
                AND folder_name = ''
                AND filename = '" . $p['library_code'] . "'
                ORDER BY recnum DESC LIMIT 1";
            break;
        case "libraryNames":
            $out['debug'] .='Case is libraryNames' . "\n";
            $sql = "SELECT DISTINCT filename FROM content
                WHERE country_code = '". $p['country_code'] . "'
                AND language_iso = '" . $p['language_iso'] . "'
                AND folder_name = ''
                ORDER BY recnum DESC";
            break;
        case "libraryIndex":
            $out['debug'] .='Case is libraryIndex' . "\n";
            $sql = "SELECT * FROM content
                WHERE country_code = '". $p['country_code'] . "'
                AND language_iso = '" . $p['language_iso'] . "'
                AND folder_name = ''
                AND filename = 'index'
                ORDER BY recnum DESC LIMIT 1";
            break;
        case "series":
            $out['debug'] .='Case is series' . "\n";
            $sql = "SELECT * from content
                WHERE country_code = '". $p['country_code'] . "'
                AND language_iso = '" . $p['language_iso'] . "'
                AND folder_name  = '" . $p['folder_name'] . "'
                AND  filename = 'index'
                ORDER BY recnum DESC LIMIT 1";
            break;
        case "page":
            $out['debug'] .='Case is page' . "\n";
            $sql = "SELECT * from content
                WHERE country_code = '". $p['country_code'] . "'
                AND language_iso = '" . $p['language_iso'] . "'
                AND folder_name = '" . $p['folder_name'] . "'
                AND  filename = '" . $p['filename'] . "'
                ORDER BY recnum DESC LIMIT 1";
            break;
        default:
            $sql = null;
            $out['debug'] .= "no match for  ". $p['scope'] . "\n";

    }
    $out['debug'] .= $sql . "\n";
    // execute query
    if ($sql){
        $result = sqlArray($sql);
        if (isset($result['recnum'])){
            $out['debug'] .='Recnum ' . $result['recnum'] ."\n";
            $out['content']= $result;
            $out['content']['text']=moveImagesGenerations($out['content']['text']);
        }
        else{
            if ($p['scope'] == 'library'){
                $out['debug'] .= 'NOTE: USING DEFAULT LIBRARY  FROM LIBRARY.json' ."\n";
                $out['content']['text'] =  myGetPrototypeFile('library.json');
            }
            else{
                $out['debug'] .= 'No default ' ."\n";
                $out['content'] =  null;
            }
        }

    }
    return $out;
}