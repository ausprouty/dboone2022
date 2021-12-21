<?php
function publishReady($item, $destination){
switch ($destination){
    case"prototype":
        if ($item->prototype){
            return true;
        }
        else{
        return false;
        }
    case "publish":
    case "usb":
        if ($item->publish){
            return true;
        }
        else{
        return false;
        }
    }
return false;

}