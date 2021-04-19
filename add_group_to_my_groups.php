<?php
include_once ("config.php");
include_once ("functions.php");
session_start();
if (!empty($_POST['searchVal'])) {
    $groupname = $_POST['searchVal'];
    add_group_to_my_groups($mysqli, $groupname);
}
?>