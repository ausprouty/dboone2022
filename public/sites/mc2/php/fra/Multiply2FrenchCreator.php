<?php
return;
require_once ('../.env.api.remote.mc2.php');
myRequireOnce ('sql.php');

define("ROOT_PARENT", '../');

// get template and text
$replacements = array();
$template = file_get_contents(ROOT_PARENT . 'prototype/Multiply2French.html');
$text = file_get_contents(ROOT_PARENT . 'translations/Multiply2French.txt');
$chapters = explode('EBF #', $text);
$this_list_start = FALSE;
$this_section_text = '';
$this_section = '';
$count = 0;
$debug = '';

$lines = explode("\n", $chapter);
$section_number = 0;
$outline = $template;
foreach ($lines as $line){
    $line = trim($line);
    if (strlen($line) > 1){
        $this_line = '';
        $find = $section[$section_number];
        if (mb_strpos( $line , $find) !== FALSE && mb_strpos($line, $find) < 5){
            // close off any needed lists
            if ($this_list_start !== FALSE){
                $this_section_text .= '    </ul>' . "\n";
            }
            // output old section
            if ($this_section){
                $replacement[$this_section] = $this_section_text;
            }
            // start new section
            $this_line = '';
            $this_section = $find;
            $this_section_text = '';
            $this_list_start = FALSE;
            $section_number++;
        }
        else{
            // do we have a list item
            if (mb_strpos($line, '•') !== FALSE){
                if ($this_list_start === FALSE){
                    $this_list_start = TRUE;
                    $this_line = '    <ul>' . "\n";
                }
                $clean_line = trim(str_replace( '•', '', $line));
                $this_line .= '      <li>'. $clean_line . '</li>'. "\n";
            }
            else{
                // we have text; is this part of a section?
                if ($this_section){
                    if ($this_list_start !== FALSE){
                        $this_line = '    </ul>' . "\n";
                    }
                    $this_line = '<p>' . $line . '</p>'. "\n";
                }
                
            }
        }
        $this_section_text .= $this_line;
    }
}
// output last section
if ($this_list_start !== FALSE){
    $this_section_text .= '</ul>' . "\n";
}
$replacement[$this_section] = $this_section_text;
foreach ($blocks as $key=>$value){
    $search = '[' . $key .']';
    if (isset($replacement[$key])){
        $replace =   $replacement[$key];
        $outline = str_replace($search, $replace, $outline);
    }
}
// write file
$count_letter = $num;
if ($count_letter < 10){
    $count_letter = '0' . $count_letter;
}
$filename = 'multiply1'. $count_letter;
$fh = fopen(ROOT_PARENT . 'translations/' . $filename . '.txt', 'w');
fwrite($fh, $outline);
fclose($fh); 
$debug .= "$filename\n";
// create record
$edit_date= time();
$edit_uid= 999;
$language_iso = 'fra';
$country_code = 'M2';
$folder_name = 'multiply';
$filetype = 'html';
$title = '';
$filename = $filename;
$text = htmlentities($outline);
$sql = "INSERT INTO content (version,edit_date, edit_uid, language_iso, country_code, folder_name, filetype, title, filename, text) VALUES
(1, '$edit_date', '$edit_uid', '$language_iso', '$country_code', '$folder_name', '$filetype', '$title', '$filename', '$text')";
$done = sqlInsert($sql);
   
echo nl2br ($debug);