<?php
echo 'in Import M2';
require_once ('../.env.api.remote.php');
echo ROOT_LOG;
myRequireOnce ('sql.php');
myRequireOnce ('.env.cors.php');
myRequireOnce ('getLatestMc2Content.php');
myRequireOnce ('create.php');
myRequireOnce ('writeLog.php');


$fixing = 'multiply2';

$p = array(
    'scope'=> 'series',
    'country_code' => 'M2',
    'language_iso' => 'eng',
    'folder_name' => 'multiply2',
);
$new = getLatestMc2Content($p);
$new['country_code'] = 'AU';
$new['my_uid'] = 996; // done by computer
createContent($new);


 echo ($debug);
 writeLogDebug('ImportM2'. time() , $debug);
 return;

