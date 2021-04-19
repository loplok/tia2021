<div class="search-bar">
<div class="input-group">
  <input type="search" class="form-control rounded" placeholder="Enter username, ISBN number or group" aria-label="Search"
    aria-describedby="search-addon" id="search_value"/>
  <button type="button" class="btn btn-centered-text btn-dark btn-outline-primary" id="searchContent">Search</button>
</div>
</div>


<script>
$(document).ready(function(){
    $('#searchContent').click(function(){
          var searchVal = $("#search_value").val();
          $.ajax({
            url:"add_group_to_my_groups.php",
            method: "POST",
            data: {searchVal: searchVal},
            success: function(data) {
                console.log(data);
            }
          });
    });
});
</script>