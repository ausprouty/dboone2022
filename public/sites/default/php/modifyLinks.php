<?php
myRequireOnce ('writeLog.php');

function modifyLinks($text){
    // take these out so we can put in proper links later.  The editors like the so they can follow links in the editor.
    $text=str_ireplace ('target="_self"', '', $text);
    $out=[];
    $debug = 'In modify Links';
    $find = '<a href="' . WEBADDRESS_EDIT;  //
    $debug .= 'Find is '. $find ."\n";
    if (strpos($text, $find) !== false){
        $text = modifyEditLinks($text, $find);
    }
    if (WEBADDRESS_STAGING){
        $find = '<a href="' . WEBADDRESS_STAGING;  //
        $debug .= 'Find is '. $find ."\n";
        if (strpos($text, $find) !== false){
             $text  =  modifyPrototypeAndFinalLinks($text, WEBADDRESS_STAGING);
        }
    }
    if (WEBADDRESS_FINAL){
        $find = '<a href="' . WEBADDRESS_FINAL;  //
        $debug .= 'Find is '. $find ."\n";
        if (strpos($text, $find) !== false){
            $text  =  modifyPrototypeAndFinalLinks($text, WEBADDRESS_FINAL);
        }
    }
    // the above should convert all links that are to edit or prototype
    // be changed to '<a href="/content"
    $find = '<a href="/content';
    if (strpos($text, $find) !== false){
        $text = str_ireplace('" >', '">', $text);
        $debug .= 'Find is '. $find ."\n";
        $text  = modifyInternalLinks($text, $find);
    }
    $find = 'href="http';
    if (strpos($text, $find) !== false){
        $text = modifyExternalLinks($text, $find);
    }
    writeLog ('modifyLinks', $debug);

    return $text;
}
/*  <a href="https://generations.edit.myfriends.network/preview/page/A2/eng/library/emc/mc201">
      to
    <a href='/content/A2/eng/emc/mc201.html">
*/
function modifyEditLinks($text, $find){
    $out=[];
    $debug = "\n\n\nIn modifyEditLinks\n";
    $length_find = strlen($find);
    $count = substr_count($text, $find);
    $debug .= "count is $count \n";
    $pos_start = 1;
    for ($i= 1; $i <= $count; $i++){
        $pos_start = strpos($text, $find, $pos_start);
        $pos_end = strpos($text, '"', $pos_start + $length_find );
        $length = $pos_end - $pos_start;
        $old = substr($text, $pos_start, $length);
        $new_link = str_ireplace($find .'/preview/page/', '/content/', $old);
        $new_link = str_ireplace('/library/', '/', $new_link) . '.html';
        $new = '<a href="'. $new_link. '">';
        $text = str_replace($old, $new, $text);
    }
    writeLog('ModifyEditLinks',$text );
    return $text;
}
/*  <a href="https://generations.prototype.myfriends.network/content/A2/eng/emc/mc201.html">
      to
    <a href='/content/A2/eng/emc/mc201.html">
*/
function modifyPrototypeAndFinalLinks($text, $replace){
    $debug = "\n\n\nIn modifyPrototypeLinks\n";
    $text = str_replace ($replace, '', $text);
    return $text;
}
/*  <a href="/sites/mc2/content/M2/eng/tc/tc01.html">
      to
    <a  href="#" onclick="goToPageAndSetReturn('/content/M2/eng/tc/tc01.html');">

    $find = '<a href="/content'
*/
function modifyInternalLinks($text, $find){
    $debug = "\nIn modifyInternalLinks\n";
    $length_find = strlen($find);
    $count = substr_count($text, $find);
    $debug .= "count is: $count : \n";
    $pos_start = 1;
    for ($i= 1; $i <= $count; $i++){
        $pos_start = strpos($text, $find, $pos_start) ;
        $pos_end = strpos($text, '">', $pos_start + $length_find);
        $content_length = $pos_end - $pos_start -  $length_find;
        $link = substr($text, $pos_start + $length_find , $content_length);
        $link_length=$pos_end-$pos_start + 2; // plus two for the length of the end
        $debug .= "link is: $link \n";
        $old = '<a href="/content'. $link .'">';
        $debug .= "old is $old \n";
        $new = '<a id = "{id}" href="#" onclick="goToPageAndSetReturn(\'/content'. $link. '\', \'#{id}\');">';
        $new = str_replace('{id}', 'Return' . $i , $new );
        $text = substr_replace($text, $new, $pos_start, $link_length);
        $pos_start =
        writeLog('modifyInternalLinks' . $i, $debug . $text);
    }
   // writeLog('modifyInternalLinks', $text);
    return $text;
}

/*  <a href="https://somewhere.com">
      to
    <a target="a_blank" href="https://somewhere.com">
*/
function modifyExternalLinks($text, $find){
    $debug = "\n\n\nIn modifyExternalLinks\n";
    $text = str_ireplace ('href="http', ' target = "_blank" href="http', $text);

   // writeLog('modifyInternalLinks', $text);
    return $text;
}