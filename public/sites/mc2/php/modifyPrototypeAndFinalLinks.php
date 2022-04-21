<?php

/*  <a href="https://app.mc2.online/content/A2/eng/emc/mc201.html">
      to
    <a href='/content/A2/eng/emc/mc201.html">

    except for page/apk.html
*/
myRequireOnce('writeLog.php');
function modifyPrototypeAndFinalLinks($text, $replace, $p){
    $special = 'https://app.mc2.online/content/'. $p['country_code'] . '/'. $p['language_iso'] .'/pages/apk.html';
    if (strpos($text, $special) !== FALSE){
        $text= str_replace($special, 'https:special', $text);
        $text = str_replace ($replace, '', $text);
        $text= str_replace( 'https:special', $special, $text);
       return $text;
    }
    $text = str_replace ($replace, '', $text);
    return $text;
}