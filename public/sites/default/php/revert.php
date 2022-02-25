<?php

myRequireOnce ('publishFiles.php');
myRequireOnce ('sql.php');
myRequireOnce ('myGetPrototypeFile.php');

/* return latest content
*/
function revert($p){

    $debug ='In Revert' . "\n";
    //writeLog('revert12-'. time(), $debug);
    if (!isset($p['scope'])){
        $debug .=  'No scope was set';
        return $out;
    }
    if (!isset($p['recnum'])){
        $debug .=  'No recnum set';
        return $out;
    }

    switch($p['scope']){
        case "countries":
            $debug .='Case is countries' . "\n";
            $sql = 'SELECT * FROM content
                WHERE filename = "countries"
                AND recnum < '. $p['recnum'] .'
                ORDER BY recnum DESC LIMIT 1';
            break;
        case "languages":
        $debug .='Case is languages' . "\n";
            $sql = "SELECT * from content
                WHERE country_code = '". $p['country_code'] . "'
                AND filename = 'languages'
                AND folder_name = ''
                AND recnum < ". $p['recnum'] ."
                ORDER BY recnum DESC LIMIT 1";
            break;
        case "library":
            $debug .='Case is library' . "\n";
            if (!isset($p['library_code'])){
                $p['library_code'] = 'library';
            }
            $sql = "SELECT * from content
                WHERE country_code = '". $p['country_code'] . "'
                AND language_iso = '" . $p['language_iso'] . "'
                AND folder_name = ''
                AND filename = '" . $p['library_code'] . "'
                AND recnum < ". $p['recnum'] ."
                ORDER BY recnum DESC LIMIT 1";
            break;
        case "libraryNames":
            $debug .='Case is libraryNames' . "\n";
            $sql = "SELECT DISTINCT filename FROM content
                WHERE country_code = '". $p['country_code'] . "'
                AND language_iso = '" . $p['language_iso'] . "'
                AND folder_name = ''
                ORDER BY recnum DESC";
            break;
        case "libraryIndex":
            $debug .='Case is libraryIndex' . "\n";
            $sql = "SELECT * FROM content
                WHERE country_code = '". $p['country_code'] . "'
                AND language_iso = '" . $p['language_iso'] . "'
                AND folder_name = ''
                AND filename = 'index'
                AND recnum < ". $p['recnum'] ."
                ORDER BY recnum DESC LIMIT 1";
            break;
        case "series":
            $debug .='Case is series' . "\n";
            $sql = "SELECT * from content
                WHERE country_code = '". $p['country_code'] . "'
                AND language_iso = '" . $p['language_iso'] . "'
                AND folder_name  = '" . $p['folder_name'] . "'
                AND  filename = 'index'
                AND recnum < ". $p['recnum'] ."
                ORDER BY recnum DESC LIMIT 1";
            break;
        case "page":
            $debug .='Case is page' . "\n";
            $sql = "SELECT * from content
                WHERE country_code = '". $p['country_code'] . "'
                AND language_iso = '" . $p['language_iso'] . "'
                AND folder_name = '" . $p['folder_name'] . "'
                AND  filename = '" . $p['filename'] . "'
                AND recnum < ". $p['recnum'] ."
                ORDER BY recnum DESC LIMIT 1";
            break;
        default:
            $sql = null;
            $debug .= "no match for  ". $p['scope'] . "\n";

    }
    $debug .= $sql . "\n";
    // execute query
    if ($sql){
        $result = sqlArray($sql);
        if (isset($result['recnum'])){
            $debug .='Recnum ' . $result['recnum'] ."\n";
            $out= $result;
        }
        else{
            if ($p['scope'] == 'library'){
                $debug .= 'NOTE: USING DEFAULT LIBRARY  FROM LIBRARY.json' ."\n";
                $out['text'] =  myGetPrototypeFile('library.json', $p['destination']);
            }
            else{
                $debug .= 'No default ' ."\n";
                $out =  null;
            }
        }

    }
    return $out;
}