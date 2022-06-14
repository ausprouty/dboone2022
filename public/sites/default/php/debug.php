<?php
myRequireOnce ('folderArray.php');

function debugSeriesCrawlX ($p){
    return;
    $version = '1.0';
    $edit_date = time();
    $edit_uid = 11;
    $ready = array(
        'amh'=>'ለከመስመር ውጭ አጠቃቀም ዝግጁ',
        'arb'=>'جاهز للاستخدام في وضع عدم الاتصال',
        'deu'=>'Offline verfügbar',
        'eng' =>'Ready for offline use',
        'fra'=>'Prêt pour une utilisation hors ligne',
        'gaz'=>'Ready for offline use',
        'hin'=>'ऑफ़लाइन उपयोग के लिए तैयार है',
        'hinr'=>'Ŏphalaa_in upayog ke lie taiyaar hai',
        'por'=>'Handa para sa paggamit ng off-line',
        'spa'=>'Listo para uso sin internet',
        'tam'=>'ஆஃப்லைன் பயன்பாட்டிற்கு தயாராக உள்ளது',
        'urd'=>'آف لائن استعمال کے لئے تیار',
        'zhs'=>'可供离线使用',

    );
    $download = array(
        'amh'=>'ለመስመር ውጪ ለመጠቀም ያውርዱ',
        'arb'=>'حفظ للاستخدام في وضع عدم الاتصال',
        'deu'=>'Offline verfügbar machen',
        'eng' => 'Download for offline use',
        'fra'=>'Télécharger pour une utilisation hors ligne',
        'gaz'=>'Download for offline use',
        'hin'=>'ऑफ़लाइन उपयोग के लिए डाउनलोड करें',
        'hinr'=>'Ŏphalaa_in upayog ke lie taiyaar hai',
        'por'=>'Download para uso off-line',
        'spa'=>'Guardar para uso sin internet',
        'tam'=>'ஆஃப்லைன் பயன்பாட்டிற்காக பதிவிறக்கவும்',
        'urd'=>'آف لائن استعمال کیلئے ڈاؤن لوڈ کریں',
        'zhs'=>'保存以供离线使用',
    );

    $conn = new mysqli(HOST, USER, PASS, DATABASE);
    if ($conn->connect_error) {
        die("Connection has failed: " . $conn->connect_error);
    }
    $output = new stdClass();

    $edit_root = ROOT_EDIT .  'content';
    $debug = 'in debugCrawl' . "\n";
    $countries = folderArray($edit_root);
    foreach ($countries as $country){
        $country_dir = $edit_root . '/'. $country;
        $languages = folderArray($country_dir);
        foreach ($languages as $language){
            $language_dir =  $country_dir .'/'. $language;
            $folders = folderArray($language_dir);
            foreach ($folders as $folder) {
                $index = $language_dir .'/'. $folder. '/index.json';
                if (file_exists($index) && $language != 'templates'){
                    // make sure we do not already have an edited record.
                    $sql = "SELECT recnum FROM content WHERE language_iso = '$language' AND
                        country_code =  '$country' AND folder_name =  '$folder' AND filename = 'index'";
                    $q = $conn->query($sql);
                    $new = $q->fetch_object();
                    if (!isset($new->recnum)){
                        $new = [];
                        $o = json_decode(($index));
                        //get rid of old data
                        if (isset($o->series)){
                            unset ($o->series);
                        }
                        if (isset($o->language)){
                            unset ($o->language);
                        }
                        if (!isset($o->image)){
                            $o->image = '';
                        }
                        $o->download_now = $download[$language];
                        $o->download_ready = $ready[$language];
                        $text = json_encode($o, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
                        $text = $conn->real_escape_string($text);
                        $sql = "INSERT into content (version,edit_date,edit_uid,language_iso,
                            country_code,folder_name,filetype,title,filename,text) values
                            ('$version','$edit_date','$edit_uid','$language',
                            '$country','$folder','json',
                            '','index','$text')";
                        $debug .= $sql . "\n";
                        $conn->query($sql);
                    }
                }

            }
        }
    }
    return $out;
}

function debugLibraryCrawl ($p){
    $version = '1.0';
    $edit_date = time();
    $edit_uid = 11;
    $conn = new mysqli(HOST, USER, PASS, DATABASE);
    if ($conn->connect_error) {
        die("Connection has failed: " . $conn->connect_error);
    }
    $output = new stdClass();

    $edit_root = ROOT_EDIT .  'content';
    $debug = 'in debugCrawl' . "\n";
    $countries = folderArray($edit_root);
    foreach ($countries as $country){
        $country_dir = $edit_root . '/'. $country;
        $languages = folderArray($country_dir);
        foreach ($languages as $language){
            $library = $country_dir .'/'. $language . '/library.json';
            if (file_exists($library) && $language != 'templates'){
                $sql = "select recnum FROM content WHERE language_iso = '$language' AND
                    country_code =  '$country' AND filename = 'library'";
                $q = $conn->query($sql);
                $new = $q->fetch_object();
                if (!isset($new->recnum)){
                    $output->text = '';
                    $output->image = 'journey.jpg';
                    $new = [];
                    $old = json_decode(($library));
                    foreach ($old as  $o){
                        if (isset($o->folder)){
                            unset ($o->folder);
                        }
                        if (isset($o->index)){
                            unset ($o->index);
                        }
                        if (isset($o->book)){
                            $o->name = $o->book;
                            unset ($o->book);
                        }
                        if ($o->name == 'principles'){
                            $o->name = 'life';
                        }
                        $new[] = $o;
                    }
                    $output->books = $new;
                    $text = json_encode($output, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
                    $text = $conn->real_escape_string($text);
                    $sql = "INSERT into content (version,edit_date,edit_uid,language_iso,
                        country_code,folder_name,filetype,title,filename,text) values
                        ('$version','$edit_date','$edit_uid','$language',
                        '$country','','json',
                        '','library','$text')";
                    $debug .= $sql . "\n";
                    $conn->query($sql);
                }
            }
        }
    }
    return $out;
}


function debugClean($p){
    $conn = new mysqli(HOST, USER, PASS, DATABASE);
    if ($conn->connect_error) {
        die("Connection has failed: " . $conn->connect_error);
    }
    $sql = 'SELECT * FROM content ORDER BY recnum DESC';
    $query = $conn->query($sql);
    while ($data = $query->fetch_object()){
       // $sql = "UPDATE content SET text = '$new' WHERE renum = $recnum'";
       $sql = "SELECT recnum FROM new_content WHERE language_iso = '$data->language_iso' AND
            country_code =  '$data->country_code' AND folder_name = '$data->folder_name'
            AND  filetype = '$data->filetype' AND filename = '$data->filename'";
        $q = $conn->query($sql);
        $new = $q->fetch_object();
        if (!isset($new->recnum)){
            $text = $conn->real_escape_string($data->text);
            $sql = "INSERT into new_content (version,edit_date,edit_uid,language_iso,
                country_code,folder_name,filetype,title,filename,text) values
                ('$data->version','$data->edit_date','$data->edit_uid','$data->language_iso',
                '$data->country_code','$data->folder_name','$data->filetype',
                '$data->title','$data->filename','$text')";
            $debug .= $sql . "\n";
            $conn->query($sql);
        }
    }
    return $out;
}
function debugLibraryX($p){
    $conn = new mysqli(HOST, USER, PASS, DATABASE);
    if ($conn->connect_error) {
        die("Connection has failed: " . $conn->connect_error);
    }
    $sql = 'SELECT * FROM content WHERE filename = "library" ';
    $debug = $sql . "\n";
    $output = new stdClass();
    $query = $conn->query($sql);
    while ($data = $query->fetch_object()){
        $output->text = '';
        $output->image = 'journey.jpg';
        $old = json_decode($data->text);
        $new = [];
        foreach ($old as  $o){
            if (isset($o->folder)){
                unset ($o->folder);
            }
            if (isset($o->index)){
                unset ($o->index);
            }
            if (isset($o->book)){
                $o->name = $o->book;
                unset ($o->book);
            }
            if ($o->name == 'principles'){
                $o->name = 'life';
            }
            $new[] = $o;
        }
        $output->books = $new;
        $text = json_encode($output, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        $text = $conn->real_escape_string($text);

       $sql = "INSERT into content (version,edit_date,edit_uid,language_iso,
            country_code,folder_name,filetype,title,filename,text) values
            ('$data->version','$data->edit_date','$data->edit_uid','$data->language_iso',
            '$data->country_code','$data->folder_name','$data->filetype',
            '$data->title','$data->filename','$text')";
        $debug .= $sql . "\n";
        $conn->query($sql);
    }
    return $out;
}
function debugLife($p){
    $conn = new mysqli(HOST, USER, PASS, DATABASE);
    if ($conn->connect_error) {
        die("Connection has failed: " . $conn->connect_error);
    }
    $sql = 'SELECT * FROM content WHERE filename = "library"';
    $debug = $sql . "\n";
    $query = $conn->query($sql);
    while ($data = $query->fetch_object()){
        $text = str_ireplace('principle2', 'life2', $data->text);
        $text = str_ireplace('"principles"', '"life"', $text);
        $text = $conn->real_escape_string($text);
        $edit_date = time();
        $my_uid = $p['my_uid'];

       $sql = "INSERT into content (version,edit_date,edit_uid,language_iso,
            country_code,folder_name,filetype,title,filename,text) values
            ('$data->version','$data->edit_date','$data->edit_uid','$data->language_iso',
            '$data->country_code','$data->folder_name','$data->filetype',
            '$data->title','$data->filename','$text')";
        $debug .= $sql . "\n";
        $conn->query($sql);
    }
    return $out;
}
function debugSeries($p){
    $ready = array(
        'amh'=>'ለከመስመር ውጭ አጠቃቀም ዝግጁ',
        'arb'=>'جاهز للاستخدام في وضع عدم الاتصال',
        'deu'=>'Offline verfügbar',
        'eng' =>'Ready for offline use',
        'fra'=>'Prêt pour une utilisation hors ligne',
        'gaz'=>'Ready for offline use',
        'hin'=>'ऑफ़लाइन उपयोग के लिए तैयार है',
        'hinr'=>'Ŏphalaa_in upayog ke lie taiyaar hai',
        'por'=>'Handa para sa paggamit ng off-line',
        'spa'=>'Listo para uso sin internet',
        'tam'=>'ஆஃப்லைன் பயன்பாட்டிற்கு தயாராக உள்ளது',
        'urd'=>'آف لائن استعمال کے لئے تیار',
        'zhs'=>'可供离线使用',

    );
    $download = array(
        'amh'=>'ለመስመር ውጪ ለመጠቀም ያውርዱ',
        'arb'=>'حفظ للاستخدام في وضع عدم الاتصال',
        'deu'=>'Offline verfügbar machen',
        'eng' => 'Download for offline use',
        'fra'=>'Télécharger pour une utilisation hors ligne',
        'gaz'=>'Download for offline use',
        'hin'=>'ऑफ़लाइन उपयोग के लिए डाउनलोड करें',
        'hinr'=>'Ŏphalaa_in upayog ke lie taiyaar hai',
        'por'=>'Download para uso off-line',
        'spa'=>'Guardar para uso sin internet',
        'tam'=>'ஆஃப்லைன் பயன்பாட்டிற்காக பதிவிறக்கவும்',
        'urd'=>'آف لائن استعمال کیلئے ڈاؤن لوڈ کریں',
        'zhs'=>'保存以供离线使用',
    );

    $conn = new mysqli(HOST, USER, PASS, DATABASE);
    if ($conn->connect_error) {
        die("Connection has failed: " . $conn->connect_error);
    }
    $sql = 'SELECT * FROM content WHERE filename = "library"';
    $debug = $sql . "\n";
    $query = $conn->query($sql);
    while ($data = $query->fetch_object()){
        $output->description = '';
        $lang = $data->language_iso;
        $output->download_now = $download[$lang];
        $output->download_ready = $ready[$lang];
        $output->chapters = json_decode($data->text);
        $text = json_encode($output, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        $text = $conn->real_escape_string($text);

       $sql = "INSERT into content (version,edit_date,edit_uid,language_iso,
            country_code,folder_name,filetype,title,filename,text) values
            ('$data->version','$data->edit_date','$data->edit_uid','$data->language_iso',
            '$data->country_code','$data->folder_name','$data->filetype',
            '$data->title','$data->filename','$text')";
        $debug .= $sql . "\n";
        $conn->query($sql);
    }
    return $out;
}

function folderArray($path){
	if (file_exists($path)){
		$results =[];
		$dir = new DirectoryIterator($path);
		foreach ($dir as $fileinfo) {
			if ($fileinfo->isDir() && !$fileinfo->isDot()) {
				$results[] = $fileinfo->getFilename();
			}
		}
	}
 return $results;
}