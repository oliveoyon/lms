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
                        <h1 class="m-0">{{ __('language.version') }}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ __('language.version') }}</a>
                            </li>
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
                                    {{ __('language.version_list') }}
                                </h3>
                                <div class="card-tools">
                                    <ul class="nav nav-pills ml-auto">
                                        <li class="nav-item">

                                            <button class="btn btn-success btn-sm" data-toggle="modal"
                                                data-target="#addTeacherModal"><i class="fas fa-plus-square mr-1"></i>
                                                {{ __('language.version_add') }}</button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body table-responsive">
                                <table class="table table-bordered table-striped table-hover table-sm" id="datas-table">
                                    <thead style="border-top: 1px solid #b4b4b4">
                                        <th style="width: 15px">#</th>
                                        <th>Teacher Name</th>
                                        <th>Username</th>
                                        <th>Mobile</th>
                                        <th>Email</th>
                                        <th>Designation</th>
                                        <th>Gender</th>
                                        <th>Image</th>
                                        <th>Status</th>
                                        <th style="width: 40px">Action</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($teachers as $teacher)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $teacher->teacher_name }}</td>
                                            <td>{{ $teacher->teacher_user_name }}</td>
                                            <td>{{ $teacher->teacher_mobile }}</td>
                                            <td>{{ $teacher->teacher_email }}</td>
                                            <td>{{ $teacher->teacher_designation }}</td>
                                            <td>{{ $teacher->teacher_gender }}</td>
                                            <td>{{ $teacher->teacher_image }}</td>
                                            <td>{{ $teacher->teacher_status == 1 ? 'Active' : 'Inactive' }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-warning btn-xs" data-id="{{ $teacher->id }}" id="editTeacherBtn">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-xs" data-id="{{ $teacher->id }}" id="deleteTeacherBtn">
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


                <div class="modal fade" id="addTeacherModal" tabindex="-1" aria-labelledby="addTeacherLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-success">
                                <h5 class="modal-title" id="addTeacherLabel">Add Teacher</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('admin.addTeacher') }}" method="post" enctype="multipart/form-data" id="add-teacher-form">
                                    @csrf
                
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="teacher_name">Teacher Name</label>
                                                <input type="text" class="form-control form-control-sm" name="teacher_name" id="teacher_name"
                                                    placeholder="Teacher Name">
                                                <span class="text-danger error-text teacher_name_error"></span>
                                            </div>
                                        </div>
                
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="teacher_user_name">Username</label>
                                                <input type="text" class="form-control form-control-sm" name="teacher_user_name" id="teacher_user_name"
                                                    placeholder="Username">
                                                <span class="text-danger error-text teacher_user_name_error"></span>
                                            </div>
                                        </div>
                
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="teacher_mobile">Mobile</label>
                                                <input type="text" class="form-control form-control-sm" name="teacher_mobile" id="teacher_mobile"
                                                    placeholder="Mobile">
                                                <span class="text-danger error-text teacher_mobile_error"></span>
                                            </div>
                                        </div>
                                    </div>
                
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="teacher_email">Email</label>
                                                <input type="text" class="form-control form-control-sm" name="teacher_email" id="teacher_email"
                                                    placeholder="Email">
                                                <span class="text-danger error-text teacher_email_error"></span>
                                            </div>
                                        </div>
                
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="teacher_designation">Designation</label>
                                                <input type="text" class="form-control form-control-sm" name="teacher_designation" id="teacher_designation"
                                                    placeholder="Designation">
                                                <span class="text-danger error-text teacher_designation_error"></span>
                                            </div>
                                        </div>
                
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="teacher_gender">Gender</label>
                                                <select class="form-control form-control-sm" name="teacher_gender" id="teacher_gender">
                                                    <option value="Male">Male</option>
                                                    <option value="Female">Female</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                                <span class="text-danger error-text teacher_gender_error"></span>
                                            </div>
                                        </div>
                                    </div>
                
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="teacher_password">Password</label>
                                                <input type="password" class="form-control form-control-sm" name="teacher_password" id="teacher_password"
                                                    placeholder="Password">
                                                <span class="text-danger error-text teacher_password_error"></span>
                                            </div>
                                        </div>
                
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="teacher_image">Image</label>
                                                <input type="file" class="form-control form-control-sm" name="teacher_image" id="teacher_image">
                                                <span class="text-danger error-text teacher_image_error"></span>
                                            </div>
                                        </div>
                
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="teacher_status">Status</label>
                                                <select class="form-control form-control-sm" name="teacher_status" id="teacher_status">
                                                    <option value="1">Active</option>
                                                    <option value="0">Inactive</option>
                                                </select>
                                                <span class="text-danger error-text teacher_status_error"></span>
                                            </div>
                                        </div>
                                    </div>
                
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-block btn-success">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                


                {{-- Edit Modal --}}
                <!-- Edit Teacher Modal -->
<div class="modal fade editTeacher" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
data-keyboard="false" data-backdrop="static">
<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{ __('language.teacher_edit') }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="modal-body">
            <form action="{{ route('admin.updateTeacherDetails') }}" enctype="multipart/form-data" files="true"
                method="post" autocomplete="off" id="update-teacher-form">
                @csrf
                <input type="hidden" name="teacher_id">

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="teacher_name">{{ __('language.teacher_name') }}</label>
                            <input type="text" class="form-control form-control-sm" name="teacher_name"
                                id="teacher_name" placeholder="{{ __('language.teacher_name') }}">
                            <span class="text-danger error-text teacher_name_error"></span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="teacher_user_name">{{ __('language.teacher_user_name') }}</label>
                            <input type="text" class="form-control form-control-sm" name="teacher_user_name"
                                id="teacher_user_name" placeholder="{{ __('language.teacher_user_name') }}">
                            <span class="text-danger error-text teacher_user_name_error"></span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="teacher_mobile">{{ __('language.teacher_mobile') }}</label>
                            <input type="text" class="form-control form-control-sm" name="teacher_mobile"
                                id="teacher_mobile" placeholder="{{ __('language.teacher_mobile') }}">
                            <span class="text-danger error-text teacher_mobile_error"></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="teacher_email">{{ __('language.teacher_email') }}</label>
                            <input type="text" class="form-control form-control-sm" name="teacher_email"
                                id="teacher_email" placeholder="{{ __('language.teacher_email') }}">
                            <span class="text-danger error-text teacher_email_error"></span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="teacher_designation">{{ __('language.teacher_designation') }}</label>
                            <input type="text" class="form-control form-control-sm" name="teacher_designation"
                                id="teacher_designation" placeholder="{{ __('language.teacher_designation') }}">
                            <span class="text-danger error-text teacher_designation_error"></span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="teacher_gender">{{ __('language.teacher_gender') }}</label>
                            <select class="form-control form-control-sm" name="teacher_gender"
                                id="teacher_gender">
                                <option value="Male">{{ __('language.male') }}</option>
                                <option value="Female">{{ __('language.female') }}</option>
                                <option value="Other">{{ __('language.other') }}</option>
                            </select>
                            <span class="text-danger error-text teacher_gender_error"></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="teacher_password">{{ __('language.teacher_password') }}</label>
                            <input type="password" class="form-control form-control-sm" name="teacher_password"
                                id="teacher_password" placeholder="{{ __('language.teacher_password') }}">
                            <span class="text-danger error-text teacher_password_error"></span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="teacher_image">{{ __('language.teacher_image') }}</label>
                            <input type="file" class="form-control form-control-sm" name="teacher_image"
                                id="teacher_image">
                            <span class="text-danger error-text teacher_image_error"></span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="teacher_status">{{ __('language.teacher_status') }}</label>
                            <select class="form-control form-control-sm" name="teacher_status"
                                id="teacher_status">
                                <option value="1">{{ __('language.active') }}</option>
                                <option value="0">{{ __('language.inactive') }}</option>
                            </select>
                            <span class="text-danger error-text teacher_status_error"></span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-block bg-purple">{{ __('language.update') }}</button>
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

            // Add Teacher RECORD
            $('#add-teacher-form').on('submit', function(e) {
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
                            $('#addTeacherModal').modal('hide');
                            $('#addTeacherModal').find('form')[0].reset();
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


            // Edit Teacher Modal
$(document).on('click', '#editTeacherBtn', function() {
    var teacher_id = $(this).data('id');

    $('.editTeacher').find('form')[0].reset();
    $('.editTeacher').find('span.error-text').text('');
    $.post("{{ route('admin.getTeacherDetails') }}", {
        teacher_id: teacher_id
    }, function(data) {
        var linkModal = $('.editTeacher');
        $('.editTeacher').find('input[name="teacher_id"]').val(data.details.id);
        $('.editTeacher').find('input[name="teacher_name"]').val(data.details.teacher_name);
        $('.editTeacher').find('input[name="teacher_user_name"]').val(data.details.teacher_user_name);
        $('.editTeacher').find('input[name="teacher_mobile"]').val(data.details.teacher_mobile);
        $('.editTeacher').find('input[name="teacher_email"]').val(data.details.teacher_email);
        $('.editTeacher').find('input[name="teacher_designation"]').val(data.details.teacher_designation);
        $('.editTeacher').find('select[name="teacher_gender"]').val(data.details.teacher_gender);
        $('.editTeacher').find('input[name="teacher_password"]').val(""); // Reset password field
        $('.editTeacher').find('select[name="teacher_status"]').val(data.details.teacher_status);
        $('.editTeacher').modal('show');
    }, 'json');
});

// Update Teacher RECORD
$('#update-teacher-form').on('submit', function(e) {
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
                $('.editTeacher').modal('hide');
                $('.editTeacher').find('form')[0].reset();
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

// DELETE Teacher RECORD
$(document).on('click', '#deleteTeacherBtn', function() {
    var teacher_id = $(this).data('id');
    var url = '<?= route('admin.deleteTeacher') ?>';

    swal.fire({
        title: 'Are you sure?',
        html: 'You want to <b>delete</b> this teacher',
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
                teacher_id: teacher_id
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
