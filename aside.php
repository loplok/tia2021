<aside>
	<div class="profile-page">
		<div class="profile-pic">
			<img src="images/profile_pic.png" alt="Profile Picture">
		</div>
		<div class="profile-page-userinfo">
			<b><?php echo_username(); ?>
			<p>
			<?php echo "Member for " . echo_member_for($mysqli)->format(' %d days, %m month, %y years'); ?>

			<?php echo get_user_email($mysqli); ?>	
			</b>
		</div>
	</div>
	<div class="groups-page">
		<select class="form-select" id="groups_selector" aria-label="Default select example">
          <?php fill_groups_for_user($mysqli); ?>
	</div>
</aside>

<script>
$('#groups_selector').on('change', function() {
   var group = ( $(this).find(":selected").val() );
   console.log(group);
   $.ajax({
               url:"update_profile_groups_posts.php",
               method: "POST",
               data: {group: group},
               success: function(data) {
                   document.getElementById("main_group_posts").innerHTML = data;
               }
             });
});
</script>