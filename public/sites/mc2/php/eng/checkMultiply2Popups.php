<?php
echo nl2br('in Import MC2' . "\n");
require_once ('../../.env.api.remote.php');
require_once('../../../default/php/myRequireOnce.php');
echo nl2br(ROOT_LOG . "\n");
myRequireOnce ('sql.php');
myRequireOnce ('.env.cors.php');
myRequireOnce ('writeLog.php');
myRequireOnce ('getLatestContent.php');
myRequireOnce ('create.php');

$debug = "Fixing Mc2 Multiply2<br>\n";
$sql = 'SELECT DISTINCT filename FROM content
    WHERE language_iso = "eng"
    AND country_code = "M2"
    AND folder_name = "multiply2"
    AND filename != "index"
    ORDER BY filename';
 $query  = sqlMany($sql);
 while($data = $query->fetch_array()){

     $p = array(
         'scope'=> 'page',
         'country_code' => 'M2',
         'language_iso' => 'eng',
         'folder_name' => 'multiply2',
         'filename' => $data['filename']
     );
    $new = getLatestContent($p);
    $text = $new['text'];

    for ($i = 0; $i <50; $i++){
       $find = 'id="pop' . $i;
       $count = substr_count($text, $find);
       $message =$count .' -- ' . $find;
       if ($count > 1){
           echo nl2br( "\n\n");
           echo nl2br($new['filename'] . "\n");
           echo nl2br($message . "\n");
       }

    }
 }

die;
