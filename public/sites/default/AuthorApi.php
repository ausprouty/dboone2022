<?php

/*
  Returns Json encoded array
	$out = successful content
	$out['login']  is set if login fails (login only)
	$out['token']  for authorization (login only)
	$out['debug']  for some debugging purposes

  Writes $debug to file if LOG_MODE == 'write_log'
*/
$debug= '';
$backend = '../'. $_GET['site'] . '/.env.api.'.  $_GET['location'] . '.php';
if (file_exists($backend)){
	require_once ($backend);
}
else{
	trigger_error("No backend for AuthorApi. Looking for $backend", E_USER_ERROR);
}
myRequireOnce ('sql.php');
myRequireOnce('writeLog.php');
myHeaders(); // send cors headers
// assign variables
$out = array();
$p = setParameters($_POST);
if (isset($p['my_uid'])){
	myRequireOnceSetup($p['my_uid']);
}
// get is used in prototype and publish and overrides $p
if (isset($_GET['page'])){
	$p['page'] = $_GET['page'];
}
if (isset($_GET['action'])){
	$p['action'] = $_GET['action'];
}

if (isset($p['page'])){
	$p['page'] = _clean($p['page']);
}
if (isset($p['action'])){
	// login routine
	if ($p['action'] == 'login'){
		$out = myApiLogin($p);
	}
	else{
		// take action if authorized user
		if (!isset($p['token'])){
			$message = "Token is not set";
			$debug .= $message;
            trigger_error( $message, E_USER_ERROR);
			die;
		}
		$ok = myApiAuthorize($p['token']);
		unset($p['token']);  // so it will not be sent back
		if($ok){
			myRequireOnce ('dirMake.php');
			if (isset($p['page'])){
				$subdirectory = null;
				if (isset($p['subdirectory'])){
      			 	$subdirectory  = $p['subdirectory'];
				}
				writeLogError('AuthorApi-62-p', $p);
				myRequireOnce($p['page'] , $subdirectory);
				$action = $p['action'];
				$out = $action ($p);
			}
			else{
				$message = $p['page']  . "is not set";
				$debug .= $message;
                trigger_error( $message, E_USER_ERROR);
			}
		}
		else{
			$message = "Not Authorized";
			$debug .= $message;
            trigger_error( $message, E_USER_ERROR);
		}
	}
}
else{
	$message = "No Action";
	$debug .= $message;
    trigger_error( $message, E_USER_ERROR);
}


$debug .= "\n\nHERE IS JSON_ENCODE OF DATA THAT IS NOT ESCAPED\n";
$debug .= json_encode($out) . "\n";
writeLog($p['action'] ,   $debug);

// return response
header("Content-type: application/json");
echo json_encode($out, JSON_UNESCAPED_UNICODE);
die();
//}


/*
//            FUNCTIONS
*/
function setParameters($post){
	$debug = 'Using set Parameters'. "\n";
	$debug .= '$p[] = ' . "\n";
	$debug .= 'parameters:' . "\n";
	foreach ($post as $param_name => $param_value) {
		$$param_name = $param_value;
		$p[$param_name] =  $param_value;
		$debug .= $param_name . ' = ' . $param_value. "\n";
	}
	$debug .= 'end of parameters' . "\n";
	$debug .= 'finished post loop' . "\n";


	if (isset($p['route'])){
		$debug .= "\n\n\n\n" .'set by route' . "\n";
		$route = json_decode($p['route']);
		$p['country_code'] = isset($route->country_code) ? $route->country_code : NULL;
		$debug .= 'country_code:' . $p['country_code'] . "\n";
		$p['language_iso'] = isset($route->language_iso )? $route->language_iso : NULL;
		$debug .= 'language_iso:' . $p['language_iso'] . "\n";
		$p['library_code'] = isset($route->library_code) ? $route->library_code : NULL;
		$debug .= 'library_code:' . $p['library_code'] .  "\n";
		$p['folder_name'] = isset($route->folder_name) ? $route->folder_name : NULL;
		$debug .= 'folder_name:' . $p['folder_name'].  "\n";
		$p['filename'] = isset($route->filename) ? $route->filename : NULL;
		$debug .= 'filename:' . $p['filename']. "\n\n\n";
	}
	if (!isset($p['version'])){
		$p['version'] = VERSION;
	}
	$p['site'] =  $_GET['site'];
	$p['debug'] = $debug;
	return $p;

}
function _clean($page){
	$bad = array('.', '$', '/');
	$page = str_replace($bad, '', $page);
	$page .= '.php';
	return $page;
}
