<?php
	include('functions.php');
	if (isset($_SESSION["admin_rights"]) && ($_SESSION["admin_rights"])) {
		echo "admin";
	} else {
		session_start();
		create_header_create_post();
		include ('logout_bar.php');
		include ('header.php');
		include ('navigation.php');
		include ('main_section_create_post.php');
		include ('footer.php');
	}
?>