<?php
myRequireOnce ('writeLog.php');
// is this item authorized to be published?
function publishReady($item, $destination){

switch ($destination){
    case"prototype":
        if ($item->prototype){
            return true;
        }
        else{
            return false;
        }
    case "nojs":
    case "publish":
    case "sdcard":
        if ($item->publish){
            return true;
        }
        else{
          return false;
        }
    default:
        return false;
    }
}