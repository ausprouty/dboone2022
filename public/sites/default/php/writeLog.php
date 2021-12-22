
<?php

function writeLog($filename, $content){
	if (LOG_MODE !== 'write_log' &&  LOG_MODE !== 'write_time_log'){
       return;
	}
	if ( LOG_MODE == 'write_time_log'){
       $filename =   time() . '-' . $filename;
	}
	$text = var_dump_ret($content);
    if (!file_exists(ROOT_LOG)){
		mkdir(ROOT_LOG);
	}
	$fh = fopen(ROOT_LOG . $filename . '.txt', 'w');
	fwrite($fh, $text);
    fclose($fh);

}
function writeLogError($filename, $content){
	$text = var_dump_ret($content);
    if (!file_exists(ROOT_LOG)){
		mkdir(ROOT_LOG);
	}
	$fh = fopen(ROOT_LOG . $filename . '.txt', 'w');
	fwrite($fh, $text);
    fclose($fh);
}

function var_dump_ret($mixed = null) {
  ob_start();
  var_dump($mixed);
  $content = ob_get_contents();
  ob_end_clean();
  return $content;
}

function XwriteLog($filename, $content){
     $space = '   ';
	 $space2= '       ';
	 $space3= '           ';
	 $text = 'WriteLog '. "\n";
	if (LOG_MODE !== 'write_log' &&  LOG_MODE !== 'write_time_log'){
       return;
	}
	if ( LOG_MODE == 'write_time_log'){
       $filename =   time() . '-' . $filename;
	}
	if (is_object($content)){
      $content = objectToArray($content);
	}
	if (!is_array($content)){
		$text = $content;
	}
	else{
		foreach ($content as $key=> $value){
			if (is_array($value)){
				$text .= $key . ' => '. "\n";
				foreach ($value as $k => $v){
					if (!is_object($v)){
						$text .= $space . $k . ' => '. $v . "\n";
					}
					else{
						$value_array= objectToArray($v);
						foreach ($value_array as $k2 => $v2){
							$text .= $space2 . $k2 . ' -> '. $v2 . "\n";
						}
					 }
				}
			}
			elseif (is_object($value)){
				$text .= $key . ' => '. "\n";
				$value_array= objectToArray($value);
				foreach ($value_array as $k => $v){
                    if (is_array($v)){
						$text .= $space .  $k . '=>'. "\n";
						foreach ($v as $k2 => $v2){
							if (is_object($v2)){
								$text .= $space2 . $k2 . '=>'. "\n";
								$value_array2= objectToArray($v2);
								foreach ($value_array2 as $k2 => $v2){
									$text .= $space2 .  $k2 . ' -> '. $v2 . "\n";
								}
					 		}
							else{

								$text .= $space2 . $k2 . ' -> '. $v2 . "\n";
							}

						}
					}
					if (is_object($v)){
						$text .= $k . '=>'. "\n";
						$value_array2= objectToArray($v);
						foreach ($value_array2 as $k2 => $v2){
							if (is_object($v2)){
								$text .= $space2 . $k2 . '=>'. "\n";
								$value_array3= objectToArray($v3);
								foreach ($value_array3 as $k3 => $v3){
									$text .= $space3.  $k3 . ' -> '. $v3 . "\n";
								}
							}
							else{
								$text .= $space2 . $k2 . ' -> '. $v2 . "\n";
							}

						}
					 }
					 else{
						 if (is_object($v)){
							$v= objectToArray($v);
						 }
						 if (!is_array()){
                           $text .= $space . $k . ' => '. $v . "\n";
						 }
						 else{
							 $text .= $space . $k . "\n";
							 foreach ($v as $k2 => $v2){
									$text .= $space2.  $k2 . ' -> '. $v2 . "\n";
								}
						 }

					}
				}
			}
			else{
  				$text .= $key . ' => '. $value . "\n";
			}
		}
	}
	if (!file_exists(ROOT_LOG)){
		mkdir(ROOT_LOG);
	}
	$fh = fopen(ROOT_LOG . $filename . '.txt', 'w');
	fwrite($fh, $text);
    fclose($fh);
}

function objectToArray($object)
{
    foreach ($object as $k => $obj) {
        if (is_object($obj)) {
            $object->$k = objectToArray($obj);
        } else {
            $object->$k = $obj;
        }
    }
    return (array) $object;
}