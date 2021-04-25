<?php
include_once ("config.php");
include_once ("functions.php");
session_start();
if (!empty($_POST['post_id'])) {
    $post_id = $_POST['post_id'];
    add_like_on_post($mysqli, $post_id);
}
?>