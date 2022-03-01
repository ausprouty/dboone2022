<?php
function myRequireOnce($filename, $subdirectory = null){
    _appendMyRequireOnce('myRequireOnce', "\n\n$subdirectory/$filename\n");
    $new_name = null;
    $filename =_cleanMyRequireOnceFile($filename);
    if ($subdirectory){
        $subdirectory =_cleanMyRequireOnceSubdirectory($subdirectory);
        $new_name = myRequireOnceDirectories($subdirectory . '/'.$filename);
    }
    if (!$new_name){
        $new_name = myRequireOnceDirectories($filename);
    }
    if ($new_name){
        _appendMyRequireOnce('myRequireOnce', "$new_name\n");
      require_once($new_name);
    }
    else{
        _appendMyRequireOnce('myRequireOnce', "\n\n$subdirectory/$filename\n");
        _appendMyRequireOnce('myRequireOnce', "NOT FOUND\n");
    }
    return;
}

function myRequireOnceDirectories($filename){
    $new_name = null;
    if (file_exists(UNIQUE_API_FILE_DIRECTORY . $filename)){
        $new_name = UNIQUE_API_FILE_DIRECTORY . $filename;
    }
    else if (file_exists(STANDARD_API_FILE_DIRECTORY . $filename)){
        $new_name = STANDARD_API_FILE_DIRECTORY . $filename;
    }
    if (isset($_SESSION['user']) ){
        if (file_exists(TESTING_API_FILE_DIRECTORY . $filename) && $_SESSION['user'] ==  DEVELOPER){
            $new_name = TESTING_API_FILE_DIRECTORY . $filename;
        }
    }
    return $new_name;
}

function myRequireOnceSetup($user){
    $_SESSION['user'] = $user;
    return;
}
// in posting we do not have .php, so we need to add it for items posted
// our goal here is to make sure we only work within the specified directories.
function _cleanMyRequireOnceFile($page){
	$bad = array('.php', '$', '/');
	$page = str_replace($bad, '', $page);
    $bad ='.';
    $page = str_replace($bad, '', $page);
	$page .= '.php';
	return $page;
}
function _cleanMyRequireOnceSubdirectory($page){
	$bad = array('.', '$', '/');
	$page = str_replace($bad, '', $page);
	return $page;
}

function _appendMyRequireOnce($filename, $content){
    $root_log = ROOT_LOG;
    if (!is_array($content)){
        $text = $content;
    }
    else{
        $text = '';
        foreach ($content as $key=> $value){
            $text .= $key . ' => '. $value . "\n";
        }
    }
    $fh = $root_log . $filename . '.txt';
    file_put_contents($fh, $text,  FILE_APPEND | LOCK_EX );
}