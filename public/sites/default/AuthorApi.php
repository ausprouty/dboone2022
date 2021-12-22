<?php

/*
  Returns Json encoded array
	$out = successful content
	$out['login']  is set if login fails (login only)
	$out['token']  for authorization (login only)
	$out['debug']  for some debugging purposes

  Writes $debug to file if LOG_MODE == 'write_log'
*/

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
$debug = "in AuthorApi.php\n";
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
if (isset($p['debug'])){
	$debug .= $p['debug'];
	unset($p['debug']);
}
if (isset($p['page'])){
	$p['page'] = _clean($p['page']);
	$debug .=  'Page: ' . $p['page']. "\n";
	$debug .= 'MyPage: ' . myFile($p['page'] . '.php') . "\n";
}
$debug .= $p['action'] .  " is action\n";
writelog ($p['action'] . '-parameters', $p);
if (isset($p['action'])){
	// login routine
	if ($p['action'] == 'login'){
		$out = myApiLogin($p);
	}
	else{
		$debug .=  $p['token'] . " is token\n";
		// take action if authorized user
		if (!isset($p['token'])){
			$message = "Token is not set";
			$debug .= $message;
			// TODO: remove this
            //trigger_error( $message, E_USER_ERROR);
			//die;
			$p['token'] = LOCAL_TOKEN;
		}
		$ok = myApiAuthorize($p['token']);
		unset($p['token']);  // so it will not be sent back
		if($ok){
			writeLog($p['action'] . '-authorized',   $debug);
			myRequireOnce ('dirMake.php');
			$debug .= " we are OK \n";
			if (isset($p['page'])){
				$p['page'] = myFile($p['page'] . '.php');
				if (file_exists($p['page'])){
					writeLog($p['action'] . '-required',   $debug);
					$debug .= 'I am adding page ' . $p['page']  . "\n";
					require_once ($p['page'] );
					$action = $p['action'];
					$debug .= 'action is '  . $action ."\n";
					$out = $action ($p);
					writeLog($p['action'] . '-completed',   $debug);
				}
				else{
					$message = $p['page']  . " does not exist";
					writeLog($p['action'] . 'error',  $message);
					$debug .= $message;
                    trigger_error( $message, E_USER_ERROR);
				}
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
	$debug = UNIQUE_API_FILE_DIRECTORY . $page . ".php\n";
	$debug .= STANDARD_API_FILE_DIRECTORY . $page . ".php\n";
	$debug .=  $page . ".php\n";
	writeLog('page', $debug);
	return $page;
}
