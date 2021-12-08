<?php
myRequireOnce ('sql.php');
function getContentByRecnum($p){
    $sql = "SELECT * from content 
        WHERE recnum = '" .  $p['recnum'] . "'";
    $out['content']= sqlArray($sql);
    return $out;
}