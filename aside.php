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

          <span><b>BACK TO TOP</b></span>
          <img src="images/arrow_up.png" class="left-img" alt="UPL">
          <img src="images/arrow_up.png" class="right-img" alt="UPR">

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
                beforeSend: function(){
                // Show image container
                document.getElementById("main_group_posts").innerHTML = "<div class='lds-dual-ring'></div>";
                },
               success: function(data) {
                   document.getElementById("main_group_posts").innerHTML = data;
               }
   });
});

$(document).ready(function(){
$('.groups-page img,span').click(function(){
   $('html, body').animate({scrollTop : 0},800);
   return false;
});
});
</script>