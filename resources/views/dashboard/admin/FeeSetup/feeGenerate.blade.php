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



                        <form action="{{ route('admin.customFeeGen') }}" method="POST" autocomplete="off"
                            id="add-attendance-form">
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
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="academicYear" class="required">Academic Year:</label>
                                                <select class="form-control form-control-sm academic_year"
                                                    name="academic_year" id="academic_year">
                                                    @php
                                                        $currentYear = date('Y');
                                                    @endphp
                                                    @for ($i = $currentYear - 2; $i <= $currentYear + 2; $i++)
                                                        <option value="{{ $i }}"
                                                            {{ $i == $currentYear ? 'selected' : '' }}>
                                                            {{ $i }}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="versionName">Version Name:</label>
                                                <select class="form-control form-control-sm version_id" name="version_id"
                                                    id="version_id">
                                                    <option value="">{{ __('language.select_version') }}</option>
                                                    @foreach ($versions as $version)
                                                        <option value="{{ $version->id }}">{{ $version->version_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="className">Class Name:</label>
                                                <select class="form-control form-control-sm class_id" name="class_id"
                                                    id="class_id" disabled>
                                                    <option value="">{{ __('language.select_class') }}</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="section_id">Section Name:</label>
                                                <select id="section_id" name="section_id"
                                                    class="form-control form-control-sm section_id" disabled>
                                                    <option value="">{{ __('language.select_section') }}</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="student_id">Student Name:</label>
                                                <select id="student_id" name="std_id[]" multiple
                                                    class="form-control form-control-sm student_id select2" disabled>
                                                    <option value="">{{ __('language.student_name') }}</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="student_id" class="required">Fee Description:</label>
                                                <input type="text" class="form-control form-control-sm" name="fee_desc">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="academicYear" class="required">For Month (Multiple):</label>
                                                <select class="form-control form-control-sm month select2" name="month[]" id="month" multiple>
                                                    @php
                                                        $currentMonth = date('m');
                                                    @endphp

                                                    @for ($i = 1; $i <= 12; $i++)
                                                        <option value="{{ $i }}"
                                                            {{ $i == $currentMonth ? 'selected' : '' }}>
                                                            {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="student_id" class="required">Amount:</label>
                                                <input type="number" class="form-control form-control-sm" name="amount" id="amount">
                                            </div>
                                        </div>





                                    </div>

                                </div>
                            </div>

                            <div id="records"></div>



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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"
        integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
                $('.section_id').prop('disabled', false).data('version-id',
                    versionId); // Pass version_id to the Section dropdown

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


            $('#add-attendance-form').on('submit', function(e) {
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
                        // Remove 'is-invalid' class and error messages on form submission
                        $(form).find('.form-control').removeClass('is-invalid');
                        $(form).find('.invalid-feedback').text('');
                    },
                    success: function(data) {
                        if (data.code == 0) {
                            $.each(data.error, function(field, messages) {
                                // Add 'is-invalid' class to the input field
                                $(form).find('[name="' + field + '"]').addClass(
                                    'is-invalid');
                                // Display the first error message for the field
                                $(form).find('span.' + field + '_error').text(messages[
                                    0]);

                                // Show Toastr error message


                            });
                            if (data.msg) {
                                toastr.error(data.msg);
                            }


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

    <!-- Add this script to your Blade file -->
    <script>
        $(document).ready(function() {
            // Handle the change event of the section_id select box
            $('#section_id').on('change', function() {
                // Fetch the selected values
                var academicYear = $('#academic_year').val();
                var versionId = $('#version_id').val();
                var classId = $('#class_id').val();
                var sectionId = $(this).val();

                // Make an Ajax request to fetch students based on the selected values

                $.ajax({
                    url: '{{ route('admin.fetchStudents') }}', // Replace with your actual route
                    method: 'POST',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'academic_year': academicYear,
                        'version_id': versionId,
                        'class_id': classId,
                        'section_id': sectionId,
                    },
                    success: function(response) {
                        var sectionDropdown = $('.student_id');
                        sectionDropdown
                            .empty(); // Clear existing options before adding new ones

                        // Enable the dropdown
                        sectionDropdown.prop('disabled', false);
                        $('.student_id').html(
                            '<option value="">-- Select All Student --</option>');

                        // Add options to the dropdown
                        $.each(response.students, function(key, value) {
                            sectionDropdown.append($('<option>', {
                                value: value.std_id,
                                text: value.std_name + ' (' + value.std_id +
                                    ')'
                            }));
                        });
                    },
                    error: function(error) {
                        console.error('Error fetching students:', error);
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endpush
