<?php
include_once ("config.php");
include_once ("functions.php");
session_start();
if (!empty($_POST['group'])) {
    $group = $_POST['group'];
    fill_profile_with_group_data($mysqli, $group);
}
?>