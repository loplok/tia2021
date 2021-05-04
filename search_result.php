<?php
include_once ("config.php");
include_once ("functions.php");
session_start();
if (!empty($_POST['searchVal'])) {
    $search_val = $_POST['searchVal'];
    create_search_result($mysqli, $search_val);
}
?>