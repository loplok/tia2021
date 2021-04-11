<?php
	include('functions.php');
	if (isset($_SESSION["admin_rights"]) && ($_SESSION["admin_rights"])) {
		echo "admin";
	} else {
		session_start();
		create_header();
		include ('logout_bar.php');
		include ('header.php');
		include ('navigation.php');
		include('search_bar.php');
		include ('aside.php');
		include ('main_section.php');
		include ('footer.php');
	} 
?>

