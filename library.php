<?php
	include('functions.php');
	if (isset($_SESSION["admin_rights"]) && ($_SESSION["admin_rights"])) {
		echo "admin";
	} else {
		session_start();
		set_user_session($_SESSION["username"], $_SESSION["id"]);
		create_header_library();
		include ('logout_bar.php');
		include ('header.php');
		include ('navigation.php');
		include ('main_section_library.php');
		include ('footer.php');
	} 
?>