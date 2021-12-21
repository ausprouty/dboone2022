<?php

myRequireOnce ('publishFiles.php');
myRequireOnce ('sql.php');
myRequireOnce('version2Text.php');
myRequireOnce('writeLog.php');

/* return latest content
*/
function getLatestContent($p){

    $debug ='In getLatestContent Dec 18' . "\n";
    if (!isset($p['scope'])){
        $debug .=  'No scope was set';
        return $out;
    }
    $debug .= $p['scope'] . "\n";

    switch($p['scope']){
        case "countries":
            $debug .='Case is countries' . "\n";
            $sql = 'SELECT * FROM content
                WHERE filename = "countries"
                ORDER BY recnum DESC LIMIT 1';
            break;
        case "languages":
        $debug .='Case is languages' . "\n";
            $sql = "SELECT * from content
                WHERE country_code = '". $p['country_code'] . "'
                AND filename = 'languages'
                AND folder_name = ''
                ORDER BY recnum DESC LIMIT 1";
            break;
        case "library":
            $debug .='Case is Library' . "\n";
            if (!isset($p['library_code'])){
                $p['library_code'] = 'library';
            }
            else{
                if (strpos($p['library_code'], '.html') !== FALSE){
                     $debug .='library code contains .html' . "\n";
                    $p['library_code'] = str_ireplace('.html', '', $p['library_code']);
                }
                else{
                     $debug .=  $p['library_code']  . ' does not contain .html' . "\n";
                }

            }
            $sql = "SELECT * from content
                WHERE country_code = '". $p['country_code'] . "'
                AND language_iso = '" . $p['language_iso'] . "'
                AND folder_name = ''
                AND filename = '" . $p['library_code'] . "'
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
            $text_file = true;
            $sql = "SELECT * FROM content
                WHERE country_code = '". $p['country_code'] . "'
                AND language_iso = '" . $p['language_iso'] . "'
                AND folder_name = ''
                AND filename = 'index'
                ORDER BY recnum DESC LIMIT 1";
            break;
        case "series":
            $debug .='Case is series' . "\n";
            $sql = "SELECT * from content
                WHERE country_code = '". $p['country_code'] . "'
                AND language_iso = '" . $p['language_iso'] . "'
                AND folder_name  = '" . $p['folder_name'] . "'
                AND  filename = 'index'
                ORDER BY recnum DESC LIMIT 1";
            break;
        case "page":
            $debug .='Case is page' . "\n";
            $sql = "SELECT * from content
                WHERE country_code = '". $p['country_code'] . "'
                AND language_iso = '" . $p['language_iso'] . "'
                AND folder_name = '" . $p['folder_name'] . "'
                AND  filename = '" . $p['filename'] . "'
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
            //if ($text_file){
                $result['text'] = version2Text($result['text']);
           // }
            $debug .='Recnum ' . $result['recnum'] ."\n";
            $out = $result;
        }
        else{
            if ($p['scope'] == 'library'){
                $debug .= 'NOTE: USING DEFAULT LIBRARY  FROM LIBRARY.json' ."\n";
                $out['text'] =  myGetPrototypeFile('library.json');
            }
            else{
                $debug .= 'No default ' ."\n";
                $out['text'] =  null;
            }
        }

    }
    writeLog ('getLatestContent-debug', $debug );
    writeLog ('getLatestContent', $out );
    return $out;
}