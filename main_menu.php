<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login_menu.php");
    exit;
} else {
    // user is logged in, can load all the parts of main page
    include('index.php');
}
?>

