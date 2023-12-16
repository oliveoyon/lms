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
                        <h1 class="m-0">{{ __('language.assigned_teachers') }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ __('language.assigned_teachers') }}</a></li>
                        </ol>
                    </div>
                </div>
            </div>
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
                                    {{ __('language.assigned_teachers_list') }}
                                </h3>
                                <div class="card-tools">
                                    <ul class="nav nav-pills ml-auto">
                                        <li class="nav-item">
                                            <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#addAssignedTeacherModal">
                                                <i class="fas fa-plus-square mr-1"></i>
                                                {{ __('language.assign_teacher_add') }}
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body table-responsive">
                                <table class="table table-bordered table-striped table-hover table-sm" id="assignedTeachersTable">
                                    <thead style="border-top: 1px solid #b4b4b4">
                                        <th style="width: 15px">#</th>
                                        <th>Teacher Name</th>
                                        <th>Version</th>
                                        <th>Class</th>
                                        <th>Section</th>
                                        <th>Subject</th>
                                        <th>Academic Year</th>
                                        <th>Status</th>
                                        <th style="width: 40px">Action</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($assignedTeachers as $assignedTeacher)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $assignedTeacher->teacher->teacher_name }}</td>
                                                <td>{{ $assignedTeacher->version->version_name }}</td>
                                                <td>{{ $assignedTeacher->eduClass->class_name }}</td>
                                                <td>{{ $assignedTeacher->section->section_name }}</td>
                                                <td>{{ $assignedTeacher->subject->subject_name }}</td>
                                                <td>{{ $assignedTeacher->academic_year }}</td>
                                                <td>{{ $assignedTeacher->status == 1 ? 'Active' : 'Inactive' }}</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-warning btn-xs" data-id="{{ $assignedTeacher->id }}" id="editAssignedTeacherBtn">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-danger btn-xs" data-id="{{ $assignedTeacher->id }}" id="deleteAssignedTeacherBtn">
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


                <div class="modal fade" id="addAssignedTeacherModal" tabindex="-1" aria-labelledby="addAssignedTeacherLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-success">
                                <h5 class="modal-title" id="addAssignedTeacherLabel">Assign Teacher to Subject</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('admin.addAssignedTeacher') }}" method="post" enctype="multipart/form-data" id="add-assigned-teacher-form">
                                    @csrf
                
                                    <div class="row">
                                        <!-- First Column -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="academicYear" class="required">Academic Year:</label>
                                                <select class="form-control form-control-sm academic_year" name="academic_year" id="academic_yeasr">
                                                    <option value="">Academic Year</option>
                                                    @php
                                                        $currentYear = date('Y');
                                                    @endphp
                                                    @for ($i = $currentYear - 5; $i <= $currentYear + 5; $i++)
                                                        <option value="{{ $i }}">
                                                            {{ $i }} - {{ $i + 1 }}
                                                        </option>
                                                    @endfor
                                                </select>
                                                <span class="text-danger error-text academic_year_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="versionName" class="required">Version Name:</label>
                                                <select class="form-control form-control-sm version_id" name="version_id" id="version_id">
                                                    <option value="">{{ __('language.select_version') }}</option>
                                                    @foreach ($versions as $version)
                                                        <option value="{{ $version->id }}">{{ $version->version_name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger error-text version_id_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="className" class="required">Class Name:</label>
                                                <select class="form-control form-control-sm class_id" name="class_id" id="class_id" disabled>
                                                    <option value="">{{ __('language.select_class') }}</option>
                                                </select>
                                                <span class="text-danger error-text class_id_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="section_id" class="required">Section Name:</label>
                                                <select id="section_id" name="section_id" class="form-control form-control-sm section_id" disabled>
                                                    <option value="">{{ __('language.select_section') }}</option>
                                                </select>
                                                <span class="text-danger error-text section_id_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="subject_id" class="required">Subject Name:</label>
                                                <select id="subject_id" name="subject_id" class="form-control form-control-sm subject_id" disabled>
                                                    <option value="">{{ __('language.subject_name') }}</option>
                                                </select>
                                                <span class="text-danger error-text subject_id_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="teacher_id">Select Teacher:</label>
                                                <select name="teacher_id" id="teacher_id" class="form-control form-control-sm">
                                                    @foreach ($teachers as $teacher)
                                                        <option value="{{ $teacher->id }}">{{ $teacher->teacher_name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger error-text teacher_id_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="status">Status:</label>
                                                <select class="form-control form-control-sm" name="status"
                                                    id="status">
                                                    <option value="1">{{ __('language.active') }}</option>
                                                    <option value="0">{{ __('language.inactive') }}</option>
                                                </select>
                                                <span class="text-danger error-text status_error"></span>
                                                
                                            </div>
                                        </div>
                                        <span class="text-danger abc "></span>
                                    </div>
                
                                    
                                    <!-- Other form fields as needed -->
                
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-block btn-success">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade editAssignedTeacher" id="editAssignedTeacherModal" tabindex="-1" aria-labelledby="editAssignedTeacherLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-primary">
                                <h5 class="modal-title" id="editAssignedTeacherLabel">Edit Assigned Teacher to Subject</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('admin.updateAssignedTeacher') }}" method="post" enctype="multipart/form-data" id="update-assigned-teacher-form">
                                    @csrf
                
                                    <div class="row">
                                        <!-- First Column -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="academic_year" class="required">Academic Year:</label>
                                                <select class="form-control form-control-sm academic_year" name="academic_year" id="academic_year" readonly>
                                                    <option value="">Academic Year</option>
                                                    @php
                                                        $currentYear = date('Y');
                                                    @endphp
                                                    @for ($i = $currentYear - 5; $i <= $currentYear + 5; $i++)
                                                        <option value="{{ $i }}">
                                                            {{ $i }} - {{ $i + 1 }}
                                                        </option>
                                                    @endfor
                                                </select>
                                                <span class="text-danger error-text academic_year_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="version_id">Select Version:</label>
                                                <select name="version_id" id="version_id" class="form-control form-control-sm" readonly>
                                                    <!-- Options for version dropdown -->
                                                    @foreach ($versions as $version)
                                                        <option value="{{ $version->id }}">{{ $version->version_name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger error-text version_id_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="class_id">Select Class:</label>
                                                <select name="class_id" id="class_id" class="form-control form-control-sm" readonly>
                                                    <!-- Options for class dropdown -->
                                                    @foreach ($classes as $class)
                                                        <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger error-text class_id_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="section_id">Select Section:</label>
                                                <select name="section_id" id="section_id" class="form-control form-control-sm" readonly>
                                                    <!-- Options for section dropdown -->
                                                    <!-- Assuming you have a variable $sections with the sections data -->
                                                    @foreach ($sections as $section)
                                                        <option value="{{ $section->id }}">{{ $section->section_name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger error-text section_id_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="subject_id">Select Subject:</label>
                                                <select name="subject_id" id="subject_id" class="form-control form-control-sm" readonly>
                                                    <!-- Options for subject dropdown -->
                                                    @foreach ($subjects as $subject)
                                                        <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger error-text subject_id_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="teacher_id">Select Teacher:</label>
                                                <select name="teacher_id" id="teacher_id" class="form-control form-control-sm">
                                                    <!-- Options for teacher dropdown -->
                                                    @foreach ($teachers as $teacher)
                                                        <option value="{{ $teacher->id }}">{{ $teacher->teacher_name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger error-text teacher_id_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="status">Status:</label>
                                                <select class="form-control form-control-sm" name="status"
                                                    id="status">
                                                    <option value="1">{{ __('language.active') }}</option>
                                                    <option value="0">{{ __('language.inactive') }}</option>
                                                </select>
                                                <span class="text-danger error-text status_error"></span>
                                                
                                            </div>
                                        </div>
                                        <!-- Add other columns as needed -->
                
                                        <!-- Other form fields as needed -->
                
                                        
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-block btn-primary">Update</button>
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
            // When the "Academic Year" dropdown changes
            
            // When the "Version" dropdown changes
            $('.version_id').on('change', function() {
                var versionId = $(this).val();
                
                // Enable the "Class" dropdown
                $('.class_id').prop('disabled', false).data('version-id', versionId);
                
                // Add the extra option to the "Class" dropdown
                $('.class_id').html('<option value="">-- Please select a class --</option>');
                
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

            // When the "Class" dropdown changes
            $('.class_id').on('change', function() {
                var classId = $(this).val();
                var versionId = $(this).data('version-id'); // Retrieve the version_id

                // Enable the "Section" dropdown
                $('.section_id').prop('disabled', false).data('version-id', versionId); // Pass version_id to the Section dropdown

                // Enable the "Subject" dropdown
                $('.subject_id').prop('disabled', false).data('version-id', versionId); // Pass version_id to the Subject dropdown

                // Add the extra option to the "Section" dropdown
                $('.section_id').html('<option value="">-- Please select a section --</option>');

                // Add the extra option to the "Subject" dropdown
                $('.subject_id').html('<option value="">-- Please select a subject --</option>');

                // Make an AJAX request to fetch sections based on the selected class
                $.ajax({
                    url: '{{ route('admin.getSectionByClass') }}',
                    method: 'POST',
                    data: {
                        class_id: classId,
                        version_id: versionId, // Pass version_id to the server
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        var sectionDropdown = $('.section_id');

                        // Populate the "Section" dropdown with the fetched data
                        $.each(data.sections, function(key, value) {
                            sectionDropdown.append($('<option>', {
                                value: value.id,
                                text: value.section_name
                            }));
                        });
                    }
                });

                // Make an AJAX request to fetch subjects based on the selected class
                $.ajax({
                    url: '{{ route('admin.getSubjectsByClass') }}',
                    method: 'POST',
                    data: {
                        class_id: classId,
                        version_id: versionId, // Pass version_id to the server
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        var subjectDropdown = $('.subject_id');

                        // Populate the "Subject" dropdown with the fetched data
                        $.each(data.subjects, function(key, value) {
                            subjectDropdown.append($('<option>', {
                                value: value.id,
                                text: value.subject_name
                            }));
                        });
                    }
                });
            });

            // Assign Teacher to Subject
            $('#add-assigned-teacher-form').on('submit', function(e) {
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
                        $(form).find('span.abc').text('');
                    },
                    success: function(data) {
                        if (data.code == 0) {
                            $(form).find('span.abc').text(data.error.unique_combination);
                            $.each(data.error, function(prefix, val) {
                                $(form).find('span.' + prefix + '_error').text(val[0]);
                                
                            });
                            
                        } else {
                            var redirectUrl = data.redirect;
                            $('#addAssignedTeacherModal').modal('hide');
                            $('#addAssignedTeacherModal').find('form')[0].reset();
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

            // Click event for the edit button
            $(document).on('click', '#editAssignedTeacherBtn', function() {
                var assignedTeacherId = $(this).data('id');
                $('.editAssignedTeacher').find('form')[0].reset();
                $('.editAssignedTeacher').find('span.error-text').text('');

                // Fetch assigned teacher details using AJAX
                $.post("{{ route('admin.getAssignedTeacherDetails') }}", {
                    assigned_teacher_id: assignedTeacherId
                }, function(data) {
                    // Assuming you have similar structure in your HTML for the edit modal
                    $('.editAssignedTeacher').find('select[name="academic_year"]').val(data.details.academic_year);
                    $('.editAssignedTeacher').find('select[name="version_id"]').val(data.details.version_id);
                    $('.editAssignedTeacher').find('select[name="class_id"]').val(data.details.class_id);
                    $('.editAssignedTeacher').find('select[name="section_id"]').val(data.details.section_id);
                    $('.editAssignedTeacher').find('select[name="subject_id"]').val(data.details.subject_id);
                    $('.editAssignedTeacher').find('select[name="teacher_id"]').val(data.details.teacher_id);
                    $('.editAssignedTeacher').find('select[name="status"]').val(data.details.status);
                    $('.editAssignedTeacher').modal('show');
                }, 'json');
            });

            // Submit event for the update form
            $('#update-assigned-teacher-form').on('submit', function(e) {
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
                            $('.editAssignedTeacher').modal('hide');
                            $('.editAssignedTeacher').find('form')[0].reset();
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

            $(document).on('click', '#deleteAssignedTeacherBtn', function() {
                var assigned_teacher_id = $(this).data('id');
                var url = '<?= route('admin.deleteAssignedTeacher') ?>';

                swal.fire({
                    title: 'Are you sure?',
                    html: 'You want to <b>delete</b> this assigned teacher',
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
                            assigned_teacher_id: assigned_teacher_id
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
