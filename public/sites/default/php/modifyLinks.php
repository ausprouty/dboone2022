<?php
myRequireOnce ('writeLog.php');

function modifyLinks($text, $p){
    // take these out so we can put in proper links later.  The editors like the URL so they can follow links in the editor.
    $text=str_ireplace ('target="_self"', '', $text);
    $out=[];
    $find = '<a href="' . WEBADDRESS_EDIT;  //
    if (strpos($text, $find) !== false){
        $text = _modifyEditLinks($text, $find);
    }
    if (WEBADDRESS_STAGING){
        $find = '<a href="' . WEBADDRESS_STAGING;  //
        if (strpos($text, $find) !== false){
             $text  =  _modifyPrototypeAndFinalLinks($text, WEBADDRESS_STAGING);
        }
    }
    if (WEBADDRESS_FINAL){
        $find = '<a href="' . WEBADDRESS_FINAL;  //
        if (strpos($text, $find) !== false){
            $text  =  _modifyPrototypeAndFinalLinks($text, WEBADDRESS_FINAL);
        }
    }
    // version2 content references are /sites/mc2/content
     $find = '<a href="/sites/'. SITE_CODE ;  //
        if (strpos($text, $find) !== false){
            $text  =  str_replace( $find, '<a href="', $text);
        }
    // the above should convert all links that are to edit or prototype
    // be changed to '<a href="/content"
    $find = '<a href="/content';
    if (strpos($text, $find) !== false){
        $text = str_ireplace('" >', '">', $text);
        $text  = _modifyInternalLinks($text, $find, $p);
    }
    if (($p['destination'] == 'nojs' || $p['destination'] == 'sdcard') && ALLOW_EXTERNAL_LINKS_IN_SDCARD == FALSE){
        writeLogError('modifyLinks-37', 'I am about the check for remove readmore Links');
         $find = '<a class="readmore"';
         if (strpos($text, $find) !== false){
             writeLogError('modifyLinks-40', 'I am going to remove readmore Links');
            $text = _removeReadmoreLinks($text);
        }
    }
    if (strpos($text, $find) !== false){
        $text = _modifyExternalLinks($text, $find, $p);
    }
    if ( $p['destination'] == 'nojs' || $p['destination']== 'pdf' ){
        $find = '<a href="javascript:popUp';
    if (strpos($text, $find) !== false){
        $text = _modifyPopupLinks($text, $find);
    }

    }
   //writeLog('modifyLinks', $debug);

    return $text;
}
/*   <a href="javascript:popUp('pop2')">Philippians 1:6</a>
     to
    Philippians 1:6
    (only used by nojs)
*/

function _modifyPopupLinks($text, $find){
    $out=[];
    $length_find = strlen($find);
    $count = substr_count($text, $find);
    $pos_start = 1;
    for ($i= 1; $i <= $count; $i++){
        $pos_start = strpos($text, $find, $pos_start);
        $pos_java_end = strpos($text, '">', $pos_start + $length_find );
        $length = $pos_java_end - $pos_start + 2; //because need end of ">
        $old = substr($text, $pos_start, $length);
        $pos_a_start = strpos($text, '</a>', $pos_java_end);
        $text = substr_replace($text, '', $pos_a_start, 4);
        $text = str_replace($old, '', $text);
    }
    //writeLog('ModifyEditLinks',$text );
    return $text;
}
/*  <a href="https://generations.edit.myfriends.network/preview/page/A2/eng/library/emc/mc201">
      to
    <a href='/content/A2/eng/emc/mc201.html">
*/
function _modifyEditLinks($text, $find){
    $out=[];
    $length_find = strlen($find);
    $count = substr_count($text, $find);
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
    //writeLog('ModifyEditLinks',$text );
    return $text;

}

/*  <a href="https://generations.prototype.myfriends.network/content/A2/eng/emc/mc201.html">
      to
    <a href='/content/A2/eng/emc/mc201.html">
*/
function _modifyPrototypeAndFinalLinks($text, $replace){
    $text = str_replace ($replace, '', $text);
    return $text;
}
/*  <a href="/sites/mc2/content/M2/eng/tc/tc01.html">
       for site to
    <a  href="#" onclick="goToPageAndSetReturn('/content/M2/eng/tc/tc01.html');">
        for sdcard to
    <a  href="#" onclick="goToPageAndSetReturn('/folder/content/M2/eng/tc/tc01.html');">
     for nojs to
      <a  href="/folder/nojs/M2/eng/tc/tc01.html"

    $find = '<a href="/content'
*/
function _modifyInternalLinks($text, $find, $p){
   // $rand= random_int(0, 9999);
    $length_find = strlen($find);
    $count = substr_count($text, $find);
    $pos_start = 1;
    for ($i= 1; $i <= $count; $i++){
        $pos_start = strpos($text, $find, $pos_start) ;
        $pos_end = strpos($text, '">', $pos_start + $length_find);
        $content_length = $pos_end - $pos_start -  $length_find;
        $link = substr($text, $pos_start + $length_find , $content_length);
        $link_length=$pos_end-$pos_start + 2; // plus two for the length of the end
        $old = '<a href="/content'. $link .'">';
        $new = '<a id = "{id}" href="#" onclick="goToPageAndSetReturn(\'/content'. $link. '\', \'#{id}\');">';
        if ($p['destination'] == 'sdcard'){
           $link = _makeRelativeLinkSDCard($link, 'content');
           $new = '<a id = "{id}" href="#" onclick="goToPageAndSetReturn(\''. $link. '\', \'#{id}\');">';
        }
        elseif ($p['destination'] == 'nojs'){
            $link = _makeRelativeLinkSDCard($link, 'nojs');
            $new = '<a  href="'. $link .'">';
        }
        $new = str_replace('{id}', 'Return' . $i , $new );
        $text = substr_replace($text, $new, $pos_start, $link_length);
        $pos_start = $pos_end;
        //writeLog('_modifyInternalLinks' . $i, $debug . $text);
    }
   // //writeLog('_modifyInternalLinks', $text);
    return $text;
}

/*  <a href="https://somewhere.com">
      to
    <a target="a_blank" href="https://somewhere.com">
*/
function _modifyExternalLinks($text, $find, $p){
    if ($p['destination']  !=='sdcard' && $p['destination'] !=='nojs'){
        $text = str_ireplace ('href="http', ' target = "_blank" href="http', $text);
        return $text;
    }
    if (ALLOW_EXTERNAL_LINKS_IN_SDCARD == TRUE){
        $text = str_ireplace ('href="http', ' target = "_blank" href="http', $text);
        return $text;
    }
    $message = 'external link found.  How do you want to process?';
    //writeLogError('_modifyExternalLinks', "$message\n$text");
    trigger_error( $message, E_USER_ERROR);
    return $text;
}
 // <a class="readmore"  href="https://biblegateway.com/passage/?search=John%2010:22-30&amp;version=NIV">Read More </a>
// these need to come out in sensetive countries
function _removeReadmoreLinks($text){
    //writeLogError('_removeReadmoreLinks-173', $text);
    $find = '<a class="readmore"';
    $length_find = strlen($find);
    $count = substr_count($text, $find);
    $pos_start = 1;
    for ($i= 1; $i <= $count; $i++){
        $pos_start = strpos($text, $find, $pos_start) ;
        $pos_end = strpos($text, '</a>', $pos_start + $length_find);
        $length = $pos_end - $pos_start + 4;
        $text = substr_replace($text, '', $pos_start, $length);
        $pos_start = $pos_end;
    }
    //writeLogError('_removeReadmoreLinks-185', $text);
    return $text;
}

function   _makeRelativeLinkSDCard($link, $folder){
    $new ='../../..' . $link;
    return $new;

}