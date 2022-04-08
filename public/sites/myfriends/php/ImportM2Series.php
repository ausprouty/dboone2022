<?php
echo nl2br('in Import MC2' . "\n");
require_once ('../.env.api.remote.php');
require_once('../../default/php/myRequireOnce.php');
echo nl2br(ROOT_LOG . "\n");
myRequireOnce ('sql.php');
myRequireOnce ('.env.cors.php');
myRequireOnce ('getLatestMc2Content.php');
myRequireOnce ('create.php');



$fixing = 'multiply2';

$debug = "Import Mc2 Multiply2<br>\n";
$sql = 'SELECT DISTINCT filename FROM mc2_content
    WHERE language_iso = "eng"
    AND country_code = "M2"
    AND folder_name = "multiply2"
    AND filename != "index"
    ORDER BY filename';
 $query  = sqlMany($sql);
 while($data = $query->fetch_array()){
    echo nl2br($data['filename'] . "\n");
     $debug .= $data['filename'] . "<br>\n";
     $p = array(
         'scope'=> 'page',
         'country_code' => 'M2',
         'language_iso' => 'eng',
         'folder_name' => 'multiply2',
         'filename' => $data['filename']
     );
    $new = getLatestMc2Content($p);
    echo ($new['language_iso']);
    $new['text'] =_transform($new['text']);
    $new['country_code'] = 'AU';
    $new['my_uid'] = 996; // done by computer
    createContent($new);

 }
 echo ($debug);
 writeLogDebug('ImportM2'. time() , $debug);
 return;

 function _transform($text){
   $bad = array(
       'Period',
       'Periods',
       'multiply2/Period',
       'content/M2/eng',
       'Our vision is: <em> &ldquo;A church for every village and community, and the gospel for every person.&rdquo;</em>'
   );
   $good = array(
       'Phase',
       'Phases',
       'multiply2/Phase',
       'content/AU/eng',
       'Our vision is: <em> &ldquo;A Jesus-centered community within reach of every person, everywhere!&rdquo;</em>'
   );
   $text = str_replace($bad, $good, $text);
   return $text;
 };