<div class="search-bar">
<div class="input-group">
  <input type="search" class="form-control rounded" placeholder="Enter username, ISBN number or group" aria-label="Search"
    aria-describedby="search-addon" id="search_value" required />
  <button type="button" class="btn  btn-dark btn-centered-text btn-outline-dark" id="searchContent">Search</button>
</div>
</div>

<div class="modal fade" id="modelWindow" role="dialog">
    <div class="modal-dialog modal-sm vertical-align-center">
      <div class="modal-content" id="modal_content_search">
          <h4 class="modal-title"> Group search results</h4>
        <div class="modal-body" id="search_input">

        </div>
      </div>
    </div>
</div>


<script>
$(document).ready(function(){

    $('#searchContent').click(function(){
    if(! ( $('#search_value').val().length === 0 ) ) {

        var searchVal = $("#search_value").val();
        $('#search_input').text(searchVal);
          console.log(searchVal);

          $.ajax({
            url:"search_result.php",
            method: "POST",
            data: {searchVal: searchVal},
            success: function(data) {
                document.getElementById("search_input").innerHTML = data;
                $('#modelWindow').modal('show');
            }
          });
    } else {
        alert("Search should not be empty");
    }
    });
    });
</script>