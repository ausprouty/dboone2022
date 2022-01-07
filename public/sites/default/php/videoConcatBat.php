<?php
/*need to make a list for each conccat following the rule

ffmpeg -f concat -safe 0 -i concat/mylist.txt -c copy output.mp4

so we will read the directories of each folder in /sdcard
create a subdirectory of concat mylist and then one master concat bat file.
*/
myRequireOnce('folderArray.php');
myRequireOnce('dirListFiles.php');
myRequireOnce('writeLog.php');

function videoConcatBat(){
    $text = '';
    $dir = ROOT_EDIT . 'sites/'. SITE_CODE . '/sdcard/M2/';
    $languages = folderArray($dir);
    foreach ($languages as $language){
        $lang_dir = $dir . $language .'/';
        $files = dirListFiles($lang_dir);
        writeLog('videoConcatBat-19-files', $files);
        foreach($files as $file){
            $text .= file_get_contents($lang_dir . $file);
            writeLog('videoConcatBat-21-text', $text);
        }

    }

    return $text;
}
