<?php

require_once ('../../.env.api.remote.php');
myRequireOnce ('sql.php');
myRequireOnce ('.env.cors.php');
myRequireOnce ('getLatestContent.php');
myRequireOnce ('create.php');
myRequireOnce ('writeLog.php');
require_once  '../../vendor/autoload.php';
use Rny\ZhConverter\ZhConverter;
echo 'after use';

$fixing = 'tc';

$p = array(
    'scope'=> 'series',
    'country_code' => 'M2',
    'language_iso' => 'cmn',
    'folder_name' => 'tc',
);
writeLogError('SimptoTradTCIndex-21-'. time() , $p);
$new = getLatestContent($p);
writeLogError('SimptoTradTCIndex-23-'. time() , $new);
$text =  $new['text'];
$traditional = _fix($text);
$new['text'] = $traditional;
$new['language_iso'] = 'cmt';
$new['my_uid'] = 996; // done by computer
createContent($new);

echo 'check log';
writeLogError('SimptoTradTCIndex'. time() , $new);
return;


 function  _fix($text){
   // see https://github.com/peterolson/hanzi-tools (for javascript)
   // see https://github.com/rny/ZhConverter (for php)
    $text = ZhConverter::zh2hant($text);
    return $text;
 }
