<?php
/* Here we copy the files that all sdcards need, but are not sourced from content direcly
/sites/mc2/images/css ->sdcard.mc2.something/folder/sites/mc2/images/standard
/sites/mc2/images/standard -> sdcard.mc2.something/folder/sites/mc2/images/standard
/sites/default/sdcard/Cx File Explorer.apk-> sdcard.mc2.something/Cx File Explorer.apk
*/


function verifyCommonFiles($p){
  $p['dir_sdcard'] = ROOT_SDCARD . _verifyBookClean($p['sdcard_settings']->subDirectory) .'/';
}