<?php

// this routine trims the Content Datafile to the last 5 entries
// best to tell everyone to stay off while you clean
if(!file_exists('../.env.api.remote.mc2.php')){
 echo "env not found";
}

require_once ('../.env.api.remote.mc2.php');
/*$site = 'mc2';
$location = 'local';
$env = '../../'. $site .'/env.api.'. $location . '.php';
if (!file_exists($env)){
    echo 'No environmental file';
    return;
}
require_once ($env);
*/
myRequireOnceSetup (11);
myRequireOnce ('sql.php');
myRequireOnce ('.env.cors.php');
myRequireOnce ('getLatestContent.php');
myRequireOnce ('create.php');
$count = 0;
$output = '';
$sql = 'SELECT * FROM members ORDER BY uid';
$query  = sqlMany($sql);
while($data = $query->fetch_array()){
    $output .=$data['uid'] . ':  '. $data['first_name'] . ' '. $data['lastname'] . "\n";

}

echo $output;
