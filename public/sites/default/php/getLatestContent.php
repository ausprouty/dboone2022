<?php

myRequireOnce ('publishFiles.php');
myRequireOnce ('sql.php');
myRequireOnce('version2Text.php');
myRequireOnce('writeLog.php');

/* return latest content
*/
function getLatestContent($p){
    $out = [];
    $out['debug'] ='In getLatestContent Dec 18' . "\n";
    if (!isset($p['scope'])){
        $out['debug'] .=  'No scope was set';
        return $out;
    }
    $out['debug'] .= $p['scope'] . "\n";

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
            $out['debug'] .='Case is Library' . "\n";
            if (!isset($p['library_code'])){
                $p['library_code'] = 'library';
            }
            else{
                if (strpos($p['library_code'], '.html') !== FALSE){
                     $out['debug'] .='library code contains .html' . "\n";
                    $p['library_code'] = str_ireplace('.html', '', $p['library_code']);
                }
                else{
                     $out['debug'] .=  $p['library_code']  . ' does not contain .html' . "\n";
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
            $out['debug'] .='Case is libraryNames' . "\n";
            $sql = "SELECT DISTINCT filename FROM content
                WHERE country_code = '". $p['country_code'] . "'
                AND language_iso = '" . $p['language_iso'] . "'
                AND folder_name = ''
                ORDER BY recnum DESC";
            break;
        case "libraryIndex":
            $out['debug'] .='Case is libraryIndex' . "\n";
            $text_file = true;
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
            //if ($text_file){
                $result['text'] = version2Text($result['text']);
           // }
            $out['debug'] .='Recnum ' . $result['recnum'] ."\n";
            $out['content']= $result;
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
    writeLog ('getLatestContent-debug', $out['debug'] );
    writeLog ('getLatestContent', $out['content'] );
    return $out;
}