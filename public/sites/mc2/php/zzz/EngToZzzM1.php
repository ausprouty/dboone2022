<?php
require_once ('../../.env.api.remote.php');
require_once('../../../default/php/myRequireOnce.php');
myRequireOnce ('sql.php');
myRequireOnce ('getLatestContent.php');
myRequireOnce ('create.php');
$fixing = 'multiply1';
$sql = 'SELECT DISTINCT filename FROM content
    WHERE language_iso = "eng"
    AND country_code = "M2"
    AND folder_name = "multiply1"
    AND filename != "index"
    ORDER BY filename';
 $query  = sqlMany($sql);
 while($data = $query->fetch_array()){
     $debug .= $data['filename'] . "<br>\n";
     $p = array(
         'scope'=> 'page',
         'country_code' => 'M2',
         'language_iso' => 'eng',
         'folder_name' => 'multiply1',
         'filename' => $data['filename']
     );
    $new = getLatestContent($p);
    $new['language_iso'] = 'zzz';
    $new['my_uid'] = 996; // done by computer
    createContent($new);

 }
 return;
