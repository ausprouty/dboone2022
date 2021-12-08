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
$sql = 'SELECT DISTINCT language_iso, country_code, folder_name, filename FROM content';
$query  = sqlMany($sql);
while($data = $query->fetch_array()){
    $latest = 'SELECT * FROM content 
        WHERE language_iso ="' . $data['language_iso'] . '"'.
        ' AND country_code = "' . $data['country_code']. '"'.
        ' AND folder_name ="'  . $data['folder_name'] . '"'.
        ' AND filename ="' . $data['filename'] . '"'.
        '  ORDER BY recnum DESC LIMIT 3';
       
    $latest_query  = sqlMany($latest);
    while($p = $latest_query->fetch_array()){
        $version = isset($p['version']) ? $p['version'] : VERSION;
		$edit_date = isset($p['edit_date']) ? $p['edit_date'] : time();
		$edit_uid = isset($p['edit_uid']) ? $p['edit_uid'] : NULL;
        $prototype_uid = isset($p['prototype_uid']) ? $p['prototype_uid'] : NULL;
        $prototype_date = isset($p['prototype_date']) ? $p['prototype_date'] : NULL;
        $publish_uid = isset($p['publish__uid']) ? $p['publish__uid'] : NULL;
        $publish__date = isset($p['publish__date']) ? $p['publish__date'] : NULL;
		$language_iso = isset($p['language_iso']) ? $p['language_iso'] : NULL;
		$country_code = isset($p['country_code']) ? $p['country_code'] : NULL;
		$folder_name = isset($p['folder_name']) ? $p['folder_name'] :'';
        $filename = isset($p['filename']) ? $p['filename'] :'';
		$filetype = isset($p['filetype']) ? $p['filetype'] : NULL;
        $page = isset($p['page']) ? $p['page'] : NULL;
		$title = isset($p['title']) ? $p['title'] : NULL;
		$text = isset($p['text']) ? $p['text'] : NULL;
		$conn = new mysqli(HOST, USER, PASS, DATABASE_CONTENT);
		$text = $conn->real_escape_string($text);
		$insert_sql = "INSERT into fresh_content (version,edit_date,edit_uid,
            prototype_uid, prototype_date, publish_uid, publish_date, 
            language_iso, country_code,folder_name,filetype,title,filename, page, text) values 
			('$version','$edit_date','$edit_uid',
            '$prototype_uid', '$prototype_date' , '$publish_uid' , '$publish_date', 
            '$language_iso','$country_code','$folder_name','$filetype','$title','$filename','$page','$text')";
        $insert_query = $conn->query($insert_sql);
		if(!$insert_query){
            trigger_error("Failed to create record in trimContent", E_USER_ERROR);
		}
        else{
            $count++;
        }
       
	}
	
}

echo $count;
