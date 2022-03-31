<?php

myRequireOnce ('prototypeORpublish.php');
myRequireOnce ('sql.php');
myRequireOnce ('myGetPrototypeFile.php');
myRequireOnce ('writeLog.php');

/* return latest mc2_content
*/
function getLatestMc2content($p){
    $out = [];
    if (!isset($p['scope'])){
        writeLogError('getLatestMc2content-13', "No scope was set");
        return null;
    }

    switch($p['scope']){
        case "countries":
            $sql = 'SELECT * FROM mc2_content
                WHERE filename = "countries"
                ORDER BY recnum DESC LIMIT 1';
            break;
        case "languages":
            $sql = "SELECT * from mc2_content
                WHERE country_code = '". $p['country_code'] . "'
                AND filename = 'languages'
                AND folder_name = ''
                ORDER BY recnum DESC LIMIT 1";
            break;
        case "library":
            if (!isset($p['library_code'])){
                $p['library_code'] = 'library';
            }
            $sql = "SELECT * from mc2_content
                WHERE country_code = '". $p['country_code'] . "'
                AND language_iso = '" . $p['language_iso'] . "'
                AND folder_name = ''
                AND filename = '" . $p['library_code'] . "'
                ORDER BY recnum DESC LIMIT 1";
            break;
        case "libraryNames":
            $sql = "SELECT DISTINCT filename FROM mc2_content
                WHERE country_code = '". $p['country_code'] . "'
                AND language_iso = '" . $p['language_iso'] . "'
                AND folder_name = ''
                ORDER BY recnum DESC";
            break;
        case "libraryIndex":
            $sql = "SELECT * FROM mc2_content
                WHERE country_code = '". $p['country_code'] . "'
                AND language_iso = '" . $p['language_iso'] . "'
                AND folder_name = ''
                AND filename = 'index'
                ORDER BY recnum DESC LIMIT 1";
            break;
        case "series":
            $sql = "SELECT * from mc2_content
                WHERE country_code = '". $p['country_code'] . "'
                AND language_iso = '" . $p['language_iso'] . "'
                AND folder_name  = '" . $p['folder_name'] . "'
                AND  filename = 'index'
                ORDER BY recnum DESC LIMIT 1";
            break;
        case "page":
            $sql = "SELECT * from mc2_content
                WHERE country_code = '". $p['country_code'] . "'
                AND language_iso = '" . $p['language_iso'] . "'
                AND folder_name = '" . $p['folder_name'] . "'
                AND  filename = '" . $p['filename'] . "'
                ORDER BY recnum DESC LIMIT 1";
            break;
        default:
            $sql = null;
            $message =  "no match for  ". $p['scope'] . "\n";
            writeLogError('getLatestMc2content-76', $message);

    }
    // execute query
    if ($sql){
        $result = sqlArray($sql);
        if (isset($result['recnum'])){
            $out= $result;
        }
        else{
            if ($p['scope'] == 'library'){
                $out['debug'] .= 'NOTE: USING DEFAULT LIBRARY  FROM LIBRARY.json' ."\n";
                $out['text'] =  myGetPrototypeFile('library.json');
            }
            else{
                $out['debug'] .= 'No default ' ."\n";
                $out =  null;
            }
        }

    }
    return $out;
}