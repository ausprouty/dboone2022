<?php
define("HOST", "localhost");
define("USER", "generations2020");
define("DEVELOPER", 11);
define("PASS", "ULuMOg13MZ01o0Sz");
define("DATABASE_CONTENT", 'new_generations');
define("DATABASE_BIBLE", 'new_dbm_common');
define("DATABASE_PORT", 9306);
 $conn = new mysqli(HOST, USER, PASS, DATABASE_BIBLE, DATABASE_PORT);
if ($conn->connect_error) {
    die("Connection has failed: " . $conn->connect_error);
}
else{
    die("Connection SUCCEEDED");
}