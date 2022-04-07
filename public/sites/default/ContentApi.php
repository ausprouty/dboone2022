<?php
$debug = 'Content API Post' . "\n";
$backend = '../'. $_GET['site'] . '/.env.api.'.  $_GET['location'] . '.php';

if (file_exists($backend)){
	require_once ($backend);
}
else{
	trigger_error("No backend for  Content Api: $backend", E_USER_ERROR);
}
require_once('php/myRequireOnce.php');
myRequireOnce ('sql.php');
myRequireOnce ('writeLog.php');
$p = array();
$out = null;
$p = getParameters();
if (isset($p['my_uid'])){
    myRequireOnceSetup($p['my_uid']);
}
myHeaders(); // send cors headers
myRequireOnce ('getLatestContent.php');
myRequireOnce ('getContentByRecnum.php');
myRequireOnce ('version2Text.php');
$debug .= 'past Parameters' . "\n";

if (isset($p['recnum'])){
    $out = getContentbyRecnum($p);
}
else{
    if (isset($p['scope'])){
        $out = getLatestContent($p);

        $debug .= 'I am going to getLatestContent'. "\n";
    }
    else{
        $message = "in ContentApi Scope was not set.  Sorry";
        trigger_error( $message, E_USER_ERROR);
    }
}
// many times $out['text'] was created by json_encode.
// decode here so we can properly send it back in good form.
//$debug .= $out['text'];
if (isset($out)){
    $out['text'] = version2Text($out['text']);
    $ok =  json_decode($out['text']);
    if ($ok){
        $out['text'] = $ok;
    }
}
// create log file

$debug .= "\n\n\n";
$debug .= strlen(json_encode($out, JSON_UNESCAPED_UNICODE));
$debug .= "\n\nHERE IS JSON_ENCODE OF DATA\n";
$debug .= json_encode($out, JSON_UNESCAPED_UNICODE) . "\n";
$fn = "ContentApi-" . $p['scope'] ;
writeLog($fn, $debug);


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
    if (isset($p['sdcard'])){
		$p['sdcard_settings'] = json_decode($p['sdcard']);
        $bad =['/','..'];
        if (isset($p['sdcard_settings']->subDirectory)){
            $clean = str_replace($bad, '', $p['sdcard_settings']->subDirectory);
            $p['dir_sdcard'] = ROOT_SDCARD . $clean . '/';
        }
	}
    if (isset($p['apk_settings'])){
		$p['apk_settings'] = json_decode($p['apk_settings']);
	}
    $p['site'] =  $_GET['site'];
    foreach ($p as $key=>$value){
        $debug .= "p['". $key . "'] = " . $value. "\n";
    }
    $p['debug'] = $debug;
    writeLogDebug('ContentSetParameters-p', $p);
    return $p;
}