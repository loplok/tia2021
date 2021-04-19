<?php
include_once ("config.php");
include_once ("functions.php");
session_start();
if (!empty($_POST['book_data'])) {
    $book_data = $_POST['book_data'];
    insert_book_into_library($mysqli, $book_data);
}
?>