@extends('dashboard.admin.layouts.admin-layout-with-cdn')
@section('title', 'Class Management')

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
                        <h1 class="m-0">{{ __('language.class_mgmt') }}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">

                        </ol>
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ __('language.dashboard') }}</a></li>
                            <li class="breadcrumb-item">{{ __('language.class_mgmt') }}</li>
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
                                    <i class="fas fa-chalkboard-teacher mr-1"></i>
                                    {{ __('language.class_list') }}
                                </h3>
                                <div class="card-tools">
                                    <ul class="nav nav-pills ml-auto">
                                        <li class="nav-item">

                                            <button class="btn btn-success btn-sm" data-toggle="modal"
                                                data-target="#addClassModal"><i class="fas fa-plus-square mr-1"></i>
                                                {{ __('language.class_add') }}</button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body table-responsive">
                                <div class="alert alert-danger" id="errorAlert" style="display: none;">
                                    <ul id="errorList">
                                        <!-- Error messages will be inserted here dynamically -->
                                    </ul>
                                </div>

                                <table class="table table-bordered table-striped table-hover table-sm" id="class-table">
                                    <thead style="border-top: 1px solid #b4b4b4">
                                        <th style="width: 10px">#</th>
                                        <th>{{ __('language.class_name') }}</th>
                                        <th>{{ __('language.version') }}</th>
                                        <th>{{ __('language.numeric') }}</th>
                                        <th>{{ __('language.status') }}</th>
                                        <th style="width: 40px">{{ __('language.action') }}</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($classes as $class)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="font-weight-bold">{{ $class->class_name }}</td>
                                                <td>{{ $class->version->version_name }}</td>
                                                <td>{{ $class->class_numeric }}</td>
                                                <td
                                                    class="{{ $class->class_status == 1 ? 'text-success' : 'text-danger' }} font-weight-bold">
                                                    {{ $class->class_status == 1 ? __('language.active') : __('language.inactive') }}
                                                </td>

                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-warning btn-xs"
                                                            data-id="{{ $class->id }}" id="editClassBtn"><i
                                                                class="fas fa-edit"></i></button>
                                                        <button type="button" class="btn btn-danger btn-xs"
                                                            data-id="{{ $class->id }}" id="deleteClassBtn"><i
                                                                class="fas fa-trash-alt"></i></button>
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

                <!-- Add Class Modal -->
                <div class="modal fade" id="addClassModal" tabindex="-1" aria-labelledby="addClassModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-success">
                                <h5 class="modal-title" id="addClassModalLabel">{{ __('language.class_add') }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('admin.addClass') }}" method="POST" autocomplete="off"
                                    id="add-class-form">
                                    @csrf
                                    <div class="form-group">
                                        <label for="class_name">{{ __('language.class_name') }}</label>
                                        <input type="text" class="form-control" name="class_name" id="class_name"
                                            placeholder="{{ __('language.class_name') }}">
                                        <span class="text-danger error-text class_name_error"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="version_id">{{ __('language.version') }}</label>
                                        <select class="form-control" name="version_id" id="version_id">
                                            @foreach ($versions as $version)
                                                <option value="{{ $version->id }}">{{ $version->version_name }}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger error-text version_id_error"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="class_numeric">{{ __('language.numeric') }}</label>
                                        <input type="number" class="form-control" name="class_numeric" id="class_numeric"
                                            placeholder="Numeric Value">
                                        <span class="text-danger error-text class_numeric_error"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="class_status">{{ __('language.status') }}</label>
                                        <select class="form-control" name="class_status" id="class_status">
                                            <option value="1">{{ __('language.active') }}</option>
                                            <option value="0">{{ __('language.inactive') }}</option>
                                        </select>
                                        <span class="text-danger error-text class_status_error"></span>
                                    </div>
                                    <button type="submit" class="btn btn-success">{{ __('language.save') }}</button>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Class Modal -->
                <div class="modal fade editClass" tabindex="-1" role="dialog" aria-labelledby="editClassLabel"
                    aria-hidden="true" data-keyboard="false" data-backdrop="static">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-purple">
                                <h5 class="modal-title" id="editClassLabel">{{ __('language.class_edit') }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('admin.updateClassDetails') }}" method="post" autocomplete="off"
                                    id="update-class-form">
                                    @csrf
                                    <input type="hidden" name="cid">
                                    <div class="form-group">
                                        <label for="class_name">{{ __('language.class_name') }}</label>
                                        <input type="text" class="form-control" name="class_name" id="class_name"
                                            placeholder="{{ __('language.class_name') }}">
                                        <span class="text-danger error-text class_name_error"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="version_id">{{ __('language.version') }}</label>
                                        <select class="form-control" name="version_id" id="version_id">
                                            @foreach ($versions as $version)
                                                <option value="{{ $version->id }}">{{ $version->version_name }}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger error-text version_id_error"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="class_numeric">{{ __('language.numeric') }}</label>
                                        <input type="number" class="form-control" name="class_numeric"
                                            id="class_numeric" placeholder="Numeric Value">
                                        <span class="text-danger error-text class_numeric_error"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="class_status">{{ __('language.status') }}</label>
                                        <select class="form-control" name="class_status" id="class_status">
                                            <option value="1">{{ __('language.active') }}</option>
                                            <option value="0">{{ __('language.inactive') }}</option>
                                        </select>
                                        <span class="text-danger error-text class_status_error"></span>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit"
                                            class="btn btn-block bg-purple">{{ __('language.update') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Section Modal -->



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

            $('#add-class-form').on('submit', function(e) {
                e.preventDefault();

                // Disable the submit button to prevent double-clicking
                $(this).find(':submit').prop('disabled', true);

                // Show the loader overlay
                $('#loader-overlay').show();

                var form = this;

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
                            $('#addClassModal').modal('hide');
                            $('#addClassModal').find('form')[0].reset();
                            toastr.success(data.msg);

                            setTimeout(function() {
                                window.location.href = redirectUrl;
                            }, 1000);
                        }
                    },
                    complete: function() {
                        // Enable the submit button and hide the loader overlay
                        $(form).find(':submit').prop('disabled', false);
                        $('#loader-overlay').hide();
                    }
                });
            });


            $(document).on('click', '#editClassBtn', function() {
                var class_id = $(this).data('id');
                $('.editClass').find('form')[0].reset();
                $('.editClass').find('span.error-text').text('');
                $.post("{{ route('admin.getClassDetails') }}", {
                    class_id: class_id
                }, function(data) {
                    $('.editClass').find('input[name="cid"]').val(data.details.id);
                    $('.editClass').find('input[name="class_name"]').val(data.details.class_name);
                    $('.editClass').find('select[name="version_id"]').val(data.details.version_id);
                    $('.editClass').find('input[name="class_numeric"]').val(data.details
                        .class_numeric);
                    $('.editClass').find('select[name="class_status"]').val(data.details
                        .class_status);
                    $('.editClass').modal('show');
                }, 'json');
            });

            // Update Class RECORD
            $('#update-class-form').on('submit', function(e) {
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
                            // $('#category-table').DataTable().ajax.reload(null, false);
                            var redirectUrl = data.redirect;
                            $('.editClass').modal('hide');
                            $('.editClass').find('form')[0].reset();
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

            // DELETE Class RECORD
            $(document).on('click', '#deleteClassBtn', function() {
                var class_id = $(this).data('id');
                var url = '<?= route('admin.deleteClass') ?>';

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You want to delete this class',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it',
                    cancelButtonText: 'Cancel',
                    showLoaderOnConfirm: true,
                    preConfirm: function() {
                        // Show the loader overlay
                        $('#loader-overlay').show();

                        return $.post(url, {
                            class_id: class_id
                        }, function(data) {
                            if (data.code == 1) {
                                var redirectUrl = data.redirect;
                                toastr.success(data.msg);
                                setTimeout(function() {
                                    window.location.href = redirectUrl;
                                }, 1000);
                            } else {
                                toastr.error(data.msg);
                            }
                        }, 'json');
                    },
                    allowOutsideClick: function() {
                        // Hide the loader overlay on outside click
                        $('#loader-overlay').hide();
                        return true;
                    }
                });
            });

        });
    </script>

@endpush
