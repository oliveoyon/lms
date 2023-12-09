@extends('dashboard.admin.layouts.admin-layout-with-cdn')
@section('title', 'Version')
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
                        <h1 class="m-0">{{ __('language.book_category') }}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ __('language.home') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('language.book_category') }}</li>
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
                                    {{ __('language.book_category_list') }}
                                </h3>
                                <div class="card-tools">
                                    <ul class="nav nav-pills ml-auto">
                                        <li class="nav-item">
                                            <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#addBookCategoryModal">
                                                <i class="fas fa-plus-square mr-1"></i>
                                                {{ __('language.add_book_category') }}
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body table-responsive">
                                <table class="table table-bordered table-striped table-hover table-sm" id="datas-table">
                                    <thead style="border-top: 1px solid #b4b4b4">
                                        <th style="width: 15px">#</th>
                                        <th>{{ __('language.category_name') }}</th>
                                        <th>{{ __('language.status') }}</th>
                                        <th style="width: 40px">{{ __('language.action') }}
                                            <button class="btn btn-sm btn-danger d-none" id="deleteAllBtn">{{ __('language.delete_all') }}</button>
                                        </th>
                                    </thead>
                                    <tbody>
                                        @foreach ($categories as $category)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="font-weight-bold">{{ $category->book_category_name }}</td>
                                                <td class="{{ $category->book_cat_status == 1 ? 'text-success' : 'text-danger' }} font-weight-bold">
                                                    {{ $category->book_cat_status == 1 ? __('language.active') : __('language.inactive') }}
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-warning btn-xs" data-id="{{ $category->id }}" id="editBookCategoryBtn">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-danger btn-xs" data-id="{{ $category->id }}" id="deleteBookCategoryBtn">
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


                <!-- Add Book Category Modal -->
<div class="modal fade" id="addBookCategoryModal" tabindex="-1" aria-labelledby="addBookCategoryLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title" id="addBookCategoryLabel">{{ __('language.add_book_category') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.addBookCategory') }}" method="post" autocomplete="off" id="add-category-form">
                    @csrf

                    <div class="form-group">
                        <label for="category_name">{{ __('language.category_name') }}</label>
                        <input type="text" class="form-control form-control-sm" name="category_name" id="category_name" placeholder="{{ __('language.category_name') }}">
                        <span class="text-danger error-text category_name_error"></span>
                    </div>

                    <div class="form-group">
                        <label for="category_status">{{ __('language.status') }}</label>
                        <select class="form-control form-control-sm" name="category_status" id="category_status">
                            <option value="1">{{ __('language.active') }}</option>
                            <option value="0">{{ __('language.inactive') }}</option>
                        </select>
                        <span class="text-danger error-text category_status_error"></span>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-block btn-success">{{ __('language.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal End -->



                <!-- Edit Book Category Modal -->
<div class="modal fade editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editBookCategoryLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editBookCategoryLabel">{{ __('language.edit_book_category') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.updateBookCategoryDetails') }}" enctype="multipart/form-data" files="true" method="post" autocomplete="off" id="update-category-form">
                    @csrf
                    <input type="hidden" name="category_id">

                    <div class="form-group">
                        <label for="category_name">{{ __('language.category_name') }}</label>
                        <input type="text" class="form-control form-control-sm" name="category_name" id="category_name" placeholder="{{ __('language.category_name') }}">
                        <span class="text-danger error-text category_name_error"></span>
                    </div>

                    <div class="form-group">
                        <label for="category_status">{{ __('language.status') }}</label>
                        <select class="form-control form-control-sm" name="category_status" id="category_status">
                            <option value="1">{{ __('language.active') }}</option>
                            <option value="0">{{ __('language.inactive') }}</option>
                        </select>
                        <span class="text-danger error-text category_status_error"></span>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-block bg-purple">{{ __('language.update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal End -->









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

            // Add Book Category RECORD
$('#add-category-form').on('submit', function(e) {
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
                $('#addBookCategoryModal').modal('hide');
                $('#addBookCategoryModal').find('form')[0].reset();
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

// Edit Book Category Modal
$(document).on('click', '#editBookCategoryBtn', function() {
    var category_id = $(this).data('id');

    $('.editCategoryModal').find('form')[0].reset();
    $('.editCategoryModal').find('span.error-text').text('');
    $.post("{{ route('admin.getBookCategoryDetails') }}", {
        category_id: category_id
    }, function(data) {
        var linkModal = $('.editCategoryModal');
        $('.editCategoryModal').find('input[name="category_id"]').val(data.details.id);
        $('.editCategoryModal').find('input[name="category_name"]').val(data.details.book_category_name);
        $('.editCategoryModal').find('select[name="category_status"]').val(data.details.book_cat_status);
        $('.editCategoryModal').modal('show');
    }, 'json');
});


            // Update Book Category RECORD
$('#update-category-form').on('submit', function(e) {
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
                $('.editCategoryModal').modal('hide');
                $('.editCategoryModal').find('form')[0].reset();
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

// DELETE Book Category RECORD
$(document).on('click', '#deleteBookCategoryBtn', function() {
    var category_id = $(this).data('id');
    var url = '{{ route('admin.deleteBookCategory') }}';

    swal.fire({
        title: 'Are you sure?',
        html: 'You want to <b>delete</b> this book category',
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
                category_id: category_id
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
