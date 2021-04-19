<section>
    <div class="container">
    <div class="row">
        <div class="col text-center" id="add_book_div">
	        <button type="button" class="btn btn-outline-dark btn-lg" data-toggle="modal" data-target="#myModal">Add a book to your library</button>
        </div>
    </div>

    <div class="library_books">
        <?php echo fill_library_with_user_books($mysqli); ?>
    </div>



    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="adding_book_modal_text">Add a book to your library</h5>
              </div>
        <div class="modal-body">
            <form id="insert_book">
                      <div class="form-group">
                        <label for="book-name" class="col-form-label">Enter book name:</label>
                        <input type="text" class="form-control" id="book_name">
                      </div>
                      <div class="form-group">
                         <label for="message-text" class="col-form-label">Author: </label>
                         <input type="text" class="form-control" id="author_name">
                      </div>
                      <div class="form-group">
                         <label for="message-text" class="col-form-label">
                         Create category(leave blank if category is chosen):</label>
                         <input type="text" class="form-control" id="category_name">
                      </div>
                      <select class="form-select" aria-label="Default select example">
                      <?php fill_categories_of_user_when_adding_books($mysqli); ?>
             <input type="submit" name="insert" id="insert_book" value="Insert" class="btn btn-success"/>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
      </div>
      </div>
    </div>
</section>


<script>
    $('#insert_book').on('submit', function(event){
        event.preventDefault();
        if($('#book_name').val() == '') {
            alert('Name of book is required');
        } else if($('#author_name').val() == '') {
            alert('Author of book is required');
        }  else if($('#category_name').val() == '') {
            alert('Category of book is required');
        } else {
          var bookName = $('#book_name').val();
          var authorName = $('#author_name').val();
          var categoryName = $('#category_name').val();
          var arrayData = [bookName, authorName, categoryName];

          $.ajax({
            url:"insert_book.php",
            method: "POST",
            data: {book_data: arrayData},
            success: function(data) {
                console.log(data);
                $('#insert_book')[0].reset();
                $('#myModal').modal('hide');
                location.reload();
            }
          });
        }
    });
</script>
