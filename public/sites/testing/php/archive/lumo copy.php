<?php
require_once ('../.env.api.remote.4G.php');
echo ('started');
myRequireOnce ('writeLog.php');
$text=  _file_get_contents_utf8(TESTING_API_FILE_DIRECTORY .  'lumo.csv');
$fields = array('Language Name','Page','JFcode','afrikaans','arabic-modern-standard','bengali-indian','cantonese','chinese-mandarin','danish','dutch','english','farsi-western','finnish','french','german-standard','hindi','indonesian-yesus','italian','japanese','korean','norwegian-bokmal','polish','portuguese-brazil','russian','spanish-latin-american','swahili-tanzania','swedish','tagalog','thai','turkish','urdu');
$acts = array('english', 'french', 'arabic-modern-standard', 'spanish-latin-american', 'russian', 'chinese-mandarin', 'portuguese-brazil' );
$interface = array('english');

$template_begin = '{
    "jfilm": "1_[JFcode]",
    "lumo" : "6_[JFcode]",
    ';
$template_end = '
    "folder" : "[Page].html",
    ';

$line_count = 0;
$output = '';
$lines = explode("\n", $text);
foreach ($lines as $line){
    $output .= $line . "|$line_count\n";
    $items = explode (',', $line);
    $line_count++;
    if ($line_count > 1){
        if (isset($items[2])){
            $video = $template_begin;
            $jfcode = $items[2];
            $page = $items[1];
            if (in_array($page, $acts)){
                $video .= '"acts" : "2_[JFcode]",
                   ';
            }
            $good = array($jfcode, $page);
            $bad = array('[JFcode]', '[Page]');
            $video = str_ireplace ($bad, $good, $video);
            $item_count = 0;
            foreach ($items as $item){
                if ($item_count > 2 ){
                    if (in_array( $fields[$item_count],  $interface )){
                        $video .= '    "' . $fields[$item_count] .'":"'. trim($item) . '",'. "\n";
                    }
                }
                $item_count++;
            }
            $video = substr($video,0, -2);
            $video .= "\n}$line_count ,\n";
            $output .= $video;
            $video = '';
        }
       
    }
    echo ($output);
    writeLog ('lumo', $output);

}



function _file_get_contents_utf8($fn) {
    $content = file_get_contents($fn);
     return mb_convert_encoding($content, 'UTF-8',
         mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true));
}
