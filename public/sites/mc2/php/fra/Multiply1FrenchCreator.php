<?php
return;
require_once ('../.env.api.remote.mc2.php');
myRequireOnce ('sql.php');

define("ROOT_PARENT", '../');
$output = '';
$blocks = array(
    'Louange'=> '<h2 class="back">Louange</h2>',
    'Soin'=> '<h2 class="back">Soin</h2>',
    'Célébrer la Fidélité' => '<h2 class="back">C&eacute;l&eacute;brer la Fid&eacute;lit&eacute;</h2>',
    'Projection de Vision' => '<h2 class="back">Projection de Vision</h2>',
    'Contexte'=> '<h2 class="up">Contexte</h2>',
    'Lire' => '<h2 class="up">Lire</h2>',
    'Discussion de Découverte' => '<h2 class="up">Discussion de D&eacute;couverte</h2>',
    'Lire, Raconter et Corriger' => '<h2 class="up">Lire, raconter et corriger</h2>',
    'Résumé' => '<h2 class="up">R&eacute;sum&eacute;</h2>',
    'Se préparer pour la Mission'=> '<h2 class="forward">Se Pr&eacute;parer pour la Mission</h2>',
    'Aller en Mission' => '<h2 class="forward">Aller en Mission&nbsp;</h2>',
    'Prier pour la Mission' => '<h2 class="forward">Prier pour la Mission</h2>',
);
// get blocks in order
$section = array();
foreach ($blocks as $key => $value){
    $section[] = $key;
}
$section[] = 'eof';
// get template and text
$replacements = array();
$template = file_get_contents(ROOT_PARENT . 'prototype/Multiply1French.html');
$text = file_get_contents(ROOT_PARENT . 'translations/Multiply1French.txt');
$chapters = explode('EBF #', $text);
$this_list_start = FALSE;
$this_section_text = '';
$this_section = '';
$count = 0;
$debug = '';
foreach ($chapters as $chapter){
    $count++;
    $num = $count-1;
    $ok = true;
    // checking to make sure each block is there
    foreach ($blocks as $key => $value){
        $pos = strpos($chapter, $key);
        if ($pos === FALSE  && $count > 1 && $count < 18){
            $output .= "chapter $num --- $key\r";
            $ok = false;
        }
    }
    if ($ok){
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
    }
}
echo nl2br ($debug);