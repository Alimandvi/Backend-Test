<!DOCTYPE html>
<html>
<head>
	<title>Books List</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
<div class="container">
	<div class="row text-center mt-5">
		<div class="col-lg-12">
			<h1 class="text-info">Manage Books and Authors</h1>
		</div>
	</div>
	<div class="row text-center mt-3">
		<div class="col-lg-6"></div>
		<div class="col-lg-6 d-flex justify-content-end">
			<button class="btn btn-primary addDetail">Add</button>
		</div>
	</div>
	<div class="row text-center mt-3 book_tbl">
		<div class="col-lg-12">
			<table class="table table-bordered">
				<thead>
					<th>Id</th>
					<th>Books</th>
					<th>Authors</th>
					<th>Edit</th>
					<th>Delete</th>
				</thead>
				<tbody class="books_tbody">
				@foreach($bookDetails as $books)
					<tr>
						<td>{{$books->id}}</td>
						<td><span class="book_span">{{$books->book}}</span></td>
						<td><span class="author_span">{{$books->author}}</span></td>
						<td><button class="btn btn-warning edit_details" data-book="{{json_encode($books)}}">Edit</button></td>
						<td><button class="btn btn-danger delete_details" data-id="{{$books->id}}">Delete</button></td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>

<!-- Add book modal start -->
<div class="modal fade" id="addDetails" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Book</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="add_book_form" method="POST">
        	@csrf
        	<label>Book:</label>
        	<input type="text" name="book" class="form-control book">
        	<span class="d-none text-danger book_span">This field is required.</span><br>
        	<label>Author:</label>
        	<input type="text" name="author" class="form-control author">
        	<span class="d-none text-danger author_span">This field is required.</span><br>
        	<button class="btn btn-primary addBook" data-formid="add_book_form">Add Book</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Add book modal end -->

<!-- Edit book modal start -->
<div class="modal fade" id="editDetails" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Book</h5>
        <button type="button" class="close edit_close_btn" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="edit_book_form" method="POST">
        	@csrf
        	<input type="hidden" name="id" class="id">
        	<input type="hidden" name="_method" value="PUT">
        	<label>Book:</label>
        	<input type="text" name="book" class="form-control edit_book">
        	<span class="d-none text-danger book_span">This field is required.</span><br>
        	<label>Author:</label>
        	<input type="text" name="author" class="form-control edit_author">
        	<span class="d-none text-danger author_span">This field is required.</span><br>
        	<button class="btn btn-primary editBook" data-formid="edit_book_form">Save Changes</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Edit book modal end -->
</body>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		$(document).on('click', '.addDetail', function(event) {
			$('#addDetails').modal('show');
		});

		$(document).on('click', '.addBook', function(event) {
			event.preventDefault();
			var book = $('.book').val();
			var author = $('.author').val();
			var error = 0;
			if(book == '' || book == null) {
				$('.book_span').removeClass('d-none');
				error++;
			} if(author == '' || author == null) {
				$('.author_span').removeClass('d-none');
				error++;
			} if (error != 0) {
				return false
			} else {
				var form = $(this).data('formid');
				var formdata = new FormData(document.getElementById(form));
				$.ajax({
					url: "{{route('addBook')}}",
					type: "POST",
					dataType: "json",
					data: formdata,
					contentType: false,
                    processData: false,
                    success: function(response) {
                    	if(response.status == "success") {
	                    	var new_tr = "<tr>"+
											"<td>"+response.id+"</td>"+
											"<td><span class='book_span'>"+book+"</span></td>"+
											"<td><span class='author_span'>"+author+"</span></td>"+
											"<td><button class='btn btn-warning edit_details' data-book='"+JSON.stringify(response.whole_data)+"'>Edit</button></td>"+
											"<td><button class='btn btn-danger delete_details' data-id='"+response.id+"'>Delete</button></td>"
										"</tr>";
							$('.books_tbody').prepend(new_tr);
							$('#addDetails').find('.author,.book').val('');
							$('#addDetails').modal('hide');
						}
                    }
				});
			}
		});


		$(document).on('click', '.edit_details', function(event) {
			$('#editDetails').modal('show');
			$(this).parents('tr').addClass('edit_book_details_class');
			var data = $(this).data('book');
			$('#editDetails').find('.edit_book').val(data.book);
			$('#editDetails').find('.edit_author').val(data.author);
			$('#editDetails').find('.id').val(data.id);
		});

		$(document).on('click', '.editBook', function(event) {
			event.preventDefault();
			var book = $('.edit_book').val();
			var author = $('.edit_author').val();
			var error = 0;
			if(book == '' || book == null) {
				$('.book_span').removeClass('d-none');
				error++;
			} if(author == '' || author == null) {
				$('.author_span').removeClass('d-none');
				error++;
			} if (error != 0) {
				return false
			} else {
				var form = $(this).data('formid');
				var formdata = new FormData(document.getElementById(form));

				$.ajax({
					url: "{{route('editBook')}}",
					type: "POST",
					data: formdata,
					contentType: false,
                    processData: false,
                    success: function(result) {
                    	if (result == 'success') {
	                    	$('.books_tbody').find('.edit_book_details_class').find('.book_span').text(book);
	                    	$('.books_tbody').find('.edit_book_details_class').find('.author_span').text(author);
	                    	$('#editDetails').modal('hide');
	                    	$('.books_tbody').find('tr').removeClass('edit_book_details_class');
                    	}
                    }
				});
			}
		});

		$(document).on('click', '.edit_close_btn', function(event) {
			event.preventDefault();
			$('.books_tbody').find('tr').removeClass('edit_book_details_class');
		});

		$(document).on('click', '.delete_details', function(event) {
			event.preventDefault();
			confirm('Are you sure?');
			var $this = $(this);
			if(true){
				var del_id = $(this).data('id');
				$.ajax({
					url: "{{route('deleteBook')}}",
					type: "POST",
					data: {id: del_id,_method:'DELETE',_token:"{{csrf_token()}}"},
					success: function(result) {
						if(result == 'success') {
							$this.parents('tr').remove();
						}
					}
				});
			}
		});
	});
</script>
</html>