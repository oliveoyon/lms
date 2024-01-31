@extends('dashboard.admin.layouts.admin-layout-with-cdn')
@section('title', 'Subject Management')

@push('admincss')
    <!-- Add CSS dependencies for subjects here -->
    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.2.0/dist/sweetalert2.min.css">
    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endpush

@section('content')
    <style>
        .modal {
            z-index: 9999;
        }

        .background-content {
            pointer-events: none;
        }
    </style>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ __('language.subject') }}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ __('language.dashboard') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('language.subject') }}</li>
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
                                    {{ __('language.subject_list') }}
                                </h3>
                                <div class="card-tools">
                                    <ul class="nav nav-pills ml-auto">
                                        <li class="nav-item">
                                            <button class="btn btn-success btn-sm" data-toggle="modal"
                                                data-target="#addSubjectModal">
                                                <i class="fas fa-plus-square mr-1"></i> {{ __('language.subject_add') }}
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body table-responsive">
                                <!-- Your table for subject listing goes here -->
                                <table class="table table-bordered table-striped table-hover table-sm" id="subject-table">
                                    <thead style="border-top: 1px solid #b4b4b4">
                                        <th style="width: 15px">#</th>
                                        <th>{{ __('language.subject_name') }}</th>
                                        <th>{{ __('language.version') }}</th>
                                        <th>{{ __('language.class') }}</th>
                                        <th>{{ __('language.status') }}</th>
                                        <th style="width: 40px">{{ __('language.action') }}</th>
                                    </thead>
                                    <tbody>
                                        <!-- Loop through subjects and display them here -->
                                        @foreach ($subjects as $subject)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="font-weight-bold">{{ $subject->subject_name }}</td>
                                                <td>{{ $subject->version->version_name }}</td>
                                                <td>{{ $subject->class->class_name }}</td>
                                                <td
                                                    class="{{ $subject->subject_status == 1 ? 'text-success' : 'text-danger' }} font-weight-bold">
                                                    {{ $subject->subject_status == 1 ? __('language.active') : __('language.inactive') }}
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-warning btn-xs"
                                                            data-id="{{ $subject->id }}" id="editSubjectBtn">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-danger btn-xs"
                                                            data-id="{{ $subject->id }}" id="deleteSubjectBtn">
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
            </div>
        </div>
        <!-- /.content -->
    </div>

    <!-- Add Subject Modal -->
    <!-- Add Subject Modal -->
    <div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title" id="addSubjectModalLabel">{{ __('language.subject_add') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.addSubject') }}" method="POST" autocomplete="off" id="add-subject-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="subject_name">{{ __('language.subject_name') }}</label>
                                    <input type="text" class="form-control form-control-sm" name="subject_name"
                                        id="subject_name" placeholder="{{ __('language.subject_name') }}">
                                    <span class="text-danger error-text subject_name_error"></span>
                                </div>
                                <div class="form-group">
                                    <label for="version_id">{{ __('language.version') }}</label>
                                    <select class="form-control form-control-sm version_id" name="version_id"
                                        id="version_id">
                                        <option value="">{{ __('language.select_version') }}</option>
                                        @foreach ($versions as $version)
                                            <option value="{{ $version->id }}">{{ $version->version_name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error-text version_id_error"></span>
                                </div>
                                <div class="form-group">
                                    <label for="class_id">{{ __('language.class') }}</label>
                                    <select class="form-control form-control-sm class_id" name="class_id" id="class_id">
                                        <option value="">{{ __('language.select_class') }}</option>
                                    </select>
                                    <span class="text-danger error-text class_id_error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="subject_code">{{ __('language.subject_code') }}</label>
                                    <input type="text" class="form-control form-control-sm" name="subject_code"
                                        id="subject_code" placeholder="{{ __('language.subject_code') }}">
                                    <span class="text-danger error-text subject_code_error"></span>
                                </div>
                                <div class="form-group">
                                    <label for="academic_year">{{ __('language.academic_year') }}</label>
                                    <select class="form-control form-control-sm academic_year" name="academic_year" id="academic_year">
                                        <option value="">{{ __('language.academic_year') }}</option>
                                        @php
                                            $currentYear = date('Y');
                                        @endphp
                                        @for ($i = $currentYear - 10; $i <= $currentYear + 10; $i++)
                                            <option value="{{ $i }}">
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                    <span class="text-danger error-text academic_year_error"></span>
                                </div>
                                <div class="form-group">
                                    <label for="subject_status">{{ __('language.status') }}</label>
                                    <select class="form-control form-control-sm" name="subject_status"
                                        id="subject_status">
                                        <option value="1">{{ __('language.active') }}</option>
                                        <option value="0">{{ __('language.inactive') }}</option>
                                    </select>
                                    <span class="text-danger error-text subject_status_error"></span>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">{{ __('language.save') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Edit Subject Modal -->
    <div class="modal fade editSubject" tabindex="-1" role="dialog" aria-labelledby="editSubjectLabel"
        aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-purple">
                    <h5 class="modal-title" id="editSubjectLabel">{{ __('language.subject_edit') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.updateSubjectDetails') }}" method="post" autocomplete="off"
                        id="update-subject-form">
                        @csrf
                        <input type="hidden" name="subject_id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="subject_name">{{ __('language.subject_name') }}</label>
                                    <input type="text" class="form-control form-control-sm" name="subject_name"
                                        placeholder="{{ __('language.subject_name') }}">
                                    <span class="text-danger error-text subject_name_error"></span>
                                </div>
                                <div class="form-group">
                                    <label for="subject_code">{{ __('language.subject_code') }}</label>
                                    <input type="text" class="form-control form-control-sm" name="subject_code"
                                        placeholder="{{ __('language.subject_code') }}">
                                    <span class="text-danger error-text subject_code_error"></span>
                                </div>
                                <div class="form-group">
                                    <label for="academic_year">{{ __('language.academic_year') }}</label>
                                    <select class="form-control form-control-sm academic_year" name="academic_year" id="academic_year">
                                        <option value="">{{ __('language.academic_year') }}</option>
                                        @php
                                            $currentYear = date('Y');
                                        @endphp
                                        @for ($i = $currentYear - 10; $i <= $currentYear + 10; $i++)
                                            <option value="{{ $i }}">
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                    <span class="text-danger error-text academic_year_error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="version_id">{{ __('language.version') }}</label>
                                    <select class="form-control form-control-sm version_id" name="version_id"
                                        id="version_id">
                                        <option value="">{{ __('language.select_version') }}</option>
                                        @foreach ($versions as $version)
                                            <option value="{{ $version->id }}">{{ $version->version_name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error-text version_id_error"></span>
                                </div>
                                <div class="form-group">
                                    <label for="class_id">{{ __('language.class') }}</label>
                                    <select class="form-control form-control-sm class_id" name="class_id" id="class_id">
                                        @foreach ($classes as $class)
                                            <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error-text class_id_error"></span>
                                </div>
                                <div class="form-group">
                                    <label for="subject_status">{{ __('language.status') }}</label>
                                    <select class="form-control form-control-sm" name="subject_status">
                                        <option value="1">{{ __('language.active') }}</option>
                                        <option value="0">{{ __('language.inactive') }}</option>
                                    </select>
                                    <span class="text-danger error-text subject_status_error"></span>
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

@endsection

@push('adminjs')
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.2.0/dist/sweetalert2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(document).ready(function() {
            // When the "Version" dropdown changes
            $('.version_id').on('change', function() {
                var versionId = $(this).val();

                // Make an AJAX request to fetch classes based on the selected version
                $.ajax({
                    url: '{{ route('admin.getClassesByVersion') }}',
                    method: 'POST',
                    data: {
                        version_id: versionId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        var classDropdown = $('.class_id');
                        classDropdown.empty();

                        // Populate the "Class" dropdown with the fetched data
                        $.each(data.classes, function(key, value) {
                            classDropdown.append($('<option>', {
                                value: value.id,
                                text: value.class_name
                            }));
                        });
                    }
                });
            });
        });
    </script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            // Handle subject form submission
            $('#add-subject-form').on('submit', function(e) {
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
                            // Handle validation errors
                            $.each(data.error, function(prefix, val) {
                                $(form).find('span.' + prefix + '_error').text(val[0]);
                            });
                        } else {
                            // Handle successful subject addition
                            var redirectUrl = data.redirect;
                            $('#addSubjectModal').modal('hide');
                            $('#add-subject-form')[0].reset();
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


            $(document).on('click', '#editSubjectBtn', function() {
                var subject_id = $(this).data('id');
                $('.editSubject').find('form')[0].reset();
                $('.editSubject').find('span.error-text').text('');
                $.post("{{ route('admin.getSubjectDetails') }}", {
                    subject_id: subject_id
                }, function(data) {
                    $('.editSubject').find('input[name="subject_id"]').val(data.details.id);
                    $('.editSubject').find('input[name="subject_name"]').val(data.details
                        .subject_name);
                    $('.editSubject').find('select[name="version_id"]').val(data.details
                    .version_id);
                    $('.editSubject').find('select[name="class_id"]').val(data.details.class_id);
                    $('.editSubject').find('input[name="subject_code"]').val(data.details
                        .subject_code);
                    $('.editSubject').find('select[name="academic_year"]').val(data.details
                        .academic_year);
                    $('.editSubject').find('select[name="class_id"]').val(data.details.class_id);
                    $('.editSubject').find('select[name="subject_status"]').val(data.details
                        .subject_status);
                    $('.editSubject').modal('show');
                }, 'json');
            });

            $('#update-subject-form').on('submit', function(e) {
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
                            // Handle validation errors
                            $.each(data.error, function(prefix, val) {
                                $(form).find('span.' + prefix + '_error').text(val[0]);
                            });
                        } else {
                            // Handle successful subject update
                            var redirectUrl = data.redirect;
                            $('.editSubject').modal('hide');
                            $('.editSubject').find('form')[0].reset();
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

            // Handle subject deletion
$(document).on('click', '#deleteSubjectBtn', function () {
    var subject_id = $(this).data('id');
    var url = '<?= route("admin.deleteSubject"); ?>';

    Swal.fire({
        title: 'Are you sure?',
        text: 'You want to delete this subject',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it',
        cancelButtonText: 'Cancel',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            // Show the loader overlay
            $('#loader-overlay').show();

            return $.post(url, { subject_id: subject_id }, function (data) {
                if (data.code == 1) {
                    var redirectUrl = data.redirect;
                    toastr.success(data.msg);
                    setTimeout(function () {
                        window.location.href = redirectUrl;
                    }, 1000);
                } else {
                    toastr.error(data.msg);
                }
            }, 'json');
        },
        allowOutsideClick: function () {
            // Hide the loader overlay on outside click
            $('#loader-overlay').hide();
            return true;
        }
    });
});





        });
    </script>
@endpush