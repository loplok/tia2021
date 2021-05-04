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
	$prepared_query = "SELECT DATE (created) FROM users WHERE username = ?";
	if ($stmt = $mysqli->prepare($prepared_query)){
            $stmt->bind_param("s", $_SESSION["username"]);
            if($stmt->execute()){
                $stmt->bind_result($res);
        		$stmt->fetch();
                return $res;
            }
    }               
}

function get_username_from_id($mysqli, $id) {
    $prepared_query = "SELECT username FROM users WHERE id = ?";
	if ($stmt = $mysqli->prepare($prepared_query)){
            $stmt->bind_param("u", $id);
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
    $prepared_query = "SELECT DISTINCT (groupname_id)
                      FROM users_groups JOIN `groups` ON users_groups.groupname_id=`groups`.id
                      WHERE `groups`.groupname=?";
	if ($stmt = $mysqli->prepare($prepared_query)){
            $stmt->bind_param("s", $groupname);
            if($stmt->execute()){
                $stmt->bind_result($res);
        		$stmt->fetch();
        		if ( ! $res) {
        		    return "empty";
        		}
                return $res;
            }
    }
}

function is_post_liked_by_user($mysqli, $group_post_id) {
    $user_id = get_user_id($mysqli);
    $prepared_query = "SELECT COUNT (DISTINCT liked_by_username_id) as likes_counter
                       FROM likes
                       WHERE post_liked_id=? AND liked_by_username_id=?";
    if ($stmt = $mysqli->prepare($prepared_query)){
        $stmt->bind_param("ii", $group_post_id, $user_id);
        $stmt->execute();
        $stmt->bind_result($res);
        $stmt->fetch();
        if ($res == 1) {
            return True;
        } else {
            return False;
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


function remove_like_on_post($mysqli, $post_id) {
    $user_id = get_user_id($mysqli);
    $prepared_query_count = "DELETE FROM likes WHERE post_liked_id=? AND liked_by_username_id=?";
    if ($stmt = $mysqli->prepare($prepared_query_count)){
        $stmt->bind_param("ii", $post_id, $user_id);
        $stmt->execute();
        $stmt->fetch();
    }
}


function add_like_on_post($mysqli, $post_id) {
    if (is_post_liked_by_user($mysqli, $post_id)) {
        remove_like_on_post($mysqli, $post_id);
    } else {
    $user_id = get_user_id($mysqli);
    $prepared_query_count = "INSERT INTO likes(id, post_liked_id, liked_by_username_id)
    VALUES (NULL, ?, ?)";
    if ($stmt = $mysqli->prepare($prepared_query_count)){
        $stmt->bind_param("ii", $post_id, $user_id);
        $stmt->execute();
        $stmt->fetch();
    }
    }
}

function create_group_as_admin($mysqli, $groupname) {
    $prepared_query = "INSERT INTO `groups` (id, is_public, groupname, users_count)
             VALUES(NULL, True, ?, 0)";
    if ($stmt = $mysqli->prepare($prepared_query)){
                $stmt->bind_param("s", $groupname);
                $stmt->execute();
    }

    $prepared_query = "INSERT INTO users_groups(id, groupname_id, username_id, role, joined)
         VALUES(NULL, ?, ?, 'admin', NOW())";


    if ($stmt2 = $mysqli->prepare($prepared_query)) {
                $stmt2->bind_param("ii", get_group_id_of_given_group($mysqli, $groupname), get_user_id($mysqli));
                $stmt2->execute();
    }
}


function join_group_as_member($mysqli, $groupname) {
    $prepared_query = "INSERT INTO users_groups(id, groupname_id, username_id, role, joined)
         VALUES(NULL, ?, ?, 'user', NOW())";
        if ($stmt = $mysqli->prepare($prepared_query)){
                    $stmt->bind_param("ii", get_group_id_of_given_group($mysqli, $groupname), get_user_id($mysqli));
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

function fill_comments_of_post($mysqli, $group_post_id) {
    $prepared_query = "SELECT comments_username_id as user_id, text FROM comments WHERE group_post_id=?";
     if ($stmt = $mysqli->prepare($prepared_query)) {

        $stmt->bind_param("i", $group_post_id);

        if ($stmt->execute()) {
            $stmt->store_result();
            $amountOfRows = $stmt->num_rows;

            if($amountOfRows > 0) {
                $stmt->bind_result($user_id, $text);
                $commenter_username = get_username_from_id($mysqli,$user_id);

                while ($stmt->fetch()) {

                }

            } else {
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
    $prepared_query = "select username, posted_when, text, bookname, group_post.id
                       from group_post JOIN users ON group_post.username_id=users.id
                       JOIN `groups` ON group_post.groups_id=`groups`.id
                       WHERE groupname=? ORDER BY posted_when DESC";
    if ($stmt = $mysqli->prepare($prepared_query)){
                $stmt->bind_param("s", $group);
                if($stmt->execute()){
                $stmt->store_result();
                $amountOfRows = $stmt->num_rows;
                if($amountOfRows > 0) {
                    $stmt->bind_result($username, $posted_when, $text, $bookname, $group_post_id);
                    while ($stmt->fetch()) {
                        echo "<div class='user_post'>";
                        echo create_likebutton_based_on_likes_of_users($mysqli, $group_post_id);
                        echo "<b>{$username}</b> at {$posted_when} was reading a book called  <b>{$bookname}</b>";
                        echo "<br>";
                        echo "and shared his thoughts: {$text}";
                        echo fill_comments_of_post($mysqli, $group_post_id);
                        echo create_comment_form();
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


function create_likebutton_based_on_likes_of_users($mysqli, $group_post_id) {
    $prepared_query = "SELECT COUNT (DISTINCT liked_by_username_id) as likes_counter
                       FROM likes
                       WHERE post_liked_id=?";
        if ($stmt = $mysqli->prepare($prepared_query)){
                    $stmt->bind_param("i", $group_post_id);

                    if($stmt->execute()){
                        $stmt->store_result();
                        $amountOfRows = $stmt->num_rows;

                        if($amountOfRows > 0) {

                            $stmt->bind_result($likes_counter);
                            if (is_post_liked_by_user($mysqli, $group_post_id)) {
                                while ($stmt->fetch()) {
                                    if ($likes_counter > 1) {
                                        $other_likes = $likes_counter - 1;
                                        echo "<button type='button' class='btn btn-outline-dark btn-sm active'
                                            id='{$group_post_id}'
                                            onClick=like_post(this)>Liked by you and {$other_likes} other users</button>";
                                    } else {
                                        echo "<button type='button' class='btn btn-outline-dark btn-sm active'
                                        id='{$group_post_id}'
                                        onClick=like_post(this)>Liked by you</button>";
                                    }
                                }
                            } else {
                                while ($stmt->fetch()) {
                                    if ($likes_counter >= 1) {
                                        echo "<button type='button' class='btn btn-outline-dark btn-sm' id='{$group_post_id}'
                                              onClick=like_post(this)>Liked by {$other_likes} other users</button>";
                                    } else {
                                        echo "<button type='button' class='btn btn-outline-dark btn-sm'
                                            id='{$group_post_id}'
                                            onClick=like_post(this)>Be first to like this post</button>";
                                    }
                                }
                            }
                        }
                    }
        }
}

function create_comment_form() {
    echo "
        <form>
            <div class='form-group'>
             <textarea class='form-control' id='exampleFormControlTextarea' rows='1'
                placeholder='Share a comment..'></textarea>
            </div>
        </form>
    ";
}

/* ONLY SEARCH RESULTS FUNCTIONS */
function get_existence_of_group($mysqli, $search_val) {
    $prepared_query_count = "SELECT COUNT (*)
                                 FROM `groups`
                                 WHERE groupname=?";

        if ($stmt = $mysqli->prepare($prepared_query_count)){
            $stmt->bind_param("s", $search_val);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();

            if ($count == "0") {
                return False;
            } else {
                return True;
            }

        }
}

function is_member_of_group($mysqli, $username_id, $groupname_id) {
    $prepared_query_count = "SELECT COUNT (*)
                                     FROM `users_groups`
                                     WHERE groupname_id=? AND username_id=?";

            if ($stmt = $mysqli->prepare($prepared_query_count)){
                $stmt->bind_param("ii", $groupname_id, $username_id );
                $stmt->execute();
                $stmt->bind_result($count);
                $stmt->fetch();

                if ($count == "0") {
                    return False;
                } else {
                    return True;
                }

            }
}

function create_search_result($mysqli, $search_val) {
    echo create_group_search_result($mysqli, $search_val);
    echo create_user_search_result($mysqli, $search_val);
}


function create_group_search_result($mysqli, $search_val) {

    $does_searched_group_exist = get_existence_of_group($mysqli, $search_val);
    $username_id = get_user_id($mysqli);
    $group_id = get_group_id_of_given_group($mysqli, $search_val);
    $is_member_of_group = is_member_of_group($mysqli, $username_id, $group_id);

    if ($does_searched_group_exist == False) {
        create_option_create_group_in_search($mysqli, $search_val);
    } else if ($does_searched_group_exist == True && $is_member_of_group == True) {
        echo "You are a member in that group!";
    } else if ($does_searched_group_exist == True && $is_member_of_group == False) {
        create_option_join_group_as_member($mysqli, $search_val);
    }
}

function create_user_search_result($mysqli, $search_val) {

}

function create_option_create_group_in_search($mysqli, $group_name) {
    echo "<span>There are no groups created yet, would you like to create one ?</span>";
    echo "<p><button type='button' data-dismiss='modal' class='btn btn-danger' id={$group_name} onClick=create_as_admin(this)
        >Create group as an admin</button></p>";
}

function create_option_join_group_as_member($mysqli, $group_name) {
    echo "<span>We found your group! Would you like to join ?</span>";
    echo "<p><button type='button' data-dismiss='modal' class='btn btn-danger' id={$group_name} onClick=join_as_member(this)
    >Join the group as a member</button></p>";
    echo "<br>";
}


/* ONLY SEARCH RESULTS FUNCTIONS */


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

<script>
    function like_post(button) {
        var post_id = (button.id);
        console.log(post_id);
        $.ajax({
                        url:"add_to_likes.php",
                        method: "POST",
                        data: {post_id: post_id},
                        success: function(data) {
                            var selected_group = $("#groups_selector").find(":selected").val();
                            localStorage.setItem("selected_group", selected_group);
                            console.log(selected_group);
                            if (button.classList.contains('active')) {
                                button.classList.remove('active');
                            } else {
                                button.classList.remove('add');

                            }
                            location.reload();

                        }
        });
    };

    function create_as_admin(button) {
        var group_name = (button.id);
        console.log(group_name);
                $.ajax({
                                url:"create_group_as_admin.php",
                                method: "POST",
                                data: {group_name: group_name},
                                success: function(data) {

                                }
                });
    }

    function join_as_member(button) {
       var group_name = (button.id);
       console.log(group_name);
               $.ajax({
                               url:"join_group_as_member.php",
                               method: "POST",
                               data: {group_name: group_name},
                               success: function(data) {

                               }
               });
   }

</script>