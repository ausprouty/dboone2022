<?php

 function sqlBibleArray($sql){
    $conn = new mysqli(HOST, USER, PASS, DATABASE_BIBLE, DATABASE_PORT);
    if ($conn->connect_error) {
        die("Connection has failed: " . $conn->connect_error);
    }
    $query = $conn->query($sql);
    if ($query){
        $output =  $query->fetch_array();
         $conn->close();
         return $output;
    }
 }
 function sqlBibleInsert($sql){
    $conn = new mysqli(HOST, USER, PASS, DATABASE_BIBLE, DATABASE_PORT);
    if ($conn->connect_error) {
        die("Connection has failed: " . $conn->connect_error);
    }
    $query = $conn->query($sql);
    return $query;
 }
 function sqlBibleMany($sql){
    $conn = new mysqli(HOST, USER, PASS, DATABASE_BIBLE, DATABASE_PORT);
    if ($conn->connect_error) {
        die("Connection has failed: " . $conn->connect_error);
    }
    $query = $conn->query($sql);
    $output =  $query;
    $conn->close();
    return $output;
 }


function sqlArray($sql, $update = NULL){
    $conn = new mysqli(HOST, USER, PASS, DATABASE_CONTENT, DATABASE_PORT);
    if ($conn->connect_error) {
        die("Connection has failed: " . $conn->connect_error);
    }
    $query = $conn->query($sql);

    if (!$update){
        if ($query){
           $output =  $query->fetch_array();
            $conn->close();
            return $output;
        }
        else{
            $conn->close();
            return null;
        }
    }
    else{
       $output =  $query;
       $conn->close();
       return $output;
    }
}
function sqlDelete($sql){
    $conn = new mysqli(HOST, USER, PASS, DATABASE_CONTENT, DATABASE_PORT);
    if ($conn->connect_error) {
        die("Connection has failed: " . $conn->connect_error);
    }
    $query = $conn->query($sql);
    $conn->close();
    return;
}
function sqlInsert($sql){
    $conn = new mysqli(HOST, USER, PASS, DATABASE_CONTENT, DATABASE_PORT);
    if ($conn->connect_error) {
        die("Connection has failed: " . $conn->connect_error);
    }
    $query = $conn->query($sql);
    $conn->close();
    return 'done';
}
function sqlMany($sql){
    $conn = new mysqli(HOST, USER, PASS, DATABASE_CONTENT, DATABASE_PORT);
    if ($conn->connect_error) {
        die("Connection has failed: " . $conn->connect_error);
    }
    $query = $conn->query($sql);
    $output =  $query;
    $conn->close();
    return $output;
}
function sqlText($sql, $update = NULL){
    $conn = new mysqli(HOST, USER, PASS, DATABASE_CONTENT, DATABASE_PORT);
    if ($conn->connect_error) {
        die("Connection has failed: " . $conn->connect_error);
    }
    $query = $conn->query($sql);
    $conn->close();
    if (!$update){
        if ($query){
            return  $query->fetch_array();
        }
        else{
            return null;
        }
    }
    else{
       return $query;
    }
}
function jsonDecodedFromContentTextByRecnum($recnum){
    $sql = "SELECT * FROM content
        WHERE  recnum = '$recnum'  LIMIT 1";
    $data = sqlArray($sql);
    $text = json_decode($data['text']);
    return $text;
}
function contentArrayFromRecnum($recnum){
    $output = null;
    if ($recnum){
        $sql = "SELECT * FROM content
            WHERE  recnum = $recnum  LIMIT 1";
        $conn = new mysqli(HOST, USER, PASS, DATABASE_CONTENT, DATABASE_PORT);
        if ($conn->connect_error) {
            die("Connection has failed: " . $conn->connect_error);
        }
        $query = $conn->query($sql);
        if ($query){
            $output =  $query->fetch_array();
        }
        else{
            $output = null;
        }
        $conn->close();
    }
    return $output;
}
function contentObjectFromRecnum($recnum){
    $output = null;
    if ($recnum){
        $sql = "SELECT * FROM content
            WHERE  recnum = $recnum  LIMIT 1";
        $conn = new mysqli(HOST, USER, PASS, DATABASE_CONTENT, DATABASE_PORT);
        if ($conn->connect_error) {
            die("Connection has failed: " . $conn->connect_error);
        }
        $query = $conn->query($sql);
        if ($query){
            $output =  $query->fetch_object();
        }
        else{
            $output = null;
        }
        $conn->close();
    }
    return $output;
}

function copyBookX($p){
    $conn = new mysqli(HOST, USER, PASS, DATABASE_CONTENT, DATABASE_PORT);
    if ($conn->connect_error) {
        die("Connection has failed: " . $conn->connect_error);
    }

    $debug= '';
    if (!$p['source'] || !$p['destination']){
        return;
    }
    $edit_date = time();
    $edit_uid = $p['my_uid'];
    // this is not going to work because this variable is used where to publish
    $destination = explode('/', $p['destination']);
    $destination_country_code = $destination[0];
    $destination_language_iso = $destination[1];
    $source = explode('/',$p['source']);
    $country_code = $source[0];
    $language_iso = $source[1];
    $folder_name = $source[2];
    $sql = "SELECT DISTINCT  filename
        FROM  content
        WHERE country_code = '$country_code'
        AND language_iso = '$language_iso'
        AND folder_name = '$folder_name'";
    $query = $conn->query($sql);
    while($data = $query->fetch_array()){
        $filename = $data['filename'];
        $debug .= 'filename is ' . $filename ."\n";
        $sql2 = "SELECT *
            FROM  content
            WHERE country_code = '$country_code'
            AND language_iso = '$language_iso'
            AND folder_name = '$folder_name'
            AND filename = '$filename'
            ORDER BY recnum DESC
            LIMIT 1";
        $query2 = $conn->query($sql2);
        $d =  $query2->fetch_array();
        $filetype = $d['filetype'];
        $debug .=  $d['filename']  . '-- '. $d['recnum'] ."\n";
        $title = $d['title'];
        $text = $d['text'];
        $sql3 = "INSERT INTO content (version,edit_date, edit_uid, language_iso, country_code, folder_name, filetype, title, filename, text) VALUES
          (1, '$edit_date', '$edit_uid', '$destination_language_iso', '$destination_country_code', '$folder_name', '$filetype', '$title', '$filename', '$text')";
        $done = $conn->query($sql3);
        $debug .= '   Inserted ' . $filename ."\n";
    }
    return $out;
}