@extends('dashboard.admin.layouts.admin-layout-with-cdn')
@section('title', 'Fee Head')
@push('admincss')
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.2.0/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- Add your custom CSS here -->
    <style>
        .required:after {
            content: " *";
            color: red;
        }

        .card-title {
            font-size: 20px;
            color: white;
            font-family: 'Lucida Sans', 'SolaimanLipi'
        }
    </style>
@endpush

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ __('language.fee_head') }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a
                                    href="{{ route('admin.home') }}">{{ __('language.fee_head') }}</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-danger alert-dismissible" id="errorAlert" style="display: none;">
                            <button type="button" class="close" id="closeErrorAlert">&times;</button>
                            <span id="errorAlertText"></span>
                        </div>

                        <div class="alert alert-success alert-dismissible" id="completionAlert" style="display: none;">
                            <button type="button" class="close" id="closeCompletionAlert">&times;</button>
                            <span id="completionAlertText"></span>
                        </div>



                        <form action="{{ route('admin.stdAppliedEdit') }}" method="POST" autocomplete="off"
                            id="add-student-form">
                            @csrf

                            <div class="card">
                                <div class="card-header bg-gray">
                                    <h3 class="card-title">Academic Details</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                                class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <input type="hidden" name="std_hash_id" value="{{ $student->std_hash_id }}">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="academicYear" class="required">Academic Year:</label>
                                                <select class="form-control form-control-sm academic_year"
                                                    name="academic_year" id="academic_yeasr">
                                                    <option value="">Academic Year</option>
                                                    <option value="2024">2024</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="versionName" class="required">Version Name:</label>
                                                <select class="form-control form-control-sm version_id" name="version_id"
                                                    id="version_id">
                                                    <option value="">{{ __('language.select_version') }}</option>
                                                    @foreach ($versions as $version)
                                                        <option value="{{ $version->id }}"
                                                            {{ old('version_id', $student->version_id) == $version->id ? 'selected' : '' }}>
                                                            {{ $version->version_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="className" class="required">Class Name:</label>
                                                <select class="form-control form-control-sm class_id" name="class_id"
                                                    id="class_id" disabled>
                                                    <option value="">{{ __('language.select_class') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="section_id" class="required">Section Name:</label>
                                                <select id="section_id" name="section_id"
                                                    class="form-control form-control-sm section_id" disabled>
                                                    <option value="">{{ __('language.select_section') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="rollNo" class="required">Roll No:</label>
                                                <div class="input-group">
                                                    <input type="text" id="rollNo" name="roll_no"
                                                        class="form-control form-control-sm"
                                                        value="">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">
                                                            <i class="fa fa-user"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="admissiondate" class="required">Admission Date:</label>
                                                <?php
                                                $formattedDate = $student->admission_date ? (new DateTime($student->admission_date))->format('Y-m-d') : '';
                                                ?>
                                                <input type="date" id="admissiondate" name="admission_date"
                                                    class="form-control form-control-sm" value="{{ $formattedDate }}">
                                            </div>
                                        </div>
                                    </div> --}}
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="studentCategory" class="required">Action Taken:</label>
                                                <select id="studentCategory" name="std_status"
                                                    class="form-control form-control-sm">
                                                    <option value="0">Pending</option>
                                                    <option value="1">Reject</option>
                                                    <option value="2">Approve</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for=""></label>
                                            <div class="btn-container">
                                                <button type="submit" class="btn btn-primary btn-block">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="card">
                                <div class="card-header bg-gray">
                                    <h3 class="card-title">Personal Details</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                                class="fas fa-minus"></i></button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="studentFullName" class="required">Student Full Name (In
                                                English):</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-user"></i>
                                                    </span>
                                                </div>
                                                <input type="text" id="studentFullName" name="std_name"
                                                    class="form-control form-control-sm"
                                                    value="{{ $student->std_name }}">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="studentFullNameBangla">Student Full Name (In Bangla):</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-user"></i>
                                                    </span>
                                                </div>
                                                <input type="text" id="studentFullNameBangla" name="std_name_bn"
                                                    class="form-control form-control-sm"
                                                    value="{{ $student->std_name_bn }}">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="fatherName" class="required">Father's Name:</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-user"></i>
                                                    </span>
                                                </div>
                                                <input type="text" id="fatherName" name="std_fname"
                                                    class="form-control form-control-sm"
                                                    value="{{ $student->std_fname }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="motherName" class="required">Mother's Name:</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-user"></i>
                                                    </span>
                                                </div>
                                                <input type="text" id="motherName" name="std_mname"
                                                    class="form-control form-control-sm"
                                                    value="{{ $student->std_mname }}">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="studentPhone" class="required">Student's Phone:</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-phone"></i>
                                                    </span>
                                                </div>
                                                <input type="tel" id="studentPhone" name="std_phone"
                                                    class="form-control form-control-sm"
                                                    value="{{ $student->std_phone }}">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="studentPhoneAlt">Student's Phone Alternative:</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-phone"></i>
                                                    </span>
                                                </div>
                                                <input type="tel" id="studentPhoneAlt" name="std_phone1"
                                                    class="form-control form-control-sm"
                                                    value="{{ $student->std_phone1 }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="dob" class="required">Date of Birth:</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-calendar"></i>
                                                    </span>
                                                </div>
                                                <input type="date" id="dob" name="std_dob"
                                                    class="form-control form-control-sm"
                                                    value="{{ $student->std_dob ? (new DateTime($student->std_dob))->format('Y-m-d') : '' }}">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="email">Email:</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-envelope"></i>
                                                    </span>
                                                </div>
                                                <input type="email" id="email" name="std_email"
                                                    class="form-control form-control-sm"
                                                    value="{{ $student->std_email }}">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="bloodGroup">Blood Group:</label>
                                            <select id="bloodGroup" name="blood_group"
                                                class="form-control form-control-sm">
                                                <option value="A+"
                                                    {{ $student->blood_group == 'A+' ? 'selected' : '' }}>A+</option>
                                                <option value="A-"
                                                    {{ $student->blood_group == 'A-' ? 'selected' : '' }}>A-</option>
                                                <option value="B+"
                                                    {{ $student->blood_group == 'B+' ? 'selected' : '' }}>B+</option>
                                                <option value="B-"
                                                    {{ $student->blood_group == 'B-' ? 'selected' : '' }}>B-</option>
                                                <option value="AB+"
                                                    {{ $student->blood_group == 'AB+' ? 'selected' : '' }}>AB+</option>
                                                <option value="AB-"
                                                    {{ $student->blood_group == 'AB-' ? 'selected' : '' }}>AB-</option>
                                                <option value="O+"
                                                    {{ $student->blood_group == 'O+' ? 'selected' : '' }}>O+</option>
                                                <option value="O-"
                                                    {{ $student->blood_group == 'O-' ? 'selected' : '' }}>O-</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="presentAddress" class="required">Present Address:</label>
                                            <textarea id="presentAddress" name="std_present_address" class="form-control form-control-sm" rows="5">{{ $student->std_present_address }}</textarea>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="permanentAddress" class="required">Permanent Address:</label>
                                            <textarea id="permanentAddress" name="std_permanent_address" class="form-control form-control-sm" rows="5">{{ $student->std_permanent_address }}</textarea>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="gender" class="required">Gender:</label>
                                            <select id="gender" name="std_gender"
                                                class="form-control form-control-sm">
                                                <option value="male"
                                                    {{ $student->std_gender == 'male' ? 'selected' : '' }}>Male</option>
                                                <option value="female"
                                                    {{ $student->std_gender == 'female' ? 'selected' : '' }}>Female
                                                </option>
                                                <option value="other"
                                                    {{ $student->std_gender == 'other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="card">
                                <div class="card-header bg-gray">
                                    <h3 class="card-title">Parents/Guardian Details</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                                class="fas fa-minus"></i></button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="fatherOccupation">Father's Occupation:</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-briefcase"></i>
                                                    </span>
                                                </div>
                                                <input type="text" id="fatherOccupation" name="std_f_occupation"
                                                    class="form-control form-control-sm"
                                                    value="{{ $student->std_f_occupation }}">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="motherOccupation">Mother's Occupation:</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-briefcase"></i>
                                                    </span>
                                                </div>
                                                <input type="text" id="motherOccupation" name="std_m_occupation"
                                                    class="form-control form-control-sm"
                                                    value="{{ $student->std_m_occupation }}">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="yearlyIncome">Yearly Income:</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-dollar-sign"></i>
                                                    </span>
                                                </div>
                                                <input type="number" id="yearlyIncome" name="f_yearly_income"
                                                    class="form-control form-control-sm"
                                                    value="{{ $student->f_yearly_income }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="card">
                                <div class="card-header bg-gray">
                                    <h3 class="card-title">Image Upload</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                                class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="pic">Upload Your Photo:</label>
                                        <input type="file" id="pic" name="std_picture" accept="image/*">
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header bg-gray">
                                    <h3 class="card-title">Guardian's Information (If the student does not live with
                                        parents)</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                                class="fas fa-minus"></i></button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="guardianName">Guardian's Name:</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-user"></i>
                                                    </span>
                                                </div>
                                                <input type="text" id="guardianName" name="std_gurdian_name"
                                                    class="form-control form-control-sm"
                                                    value="{{ $student->std_gurdian_name }}">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="relationship">Relationship with Student:</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-users"></i>
                                                    </span>
                                                </div>
                                                <input type="text" id="relationship" name="std_gurdian_relation"
                                                    class="form-control form-control-sm"
                                                    value="{{ $student->std_gurdian_relation }}">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="guardianPhone">Guardian's Phone:</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-phone"></i>
                                                    </span>
                                                </div>
                                                <input type="tel" id="guardianPhone" name="std_gurdian_mobile"
                                                    class="form-control form-control-sm"
                                                    value="{{ $student->std_gurdian_mobile }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="guardianAddress">Guardian's Address:</label>
                                        <textarea id="guardianAddress" name="std_gurdian_address" class="form-control form-control-sm summernote"
                                            rows="3">{{ $student->std_gurdian_address }}</textarea>
                                    </div>
                                </div>
                            </div>




                        </form>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('adminjs')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.2.0/dist/sweetalert2.min.js"></script>
    <!-- Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
$(document).ready(function() {
    // Simulate change event for the "Version" dropdown
    $('.version_id').trigger('change');

    // Simulate change event for the "Class" dropdown (if a version is already selected)
    if ($('.class_id').val()) {
        $('.class_id').trigger('change');
    }
});

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
                var option = $('<option>', {
                    value: value.id,
                    text: value.class_name
                });

                // Pre-select the option if it matches the class_id from AcademicStudents
                if (value.id == {{ $student->class_id }}) {
                    option.prop('selected', true);
                }

                classDropdown.append(option);
            });

            // Trigger change event to populate the Section dropdown
            $('.class_id').trigger('change');
        }
    });
});

// When the "Class" dropdown changes
$('.class_id').on('change', function() {
    var classId = $(this).val();
    var versionId = $(this).data('version-id'); // Retrieve the version_id

    // Enable the "Section" dropdown
    $('.section_id').prop('disabled', false).data('version-id', versionId); // Pass version_id to the Section dropdown

    // Add the extra option to the "Section" dropdown
    $('.section_id').html('<option value="">-- Please select a section --</option>');

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
                var option = $('<option>', {
                    value: value.id,
                    text: value.section_name
                });

                // Pre-select the option if it matches the section_id from AcademicStudents


                sectionDropdown.append(option);
            });
        }
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



            $('#add-student-form').on('submit', function(e) {
    e.preventDefault();

    // Disable the submit button to prevent double-clicking
    $(this).find(':submit').prop('disabled', true);

    // Show the loader overlay
    $('#loader-overlay').show();

    var form = this;
    var studentId = $('.version_id').data('student-id');

    // Append the student ID to the form data
    var formData = new FormData(form);
    formData.append('student_id', studentId);

    $.ajax({
        url: $(form).attr('action'),
        method: $(form).attr('method'),
        data: formData,
        processData: false,
        dataType: 'json',
        contentType: false,
        beforeSend: function() {
            $(form).find('span.error-text').text('');
        },
        success: function(data) {
            if (data.code == 0) {
                $.each(data.error, function(field, messages) {
                    // Add 'is-invalid' class to the input field
                    $(form).find('[name="' + field + '"]').addClass('is-invalid');
                    // Display the first error message for the field
                    $(form).find('span.' + field + '_error').text(messages[0]);
                });
            } else {
                var redirectUrl = data.redirect;
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



        });
    </script>
@endpush
