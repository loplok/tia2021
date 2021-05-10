<?php
include_once ("config.php");
include_once ("functions.php");
session_start();
if (!empty($_POST['library_record_id'])) {
    $remove_record_id = $_POST['library_record_id'];
    remove_book_from_library($mysqli, $remove_record_id);
}