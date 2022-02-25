<?php
echo 'in Help Insert'. "<br>\n";
require_once ('../.env.api.remote.myfriends.php');

myRequireOnce ('sql.php');
myRequireOnce ('.env.cors.php');
myRequireOnce ('getLatestContent.php');
myRequireOnce ('createHelpContent.php');

// get library

$p = array(
    'scope'=> 'library',
    'country_code' => 'HD',
    'language_iso' => 'eng',
    'library_code' => 'library'
);
$res = getLatestContent($p);
$new = $res['content'];
$new['my_uid'] = 996; // done by computer
createHelpContent($new);

// get index
$p = array(
    'scope'=> 'series',
    'country_code' => 'HD',
    'language_iso' => 'eng',
    'folder_name' => 'help-1',
);
$res = getLatestContent($p);
$new = $res['content'];
$new['my_uid'] = 996; // done by computer
createHelpContent($new);

// get pages

$sql = 'SELECT DISTINCT filename FROM content 
    WHERE language_iso = "eng"
    AND country_code = "HD"
    AND folder_name = "help-1"
    AND filename != "index"
    ORDER BY filename';
$query  = sqlMany($sql);
while($data = $query->fetch_array()){
     echo ( $data['filename'] . "<br>\n");
     $p = array(
         'scope'=> 'page',
         'country_code' => 'HD',
         'language_iso' => 'eng',
         'folder_name' => 'help-1',
         'filename' => $data['filename']
     );
    $res = getLatestContent($p);
    $new = $res['content'];
    $new['my_uid'] = 996; // done by computer
    createHelpContent($new);
}
return $output;