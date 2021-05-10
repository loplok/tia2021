
<section class="create-post">
    <div class="container">
    <form id="create_post">
        <div class="form-group">
            <b><label for="create_post_group_select">Choose a group to post in:</label> </b>
            <select class="form-control" id="create_post_group_select" required>
             <?php fill_groups_of_user_when_creating_post($mysqli); ?>
            </select>
        </div>
        <div class="form-group">
                   <b><label for="create_post_book">You were reading: </label></b>
                    <select class="form-control" id="create_post_book" required>
                      <?php fill_books_in_create_post($mysqli); ?>
                    </select>
        </div>
        <div class="form-group">
            <b><label for="create_post_textarea">Enter text content</label></b>
            <textarea class="form-control" id="create_post_textarea" rows="5"
            placeholder="Share your thoughts on the book.." required></textarea>
        </div>
        <div class="submit_post_btn">
          <input class="btn btn-outline-dark" type="submit" value="Create post">
        </div>
    </form>
    </div>

</section>


<script>
    $('#create_post').on('submit', function(event){
          event.preventDefault();
          var group = $('#create_post_group_select').val();
          var bookName = $('#create_post_book').val();
          var thoughts = $('#create_post_textarea').val();
          var arrayData = [group, bookName, thoughts];
          console.log(arrayData);
          $.ajax({
            url:"insert_post.php",
            method: "POST",
            data: {arrayData: arrayData},
            success: function(data) {
                $('#create_post')[0].reset();
            }

          });
    });
</script>