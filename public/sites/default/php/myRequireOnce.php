<?php
function myRequireOnce($filename, $subdirectory = null){



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

        require_once ($new_name);
    }
    else{
        _appendMyRequireOnce('myRequireOnce', "\n\n$filename\n");
         _appendMyRequireOnce('myRequireOnce', "NOT FOUND\n");
    }
    return;
}

function myRequireOnceSetup($user){
    $_SESSION['user'] = $user;
    return;
}
// in posting we do not have .php, so we need to add it for items posted
// our goal here is to make sure we only work within the specified directories.
function _cleanMyRequireOnce($page){
	$bad = array('.php', '$', '/');
	$page = str_replace($bad, '', $page);
    $bad ='.';
    $page = str_replace($bad, '', $page);
	$page .= '.php';
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