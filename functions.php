<?php
require_once('config.php');

function is_admin($username, $password) {
	return ($username == "admin" && $password == "admin");
}

function set_admin_session() {
	$_SESSION["loggedin"] = true;
    $_SESSION["admin_rights"] = true;
    $_SESSION["username"] = "admin";
    $_SESSION["is_user"] = false;
    $_SESSION["can_ban"] = true;
}

function set_user_session($username, $id) {
	$_SESSION["loggedin"] = true;
    $_SESSION["id"] = $id;
    $_SESSION["username"] = $username;                            
    $_SESSION["is_user"] = true;
}

function echo_username() {
	if (isset($_SESSION["username"]))
	echo $_SESSION["username"];
}

function get_date_joined($mysqli) {
	$prepared_query = "SELECT DATE(created) FROM users WHERE username = ?";
	if ($stmt = $mysqli->prepare($prepared_query)){
            $stmt->bind_param("s", $_SESSION["username"]);
            if($stmt->execute()){
                $stmt->bind_result($res);
        		$stmt->fetch();
                return $res;
            }
    }               
}

function echo_member_for($mysqli) {
	$today = new DateTime("now");
	$day_joined = get_date_joined($mysqli);
	$target = date_create($day_joined);
	return date_diff($target, $today);
}

function get_user_email($mysqli) {
	$prepared_query = "SELECT email FROM users WHERE username = ?";
	if ($stmt = $mysqli->prepare($prepared_query)){
            $stmt->bind_param("s", $_SESSION["username"]);
            if($stmt->execute()){
                $stmt->bind_result($res);
        		$stmt->fetch();
                return $res;
            }
    }               
}

function create_header() {
?>
		<!DOCTYPE html>
		<html>
		<head>
			<meta charset="utf-8">
			<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
			<link href="style.css" rel="stylesheet">
			<title>Welcome to Bookery</title>
		</head>
		<body>	
		<header>

<?php 
}

function create_header_library() {
?>
		<!DOCTYPE html>
		<html>
		<head>
			<meta charset="utf-8">
			<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
			<link href="style.css" rel="stylesheet">
			<title>Welcome to Bookery</title>
		</head>
		<body>	
		<header>

<?php 
}


?>