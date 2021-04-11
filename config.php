<?php
define('DB_SERVER', 'db-mysql-fra1-36602-do-user-8828141-0.b.db.ondigitalocean.com:25060');
define('DB_USERNAME', 'doadmin');
define('DB_PASSWORD', 'wwto58zqjtwnk5m0');
define('DB_NAME', 'defaultdb');
 
$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if($mysqli === false){
    die("ERROR: Could not connect. " . $mysqli->connect_error);
}
?>