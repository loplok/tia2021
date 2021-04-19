<?php
if (isset($_SESSION["is_user"]) && $_SESSION["is_user"]) { ?>

<nav>
    <div class="navbar">
  		<a href="index.php">My Profile</a>
  		<a href="feed.php">News feed</a>
  		<a href="create_post.php">Create a post</a>
  	    <a href="library.php">Library</a>
  	</div>
</nav>	
<?php } ?>