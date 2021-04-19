<?php
include_once ("config.php");
include_once ("functions.php");
session_start();
if (!empty($_POST['arrayData'])) {
    $post_data = $_POST['arrayData'];
    insert_post_into_db($mysqli, $post_data);
}
?>