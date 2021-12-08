<?php

function updateUser($params){
	$out=[];
	if (!$params['member_uid']){
		 $out['debug'] = 'member_uid not set in updateUser';
		return $out;
	}
	$sql = 'UPDATE members SET
        firstname = "'. $params['firstname'] . '",' .
		'lastname = "'. $params['lastname'] . '",' .
		'scope_countries = "'. $params['scope_countries'] . '",' .
		'scope_languages = "'. $params['scope_languages'] . '", ' .
		'start_page = "'. $params['start_page'] . '" ' .
		' WHERE  uid = ' . $params['member_uid'] . ' LIMIT 1';
	$out['debug'] = $sql . "\n";
    sqlArray($sql, 'update');
	if  ($params['password']){
		// password
		//$out['debug'] .= '|'. $params['password'] . '|' ."\n";
		if (strlen($params['password']) > 5){
			$hash = password_hash($params['password'], PASSWORD_DEFAULT);
		$sql = 'UPDATE members SET
			password = "'. $hash . '"
			 WHERE  uid = ' . $params['member_uid'] . ' LIMIT 1';
		sqlArray($sql, 'update');
		$out['debug'] .= $sql . "\n";
		}
	}
	if  ($params['username']){
		$sql = 'UPDATE members SET
			username = "'. $params['username'] . '"
			 WHERE  uid = ' . $params['member_uid'] . ' LIMIT 1';
		sqlArray($sql, 'update');
		$out['debug'] .= $sql . "\n";
	}
    $out['content'] = 'updated';
    return $out;
}