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
		TODO vyber zo skupin, nejaky rozumny dropdown/scrollbar
	</div>
</aside>