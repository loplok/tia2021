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

function get_user_id($mysqli) {
	$prepared_query = "SELECT id FROM users WHERE username = ?";
	if ($stmt = $mysqli->prepare($prepared_query)){
            $stmt->bind_param("s", $_SESSION["username"]);
            if($stmt->execute()){
                $stmt->bind_result($res);
        		$stmt->fetch();
                return $res;
            }
    }
}

function get_group_id_of_given_group($mysqli, $groupname) {
    $prepared_query = "SELECT groupname_id
                      FROM users_groups JOIN `groups` ON users_groups.groupname_id=`groups`.id
                      WHERE `groups`.groupname=?";
	if ($stmt = $mysqli->prepare($prepared_query)){
            $stmt->bind_param("s", $groupname);
            if($stmt->execute()){
                $stmt->bind_result($res);
        		$stmt->fetch();
                return $res;
            }
    }
}


/* INSERTS ONLY */
function insert_book_into_library($mysqli, $book_data) {
    $author = $book = $category = "";
    $book = $book_data[0];
    $author = $book_data[1];
    $category = $book_data[2];
    $prepared_query = "INSERT INTO library(id, username, bookid, category, author)
     VALUES(NULL, ?, ?, ?, ?)";
    if ($stmt = $mysqli->prepare($prepared_query)){
                $stmt->bind_param("ssss", $_SESSION["username"], $book, $category, $author);
                $stmt->execute();
    }
}

function add_group_to_my_groups($mysqli, $groupname) {
    $group_id = get_group_id_of_given_group($mysqli, $groupname);
    $user_id = get_user_id($mysqli);

    $prepared_query_count = "SELECT COUNT(groupname_id) as count
                             FROM users_groups
                             WHERE groupname_id=? GROUP BY groupname_id";
    if ($stmt = $mysqli->prepare($prepared_query_count)){
        $stmt->bind_param("s", $group_id);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
    }
    if ($count == 0) {
        create_group_as_admin($mysqli, $group_id, $user_id, $groupname);
    } else {
        join_group_as_member($mysqli, $group_id, $user_id);
    }
}


function create_group_as_admin($mysqli, $group_id, $user_id, $groupname) {
    $prepared_query = "INSERT INTO groups(id, is_public, groupname, users_count)
             VALUES(NULL, True, ?, 0)";
            if ($stmt = $mysqli->prepare($prepared_query)){
                        $stmt->bind_param("s", $groupname);
                        $stmt->execute();
            }

    $prepared_query = "INSERT INTO users_groups(id, groupname_id, username_id, role, joined)
         VALUES(NULL, ?, ?, 'admin', NOW())";
        if ($stmt2 = $mysqli->prepare($prepared_query)){
                    $stmt2->bind_param("ii", $group_id, $user_id);
                    $stmt2->execute();
        }
}

function join_group_as_member($mysqli, $group_id, $user_id) {
    $prepared_query = "INSERT INTO users_groups(id, groupname_id, username_id, role, joined)
         VALUES(NULL, ?, ?, 'user', NOW())";
        if ($stmt = $mysqli->prepare($prepared_query)){
                    $stmt->bind_param("ii", $group_id, $user_id);
                    $stmt->execute();
        }
}

function insert_post_into_db($mysqli, $post_data) {
    $group = $bookName = $thoughts = "";
    $group = $post_data[0];
    $bookName = $post_data[1];
    $thoughts = $post_data[2];
    $group_id = get_group_id_of_given_group($mysqli, $group);
    $user_id  = get_user_id($mysqli);

    $prepared_query = "INSERT INTO group_post(id, username_id, groups_id, text, posted_when, bookname)
         VALUES(NULL, ?, ?, ?, NOW(), ?)";
    if ($stmt = $mysqli->prepare($prepared_query)){
        $stmt->bind_param("siss", $user_id, $group_id, $thoughts, $bookName);
        $stmt->execute();
    }
}
/* INSERTS ONLY */


/* FILLING SELECTS ONLY */
function fill_books_in_create_post($mysqli) {
    $prepared_query = "SELECT DISTINCT bookid FROM library WHERE username=?";
        if ($stmt = $mysqli->prepare($prepared_query)){
                $stmt->bind_param("s", $_SESSION["username"]);
                if($stmt->execute()){
                $stmt->store_result();
                $amountOfRows = $stmt->num_rows;
                if($amountOfRows > 0) {
                    $stmt->bind_result($bookid);
                    while ($stmt->fetch()) {
                        echo "<option value='{$bookid}'>{$bookid}</option>";
                    }
                    } else {
                        echo "<option value='no_books' disabled>Add a book to your library first!</option>";
                        echo "</select>;";
                    }
                }
        }
}

function fill_categories_of_user_when_adding_books($mysqli) {
    $prepared_query = "SELECT DISTINCT category FROM library WHERE username=?";
        if ($stmt = $mysqli->prepare($prepared_query)){
                $stmt->bind_param("s", $_SESSION["username"]);
                if($stmt->execute()){
                $stmt->store_result();
                $amountOfRows = $stmt->num_rows;
                if($amountOfRows > 0) {
                    $stmt->bind_result($category_name);
                    while ($stmt->fetch()) {
                        echo "<option value='{$category_name}'>{$category_name}</option>";
                    }
                    echo "</select>";
                    } else {
                        echo "<option value='no_categories' disabled>Create a category first</option>";
                        echo "</select>;";
                    }
                }
        }
}

function fill_groups_of_user_when_creating_post($mysqli) {
    $prepared_query = "SELECT DISTINCT groupname
    FROM `groups` JOIN users_groups ON `groups`.id=users_groups.groupname_id WHERE username_id=?
    ";
    $id = get_user_id($mysqli);
        if ($stmt = $mysqli->prepare($prepared_query)){
                $stmt->bind_param("s", $id);
                if($stmt->execute()){
                $stmt->store_result();
                $amountOfRows = $stmt->num_rows;
                if($amountOfRows > 0) {
                    $stmt->bind_result($groupname);
                    while ($stmt->fetch()) {
                        echo "<option value='{$groupname}'>{$groupname}</option>";
                    }
                    echo "</select>";
                    } else {
                        echo "<option value='no_groups' disabled>Create a group first</option>";
                        echo "</select>;";
                    }
                }
        }
}

function fill_groups_for_user($mysqli) {
    $prepared_query = "SELECT groupname from users join users_groups on users.id=users_groups.username_id
    JOIN `groups` ON groupname_id=`groups`.id WHERE username=?";
    if ($stmt = $mysqli->prepare($prepared_query)){
            $stmt->bind_param("s", $_SESSION["username"]);
            if($stmt->execute()){
            $stmt->store_result();
            $amountOfRows = $stmt->num_rows;
            if($amountOfRows > 0) {
                $stmt->bind_result($groupname);
                echo "<option value='Found groups'>Select a group to show posts</option>";
                while ($stmt->fetch()) {
                    echo "<option value='{$groupname}'>  {$groupname} </option>";
                }
                echo "</select>";

                } else {
                    echo "<option value='no_groups' disabled>Join a group first </option>";
                    echo "</select>";
                }
            }
    }
}

function fill_profile_with_group_data($mysqli, $group) {
    $prepared_query = "select username, posted_when, text, bookname
                       from group_post JOIN users ON group_post.username_id=users.id
                       JOIN `groups` ON group_post.groups_id=`groups`.id
                       WHERE groupname=? ORDER BY posted_when DESC";
    if ($stmt = $mysqli->prepare($prepared_query)){
                $stmt->bind_param("s", $group);
                if($stmt->execute()){
                $stmt->store_result();
                $amountOfRows = $stmt->num_rows;
                if($amountOfRows > 0) {
                    $stmt->bind_result($username, $posted_when, $text, $bookname);
                    while ($stmt->fetch()) {
                        echo "<div class='user_post'>";
                        echo "<b>{$username}</b> at {$posted_when} was reading a book called  <b>{$bookname}</b>";
                        echo "<br>";
                        echo "and shared his thoughts: {$text}";
                        echo "</div>";
                    }
                } else {
                    echo "<div class='user_post'>";
                    echo "No posts found. Be first to create a post!";
                    echo "</div>";
                }
                }
    }
}

function fill_library_with_user_books($mysqli) {
    $prepared_query = "SELECT bookid, category, author FROM library WHERE username=? ORDER BY category ASC";
            if ($stmt = $mysqli->prepare($prepared_query)){
                    $stmt->bind_param("s", $_SESSION["username"]);
                    if($stmt->execute()){
                        $result = $stmt->get_result();
                        $amountOfRows = $result->num_rows;
                        if($amountOfRows > 0) {
                            echo '<div class="table-wrapper-scroll-y my-custom-scrollbar">
                                    <table class="table table-bordered table-hover">
                                   <thead class="thead-dark">
                                     <tr>
                                       <th scope="col" style="width: 33.33%">Category</th>
                                       <th scope="col" style="width: 33.33%">Author</th>
                                       <th scope="col" style="width: 33.33%">Book</th>
                                     </tr>
                                   </thead>
                                   <tbody>';

                            while ($row = $result->fetch_assoc()) {
                                echo '<tr>

                                          <td>' . $row["category"] . '</td>
                                          <td>' . $row["author"] . '</td>
                                          <td>' . $row["bookid"] . '</td>
                                        </tr>';
                            }
                            echo ' </tbody>
                                  </table>
                                  </div>';
                        } else {
                            echo "Your library is empty, add books first";
                        }
                    }
            }
}

/* FILLING SELECTS ONLY */

/* CREATING HEADERS ONLY */
function create_header() {
?>
		<!DOCTYPE html>
		<html>
		<head>
			<meta charset="utf-8">
            <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
			<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
			integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
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
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
            			<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
            			integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

                        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
                        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
            			<link href="style.css" rel="stylesheet">
			<link href="style.css" rel="stylesheet">
			<title>Welcome to Bookery</title>
		</head>
		<body>	
		<header>

<?php 
}

function create_header_create_post() {
?>
		<!DOCTYPE html>
		<html>
		<head>
			<meta charset="utf-8">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
            			<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
            			integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

                        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
                        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
            			<link href="style.css" rel="stylesheet">
			<link href="style.css" rel="stylesheet">
			<title>Welcome to Bookery</title>
		</head>
		<body>
		<header>

<?php
}

/* CREATING HEADERS ONLY */
?>