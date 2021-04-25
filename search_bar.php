<div class="search-bar">
<div class="input-group">
  <input type="search" class="form-control rounded" placeholder="Enter username, ISBN number or group" aria-label="Search"
    aria-describedby="search-addon" id="search_value"/>
  <button type="button" class="btn btn-centered-text btn-dark btn-outline-primary" id="searchContent">Search</button>
</div>
</div>

<div class="modal fade" id="modelWindow" role="dialog">
    <div class="modal-dialog modal-sm vertical-align-center">
      <div class="modal-content">
          <h4 class="modal-title">Heading</h4>
        <div class="modal-body" id="search_input">
            Body text here
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Close</button>
        </div>
      </div>
    </div>
</div>


<script>
$(document).ready(function(){

    $('#searchContent').click(function(){
    var searchVal = $("#search_value").val();
    $('#modelWindow').modal('show');
    $('#search_input').text(searchVal);

        /*

          console.log(searchVal);
          $.ajax({
            url:"add_group_to_my_groups.php",
            method: "POST",
            data: {searchVal: searchVal},
            success: function(data) {
                console.log(data);
            }
          });
        */
    });
});
</script>