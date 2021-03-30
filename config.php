<?php
define('DB_SERVER', '${{secrets.DB_SERVER}}');
define('DB_USERNAME', '${{secrets.DB_USERNAME}}');
define('DB_PASSWORD', '${{secrets.DB_PASSWORD}}');
define('DB_NAME', '${{secrets.DB_NAME}}');
 
$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if($mysqli === false){
    die("ERROR: Could not connect. " . $mysqli->connect_error);
}
?>
