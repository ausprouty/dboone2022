<?php
echo 'HelpInsertM2';
require_once ('../.env.api.remote.mc2.php');
echo ROOT_LOG;
myRequireOnce ('sql.php');
myRequireOnce ('.env.cors.php');
myRequireOnce ('create.php');

$sql = 'SELECT * from help_content';
$query  = sqlMany($sql);
while($data = $query->fetch_array()){
	$new = $data;
	echo ( $data['filename'] . "<br>\n");
	$new['my_uid'] = 990; // done by computer
	createContent($new);
}
echo (' finished');
return;
 
