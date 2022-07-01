<?php
require_once ('../../.env.api.remote.php');
require_once('../../../default/php/myRequireOnce.php');
myRequireOnce ('sql.php');
myRequireOnce ('getLatestContent.php');
myRequireOnce ('create.php');
$fixing = 'multiply1';
$p = array(
    'scope'=> 'series',
    'country_code' => 'M2',
    'language_iso' => 'eng',
    'folder_name' => 'multiply1',
);
$new = getLatestContent($p);
$new['language_iso'] = 'zzz';
$new['my_uid'] = 996; // done by computer
createContent($new);
echo ('check database');
 return;
