@extends('dashboard.admin.layouts.admin-layout-with-cdn')
@section('title', 'Bulk Student Admission')
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
                    <h1 class="m-0">{{ __('language.bulk_student_admission') }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ __('language.dashboard') }}</a></li>
                        <li class="breadcrumb-item">{{ __('language.bulk_student_admission') }}</li>
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

                    <div class="card">
                        <div class="card-header bg-danger">
                            <h3 class="card-title">{{ __('language.bulk_student_admission') }}</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body bg-info">
                            <p>{{ __('language.bulk_student_upload_instructions') }}</p>
                            <a href="{{ asset('Students.csv') }}" download="Students.csv">
                                <button type="button" class="btn btn-warning btn-sm btn-flat">Download Template</button>
                            </a>

                        </div>
                    </div>

                        <form action="{{ route('admin.bulkstdAdmission') }}" method="POST"  enctype="multipart/form-data" autocomplete="off" id="add-student-form">
                            @csrf

                            <div class="card">
                                <div class="card-header bg-gray">
                                    <h3 class="card-title">{{ __('language.academic_details') }}</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="academicYear" class="required">{{ __('language.academic_year') }}</label>
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
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="versionName" class="required">{{ __('language.version_name') }}</label>
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
                                                <label for="className" class="required">{{ __('language.class_name') }}</label>
                                                <select class="form-control form-control-sm class_id" name="class_id" id="class_id" disabled>
                                                    <option value="">{{ __('language.select_class') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="section_id" class="required">{{ __('language.section_name') }}</label>
                                                <select id="section_id" name="section_id" class="form-control form-control-sm section_id" disabled>
                                                    <option value="">{{ __('language.select_section') }}</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="feeSetup" class="required">{{ __('language.fee_setup') }}</label>
                                                <select id="feeSetup" name="feeSetup" class="form-control form-control-sm feesetup" id="feesetup" disabled>
                                                    <option value="">Please select a Fee</option>

                                                </select>
                                            </div>
                                        </div>


                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="admissiondate" class="required">{{ __('language.admission_date') }}</label>
                                                <input type="date" id="admissiondate" name="admission_date" class="form-control form-control-sm">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="studentCategory" class="required">{{ __('language.std_category') }}</label>
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
                                                <label for="feeSetup" class="required">{{ __('language.upload_csv') }}</label>
                                                <input type="file" name="upload" id="">
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="btn-container">
                                <button type="submit" class="btn btn-primary">{{ __('language.save') }}</button>
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
