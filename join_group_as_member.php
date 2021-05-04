<?php
include_once ("config.php");
include_once ("functions.php");
session_start();
if (!empty($_POST['group_name'])) {
    $groupname = $_POST['group_name'];
    join_group_as_member($mysqli, $groupname);
}
?>