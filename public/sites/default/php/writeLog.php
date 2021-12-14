<?php
function writeLog($filename, $content){
	$filename =   time() . '-' . $filename;
	if (is_object($content)){
      $content=objectToArray($content);
	}
	if (!is_array($content)){
		$text = $content;
	}
	else{
		$text = '';
		foreach ($content as $key=> $value){
			if (is_array($value)){
				$text .= $key . ' => '. "\n";
				foreach ($value as $k => $v){
					if (!is_object($v)){
						$text .= $k . ' => '. $v . "\n";
					}
					else{
						$value_array= objectToArray($v);
						foreach ($value_array as $k => $v){
							$text .= $k . ' -> '. $v . "\n";
						}
					 }
				}
			}
			elseif (is_object($value)){
				$text .= $key . ' => '. "\n";
				$value_array= objectToArray($value);
				foreach ($value_array as $k => $v){
					$text .= $k . ' -> '. $v . "\n";
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