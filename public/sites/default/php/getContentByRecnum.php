<?php
myRequireOnce ('sql.php');

function getContentByRecnum($p){
    $sql = "SELECT * from content
        WHERE recnum = '" .  $p['recnum'] . "'";
    $out = sqlArray($sql);
    return $out;
}