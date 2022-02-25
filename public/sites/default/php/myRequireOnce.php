<?php
function myRequireOnce($filename, $subdirectory = null){

     _appendMyRequireOnce('myRequireOnce', "\n\n$subdirectory/$filename\n");

    $new_name = 'not found';
    $filename =_cleanMyRequireOnce($filename);
    if ($subdirectory){
        $subdirectory =_cleanMyRequireOnce($subdirectory);
        $filename =$subdirectory . '/'.$filename;
    }
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
    if ($new_name != 'not found'){
        _appendMyRequireOnce('myRequireOnce', "$new_name\n");
        require_once ($new_name);
    }
    return;
}

function myRequireOnceSetup($user){
    $_SESSION['user'] = $user;
    return;
}

function _cleanMyRequireOnce($page){
	$bad = array('.', '$', '/');
	$page = str_replace($bad, '', $page);
	$page .= '.php';
	return $page;
}

function _appendMyRequireOnce($filename, $content){
    if (LOG_MODE !== 'write_log'){
      return;
    }
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