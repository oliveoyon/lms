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
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ __('language.fee_head') }}</a></li>
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
                    
                    
                        
                        <form action="{{ route('admin.stdAdmission') }}" method="POST" autocomplete="off" id="add-student-form">
                            @csrf
                        
                            <div class="card">
                                <div class="card-header bg-gray">
                                    <h3 class="card-title">Academic Details</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
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
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="versionName" class="required">Version Name:</label>
                                                <select class="form-control form-control-sm version_id" name="version_id" id="version_id">
                                                    <option value="">{{ __('language.select_version') }}</option>
                                                    @foreach ($versions as $version)
                                                        <option value="{{ $version->id }}">{{ $version->version_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="className" class="required">Class Name:</label>
                                                <select class="form-control form-control-sm class_id" name="class_id" id="class_id" disabled>
                                                    <option value="">{{ __('language.select_class') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="section_id" class="required">Section Name:</label>
                                                <select id="section_id" name="section_id" class="form-control form-control-sm section_id" disabled>
                                                    <option value="">{{ __('language.select_section') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="rollNo" class="required">Roll No:</label>
                                                <div class="input-group">
                                                    <input type="text" id="rollNo" name="roll_no" class="form-control form-control-sm" placeholder="Roll No">
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
                                                <input type="date" id="admissiondate" name="admission_date" class="form-control form-control-sm">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="studentCategory" class="required">Student Category:</label>
                                                <select id="studentCategory" name="std_category" class="form-control form-control-sm">
                                                    <option value="">Select Student Category</option>
                                                    <option value="Regular">Regular</option>
                                                    <option value="Transferred">Transferred</option>
                                                    <option value="Interchanged">Interchanged</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="feeSetup" class="required">Fee Setup:</label>
                                                <select id="feeSetup" name="feeSetup" class="form-control form-control-sm feesetup" id="feesetup" disabled>
                                                    <option value="">Please select a Fee</option>
                                                    
                                                </select>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        
                            <div class="card">
                                <div class="card-header bg-gray">
                                    <h3 class="card-title">Personal Details</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="studentFullName" class="required">Student Full Name (In English):</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-user"></i>
                                                    </span>
                                                </div>
                                                <input type="text" id="studentFullName" name="std_name" class="form-control form-control-sm">
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
                                                <input type="text" id="studentFullNameBangla" name="std_name_bn" class="form-control form-control-sm">
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
                                                <input type="text" id="fatherName" name="std_fname" class="form-control form-control-sm">
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
                                                <input type="text" id="motherName" name="std_mname" class="form-control form-control-sm">
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
                                                <input type="tel" id="studentPhone" name="std_phone" class="form-control form-control-sm">
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
                                                <input type="tel" id="studentPhoneAlt" name="std_phone1" class="form-control form-control-sm">
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
                                                <input type="date" id="dob" name="std_dob" class="form-control form-control-sm">
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
                                                <input type="email" id="email" name="std_email" class="form-control form-control-sm">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="bloodGroup">Blood Group:</label>
                                            <select id="bloodGroup" name="blood_group" class="form-control form-control-sm">
                                                <option value="A+">A+</option>
                                                <option value="A-">A-</option>
                                                <option value="B+">B+</option>
                                                <option value="B-">B-</option>
                                                <option value="AB+">AB+</option>
                                                <option value="AB-">AB-</option>
                                                <option value="O+">O+</option>
                                                <option value="O-">O-</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="presentAddress" class="required">Present Address:</label>
                                            <textarea id="presentAddress" name="std_present_address" class="form-control form-control-sm" rows="5"></textarea>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="permanentAddress" class="required">Permanent Address:</label>
                                            <textarea id="permanentAddress" name="std_permanent_address" class="form-control form-control-sm" rows="5"></textarea>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="gender" class="required">Gender:</label>
                                            <select id="gender" name="std_gender" class="form-control form-control-sm">
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                            <div class="card">
                                <div class="card-header bg-gray">
                                    <h3 class="card-title">Parents/Guardian Details</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                        </button>
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
                                                <input type="text" id="fatherOccupation" name="std_f_occupation" class="form-control form-control-sm">
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
                                                <input type="text" id="motherOccupation" name="std_m_occupation" class="form-control form-control-sm">
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
                                                <input type="number" id="yearlyIncome" name="f_yearly_income" class="form-control form-control-sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                            <div class="card">
                                <div class="card-header bg-gray">
                                    <h3 class="card-title">Image Upload</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
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
                                    <h3 class="card-title">Guardian's Information (If the student does not live with parents)</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                        </button>
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
                                                <input type="text" id="guardianName" name="std_gurdian_name" class="form-control form-control-sm">
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
                                                <input type="text" id="relationship" name="std_gurdian_relation" class="form-control form-control-sm">
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
                                                <input type="tel" id="guardianPhone" name="std_gurdian_mobile" class="form-control form-control-sm">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="guardianAddress">Guardian's Address:</label>
                                        <textarea id="guardianAddress" name="std_gurdian_address" class="form-control form-control-sm summernote" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        
                            <div class="btn-container">
                                <button type="submit" class="btn btn-primary">Finish</button>
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

$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


$(document).ready(function() {
    // When the "Academic Year" dropdown changes
    $('.academic_year').on('change', function() {
        var academic_year = $(this).val();
        
        // Enable the "Class" dropdown
        $('.feesetup').prop('disabled', false).data('academic_year', academic_year);
        
        // Add the extra option to the "Class" dropdown
        $('.feesetup').html('<option value="">-- Please select a Fee --</option>');
        
        // Make an AJAX request to fetch classes based on the selected version
        $.ajax({
            url: '{{ route('admin.getFeegroupByAcademicYear') }}',
            method: 'POST',
            data: {
                academic_year: academic_year,
                _token: '{{ csrf_token() }}'
            },
            success: function(data) {
                var feeDropdown = $('.feesetup');
                
                // Populate the "Class" dropdown with the fetched data
                $.each(data.feegroups, function(key, value) {
                    feeDropdown.append($('<option>', {
                        value: value.id,
                        text: value.aca_group_name
                    }));
                });
            }
        });
    });

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
                    sectionDropdown.append($('<option>', {
                        value: value.id,
                        text: value.section_name
                    }));
                });
            }
        });
    });


    $('#add-student-form').on('submit', function(e) {
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
                $.each(data.error, function (field, messages) {
                        // Add 'is-invalid' class to the input field
                        $(form).find('[name="' + field + '"]').addClass('is-invalid');
                        // Display the first error message for the field
                        $(form).find('span.' + field + '_error').text(messages[0]);
                    });
            } else {
                var redirectUrl = data.redirect;
                // $(form).find('form')[0].reset();
                // $(form).find('.form-control').removeClass('is-invalid');
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
