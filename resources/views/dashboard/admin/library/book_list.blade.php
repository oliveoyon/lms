@extends('dashboard.admin.layouts.admin-layout-with-cdn')
@section('title', 'Book List')
@push('admincss')
    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.2.0/dist/sweetalert2.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endpush

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ __('language.book_list') }}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ __('language.home') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('language.book_list') }}</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        
        
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">

                        <div class="card card-outline">
                            <div class="card-header bg-navy">
                                <h3 class="card-title">
                                    <i class="fas fa-book mr-1"></i>
                                    {{ __('language.book_list') }}
                                </h3>
                                <div class="card-tools">
                                    <ul class="nav nav-pills ml-auto">
                                        <li class="nav-item">
                                            <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#addBookModal">
                                                <i class="fas fa-plus-square mr-1"></i>
                                                {{ __('language.add_book') }}
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body table-responsive">
                                <table class="table table-bordered table-striped table-hover table-sm" id="datas-table">
                                    <thead style="border-top: 1px solid #b4b4b4">
                                        <th style="width: 15px">#</th>
                                        <th>{{ __('language.book_title') }}</th>
                                        <th>{{ __('language.author') }}</th>
                                        <th>{{ __('language.isbn') }}</th>
                                        <th>{{ __('language.edition') }}</th>
                                        <th>{{ __('language.publisher') }}</th>
                                        <th>{{ __('language.shelf') }}</th>
                                        <th>{{ __('language.position') }}</th>
                                        <th>{{ __('language.purchase_date') }}</th>
                                        <th>{{ __('language.cost') }}</th>
                                        <th>{{ __('language.no_of_copy') }}</th>
                                        <th>{{ __('language.availability') }}</th>
                                        <th>{{ __('language.language') }}</th>
                                        <th>{{ __('language.status') }}</th>
                                        <th style="width: 40px">{{ __('language.action') }}
                                            <button class="btn btn-sm btn-danger d-none" id="deleteAllBtn">{{ __('language.delete_all') }}</button>
                                        </th>
                                    </thead>
                                    <tbody>
                                        @foreach ($books as $book)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="font-weight-bold">{{ $book->book_title }}</td>
                                                <td>{{ $book->author }}</td>
                                                <td>{{ $book->isbn }}</td>
                                                <td>{{ $book->edition }}</td>
                                                <td>{{ $book->publisher }}</td>
                                                <td>{{ $book->shelf }}</td>
                                                <td>{{ $book->position }}</td>
                                                <td>{{ $book->book_purchase_date }}</td>
                                                <td>{{ $book->cost }}</td>
                                                <td>{{ $book->no_of_copy }}</td>
                                                <td>{{ $book->availability }}</td>
                                                <td>{{ $book->language }}</td>
                                                <td class="{{ $book->book_status == 1 ? 'text-success' : 'text-danger' }} font-weight-bold">
                                                    {{ $book->book_status == 1 ? __('language.active') : __('language.inactive') }}
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-warning btn-xs" data-id="{{ $book->id }}" id="editBookBtn">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-danger btn-xs" data-id="{{ $book->id }}" id="deleteBookBtn">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        
                    </div>

                </div>

                <div class="modal fade" id="addBookModal" tabindex="-1" aria-labelledby="addBookLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-xl">
                        <div class="modal-content">
                            <div class="modal-header bg-success">
                                <h5 class="modal-title" id="addBookLabel">{{ __('language.add_book') }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('admin.addBook') }}" method="post" autocomplete="off" id="add-book-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="book_cat_id">{{ __('language.book_category') }}</label>
                                                <select class="form-control form-control-sm" name="book_cat_id" id="add_book_cat_id">
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}">{{ $category->book_category_name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger error-text book_cat_id_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="book_title">{{ __('language.book_title') }}</label>
                                                <input type="text" class="form-control form-control-sm" name="book_title" id="book_title" placeholder="{{ __('language.book_title') }}">
                                                <span class="text-danger error-text book_title_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="author">{{ __('language.author') }}</label>
                                                <input type="text" class="form-control form-control-sm" name="author" id="author" placeholder="{{ __('language.author') }}">
                                                <span class="text-danger error-text author_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="isbn">{{ __('language.isbn') }}</label>
                                                <input type="text" class="form-control form-control-sm" name="isbn" id="isbn" placeholder="{{ __('language.isbn') }}">
                                                <span class="text-danger error-text isbn_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="edition">{{ __('language.edition') }}</label>
                                                <input type="text" class="form-control form-control-sm" name="edition" id="edition" placeholder="{{ __('language.edition') }}">
                                                <span class="text-danger error-text edition_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="publisher">{{ __('language.publisher') }}</label>
                                                <input type="text" class="form-control form-control-sm" name="publisher" id="publisher" placeholder="{{ __('language.publisher') }}">
                                                <span class="text-danger error-text publisher_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="shelf">{{ __('language.shelf') }}</label>
                                                <input type="text" class="form-control form-control-sm" name="shelf" id="shelf" placeholder="{{ __('language.shelf') }}">
                                                <span class="text-danger error-text shelf_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="position">{{ __('language.position') }}</label>
                                                <input type="text" class="form-control form-control-sm" name="position" id="position" placeholder="{{ __('language.position') }}">
                                                <span class="text-danger error-text position_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="book_purchase_date">{{ __('language.book_purchase_date') }}</label>
                                                <input type="datetime-local" class="form-control form-control-sm" name="book_purchase_date" id="book_purchase_date">
                                                <span class="text-danger error-text book_purchase_date_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="cost">{{ __('language.cost') }}</label>
                                                <input type="text" class="form-control form-control-sm" name="cost" id="cost" placeholder="{{ __('language.cost') }}">
                                                <span class="text-danger error-text cost_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="no_of_copy">{{ __('language.no_of_copy') }}</label>
                                                <input type="text" class="form-control form-control-sm" name="no_of_copy" id="no_of_copy" placeholder="{{ __('language.no_of_copy') }}">
                                                <span class="text-danger error-text no_of_copy_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="availability">{{ __('language.availability') }}</label>
                                                <select class="form-control form-control-sm" name="availability" id="availability">
                                                    <option value="available">{{ __('language.available') }}</option>
                                                    <option value="unavailable">{{ __('language.unavailable') }}</option>
                                                </select>
                                                <span class="text-danger error-text availability_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="language">{{ __('language.language') }}</label>
                                                <input type="text" class="form-control form-control-sm" name="language" id="language" placeholder="{{ __('language.language') }}">
                                                <span class="text-danger error-text language_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="book_status">{{ __('language.status') }}</label>
                                                <select class="form-control form-control-sm" name="book_status" id="book_status">
                                                    <option value="1">{{ __('language.active') }}</option>
                                                    <option value="0">{{ __('language.inactive') }}</option>
                                                </select>
                                                <span class="text-danger error-text book_status_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-block btn-success">{{ __('language.save') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade editBookModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-warning">
                                <h5 class="modal-title" id="editBookLabel">{{ __('language.book') }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('admin.updateBookDetails') }}" method="post" autocomplete="off" id="edit-book-form">
                                    @csrf
                                    <input type="hidden" name="book_id">
                
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="book_cat_id">{{ __('language.book_category') }}</label>
                                                <select class="form-control form-control-sm" name="book_cat_id" id="book_cat_id">
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}">{{ $category->book_category_name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger error-text book_cat_id_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="book_title">{{ __('language.book_title') }}</label>
                                                <input type="text" class="form-control form-control-sm" name="book_title"
                                                    id="book_title" placeholder="{{ __('language.book_title') }}">
                                                <span class="text-danger error-text book_title_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="author">{{ __('language.author') }}</label>
                                                <input type="text" class="form-control form-control-sm" name="author" id="author"
                                                    placeholder="{{ __('language.author') }}">
                                                <span class="text-danger error-text author_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="isbn">{{ __('language.isbn') }}</label>
                                                <input type="text" class="form-control form-control-sm" name="isbn" id="isbn"
                                                    placeholder="{{ __('language.isbn') }}">
                                                <span class="text-danger error-text isbn_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="edition">{{ __('language.edition') }}</label>
                                                <input type="text" class="form-control form-control-sm" name="edition" id="edition"
                                                    placeholder="{{ __('language.edition') }}">
                                                <span class="text-danger error-text edition_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="publisher">{{ __('language.publisher') }}</label>
                                                <input type="text" class="form-control form-control-sm" name="publisher" id="publisher"
                                                    placeholder="{{ __('language.publisher') }}">
                                                <span class="text-danger error-text publisher_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="shelf">{{ __('language.shelf') }}</label>
                                                <input type="text" class="form-control form-control-sm" name="shelf" id="shelf"
                                                    placeholder="{{ __('language.shelf') }}">
                                                <span class="text-danger error-text shelf_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="position">{{ __('language.position') }}</label>
                                                <input type="text" class="form-control form-control-sm" name="position" id="position"
                                                    placeholder="{{ __('language.position') }}">
                                                <span class="text-danger error-text position_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="book_purchase_date">{{ __('language.book_purchase_date') }}</label>
                                                <input type="datetime-local" class="form-control form-control-sm" name="book_purchase_date"
                                                    id="book_purchase_date">
                                                <span class="text-danger error-text book_purchase_date_error"></span>
                                            </div>
                                        </div>
                                    </div>
                
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="cost">{{ __('language.cost') }}</label>
                                                <input type="text" class="form-control form-control-sm" name="cost" id="cost"
                                                    placeholder="{{ __('language.cost') }}">
                                                <span class="text-danger error-text cost_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="no_of_copy">{{ __('language.no_of_copy') }}</label>
                                                <input type="text" class="form-control form-control-sm" name="no_of_copy" id="no_of_copy"
                                                    placeholder="{{ __('language.no_of_copy') }}">
                                                <span class="text-danger error-text no_of_copy_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="availability">{{ __('language.availability') }}</label>
                                                <select class="form-control form-control-sm" name="availability" id="availability">
                                                    <option value="available">{{ __('language.available') }}</option>
                                                    <option value="unavailable">{{ __('language.unavailable') }}</option>
                                                </select>
                                                <span class="text-danger error-text availability_error"></span>
                                            </div>
                                        </div>
                                    </div>
                
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="language">{{ __('language.language') }}</label>
                                                <input type="text" class="form-control form-control-sm" name="language" id="language"
                                                    placeholder="{{ __('language.language') }}">
                                                <span class="text-danger error-text language_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="book_status">{{ __('language.status') }}</label>
                                                <select class="form-control form-control-sm" name="book_status" id="book_status">
                                                    <option value="1">{{ __('language.active') }}</option>
                                                    <option value="0">{{ __('language.inactive') }}</option>
                                                </select>
                                                <span class="text-danger error-text book_status_error"></span>
                                            </div>
                                        </div>
                                    </div>
                
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-block btn-warning">{{ __('language.update') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                
                



            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->


@endsection


@push('adminjs')
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.2.0/dist/sweetalert2.min.js"></script>

    <!-- Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        new DataTable('#data-table');
    </script>



    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        $(document).ready(function() {
            // Image preview
            $("#upload").change(function() {
                readURL(this);
            });

            // Add Book RECORD
$('#add-book-form').on('submit', function(e) {
    e.preventDefault();
    var form = this;

    // Disable the submit button to prevent double-clicking
    $(form).find(':submit').prop('disabled', true);

    // Show the loader overlay
    $('#loader-overlay').show();

    $.ajax({
        url: $(form).attr('action'),
        method: $(form).attr('method'),
        data: new FormData(form),
        processData: false,
        dataType: 'json',
        contentType: false,
        beforeSend: function() {
            $(form).find('span.error-text').text('');
        },
        success: function(data) {
            if (data.code == 0) {
                $.each(data.error, function(prefix, val) {
                    $(form).find('span.' + prefix + '_error').text(val[0]);
                });
            } else {
                var redirectUrl = data.redirect;
                $('#addBookModal').modal('hide');
                $('#addBookModal').find('form')[0].reset();
                toastr.success(data.msg);

                setTimeout(function() {
                    window.location.href = redirectUrl;
                }, 1000); // Adjust the delay as needed (in milliseconds)
            }
        },
        complete: function() {
            // Enable the submit button and hide the loader overlay
            $(form).find(':submit').prop('disabled', false);
            $('#loader-overlay').hide();
        }
    });
});

// Edit Book Modal
// Edit Book Modal
$(document).on('click', '#editBookBtn', function() {
    var book_id = $(this).data('id');

    $('.editBookModal').find('form')[0].reset();
    $('.editBookModal').find('span.error-text').text('');
    $.post("{{ route('admin.getBookDetails') }}", {
        book_id: book_id
    }, function(data) {
        var editBookModal = $('.editBookModal');
        
        // Set values in the form fields
        editBookModal.find('input[name="book_id"]').val(data.details.id);
        editBookModal.find('select[name="book_cat_id"]').val(data.details.book_cat_id);
        editBookModal.find('input[name="book_title"]').val(data.details.book_title);
        editBookModal.find('input[name="author"]').val(data.details.author);
        editBookModal.find('input[name="isbn"]').val(data.details.isbn);
        editBookModal.find('input[name="edition"]').val(data.details.edition);
        editBookModal.find('input[name="publisher"]').val(data.details.publisher);
        editBookModal.find('input[name="shelf"]').val(data.details.shelf);
        editBookModal.find('input[name="position"]').val(data.details.position);
        editBookModal.find('input[name="book_purchase_date"]').val(data.details.book_purchase_date); // Format this as needed
        editBookModal.find('input[name="cost"]').val(data.details.cost);
        editBookModal.find('input[name="no_of_copy"]').val(data.details.no_of_copy);
        editBookModal.find('select[name="availability"]').val(data.details.availability);
        editBookModal.find('input[name="language"]').val(data.details.language);
        editBookModal.find('select[name="book_status"]').val(data.details.book_status);
        // Repeat the pattern for other fields

        // Show the edit book modal
        editBookModal.modal('show');
    }, 'json');
});



// Update Book RECORD
$('#edit-book-form').on('submit', function(e) {
    e.preventDefault();
    var form = this;

    // Disable the submit button to prevent double-clicking
    $(form).find(':submit').prop('disabled', true);

    // Show the loader overlay
    $('#loader-overlay').show();

    $.ajax({
        url: $(form).attr('action'),
        method: $(form).attr('method'),
        data: new FormData(form),
        processData: false,
        dataType: 'json',
        contentType: false,
        beforeSend: function() {
            $(form).find('span.error-text').text('');
        },
        success: function(data) {
            if (data.code == 0) {
                $.each(data.error, function(prefix, val) {
                    $(form).find('span.' + prefix + '_error').text(val[0]);
                });
            } else {
                var redirectUrl = data.redirect;
                $('.editBookModal').modal('hide');
                $('.editBookModal').find('form')[0].reset();
                toastr.success(data.msg);

                setTimeout(function() {
                    window.location.href = redirectUrl;
                }, 1000); // Adjust the delay as needed (in milliseconds)
            }
        },
        complete: function() {
            // Enable the submit button and hide the loader overlay
            $(form).find(':submit').prop('disabled', false);
            $('#loader-overlay').hide();
        }
    });
});

// DELETE Book RECORD
$(document).on('click', '#deleteBookBtn', function() {
    var book_id = $(this).data('id');
    var url = '{{ route('admin.deleteBook') }}';

    swal.fire({
        title: 'Are you sure?',
        html: 'You want to <b>delete</b> this book',
        showCancelButton: true,
        showCloseButton: true,
        cancelButtonText: 'Cancel',
        confirmButtonText: 'Yes, Delete',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',

        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            // Show the loader overlay
            $('#loader-overlay').show();

            $.post(url, {
                book_id: book_id
            }, function(data) {
                if (data.code == 1) {
                    var redirectUrl = data.redirect;
                    toastr.success(data.msg);

                    setTimeout(function() {
                        window.location.href = redirectUrl;
                    }, 1000); // Adjust the delay as needed (in milliseconds)

                } else {
                    toastr.error(data.msg);
                }
            }, 'json').always(function() {
                // Hide the loader overlay regardless of the request result
                $('#loader-overlay').hide();
            });
        }
    });
});



        });
    </script>
@endpush
