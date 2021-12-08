<?php
$debug = 'Content API Post' . "\n";

$backend = '../'. $_GET['site'] . '/.env.api.'.  $_GET['location'] . '.php';

$text = $backend;
if (file_exists($backend)){
	require_once ($backend);
}
else{
	trigger_error("No backend for  Content Api: $backend", E_USER_ERROR);
}
$p = array();
$out = [];
$p = getParameters();
if (isset($p['my_uid'])){
    myRequireOnceSetup($p['my_uid']);
}

myHeaders(); // send cors headers

myRequireOnce('writeLog.php');
//myRequireOnce ('sql.php');

myRequireOnce ('getLatestContent.php');

myRequireOnce ('getContentByRecnum.php');

$debug .= 'past Parameters';
if (isset($p['debug'])){
    $debug .= $p['debug'];
    $p['debug'] = null;
}
if (isset($p['recnum'])){
    $out = getContentbyRecnum($p);
}
else{
    if (isset($p['scope'])){
        $out = getLatestContent($p);
        if (isset($out['debug'])){
            $debug .= $out['debug'];
        }
    }
    else{
        $debug .= 'Scope was not set.  Sorry'. "\n";
    }
}

// many times $out['text'] was created by json_encode.
// decode here so we can properly send it back in good form.
//$debug .= $out['content']['text'];
if (isset($out['content']['text'])){
    $ok =  json_decode($out['content']['text']);
    if ($ok){
        $out['content']['text'] = $ok;
    }
}
// create log file
if ("LOG_MODE"== 'write_log'){
    $debug .= "\n\n\n";
    $debug .= strlen(json_encode($out, JSON_UNESCAPED_UNICODE));
    $debug .= "\n\nHERE IS JSON_ENCODE OF DATA\n";
    $debug .= json_encode($out, JSON_UNESCAPED_UNICODE) . "\n";
    $fn = "ContentApi-" . $p['scope'] ;
    writeContentLog($fn, $debug);
}

header("Content-type: application/json");
echo json_encode($out, JSON_UNESCAPED_UNICODE);
die();
//
//   FUNCTIONS
//
// we clean parameters because people may be adding crummy stuff
function getParameters(){
    $out = array();
    $p['country_code'] = NULL;
    $p['language_iso'] = NULL;
    $p['library_code'] = NULL;
    $p['folder_name'] = NULL;
    $p['filetype'] = NULL;
    $p['recnum'] = NULL;
    $p['title'] = NULL;
    $p['filename'] = NULL;
    $p['text'] = NULL;
    $debug = 'parameters:' . "\n";
    $conn = new mysqli(HOST, USER, PASS, DATABASE_CONTENT, DATABASE_PORT);
    foreach ($_POST as $param_name => $param_value) {
        //$p[$param_name] = $conn->real_escape_string($param_value);
        $p[$param_name] = $param_value;
        if ($p[$param_name] == 'null'){
            $p[$param_name] = NULL;
        }

    }
    // route overrides any other parameters
    if (isset($route)){
        $debug .= '$route is set'.  "\n";
        $debug .= $route.  "\n";
        $r = json_decode($route);
        $p['country_code'] = isset($r['country_code']) ? $r['country_code'] : $p['country_code'];
        $p['language_iso'] = isset($r['language_iso'] )? $r['language_iso'] : $p['language_iso'];
        $p['library_code'] = isset($r['library_code']) ? $r['library_code'] : $p['library_code'];
        $p['folder_name'] = isset($r['folder_name']) ? $r['folder_name'] : $p['folder_name'];
        $p['filename'] = isset($r['filename']) ? $r['filename'] : $p['filename'];
        $p['recnum'] = isset($r['recnum']) ? $r['recnum'] : $p['recnum'];
    }
    $p['site'] =  $_GET['site'];
    foreach ($p as $key=>$value){
        $debug .= "p['". $key . "'] = " . $value. "\n";
    }
    $p['debug'] = $debug;
    return $p;
}



function writeContentLog($filename, $content){
	if (!is_array($content)){
		$text = $content;
	}
	else{
		$text = '';
		foreach ($content as $key=> $value){
			$text .= $key . ' => '. $value . "\n";
		}
	}
	if (!file_exists(ROOT_LOG)){
		mkdir(ROOT_LOG);
	}
	$fh = fopen(ROOT_LOG . $filename . '.txt', 'w');
	fwrite($fh, $text);
    fclose($fh);
}