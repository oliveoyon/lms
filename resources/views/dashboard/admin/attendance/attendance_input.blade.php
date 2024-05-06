@extends('dashboard.admin.layouts.admin-layout-with-cdn')
@section('title', 'Attendance')
@push('admincss')
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.2.0/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<!-- Add your custom CSS here -->


@endpush

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('language.attendance_mgmt') }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ __('language.attendance_mgmt') }}</a></li>
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



                        <form action="{{ route('admin.addAttendance') }}" method="POST" autocomplete="off" id="add-attendance-form">
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
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="academicYear" class="required">{{ __('language.academic_year') }}:</label>
                                                <select class="form-control form-control-sm academic_year" name="academic_year" id="academic_year">
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
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="versionName" class="required">{{ __('language.version_name') }}:</label>
                                                <select class="form-control form-control-sm version_id" name="version_id" id="version_id">
                                                    <option value="">{{ __('language.select_version') }}</option>
                                                    @foreach ($versions as $version)
                                                        <option value="{{ $version->id }}">{{ $version->version_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="className" class="required">{{ __('language.class_name') }}:</label>
                                                <select class="form-control form-control-sm class_id" name="class_id" id="class_id" disabled>
                                                    <option value="">{{ __('language.select_class') }}</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="section_id" class="required">{{ __('language.section_name') }}:</label>
                                                <select id="section_id" name="section_id" class="form-control form-control-sm section_id" disabled>
                                                    <option value="">{{ __('language.select_section') }}</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="attendance_date" class="required">{{ __('language.attendance_date') }}:</label>
                                                <input type="date" id="attendance_date" name="attendance_date" class="form-control form-control-sm" required value="{{ now()->toDateString() }}">
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>

                            <div id="records"></div>



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


    $('#add-attendance-form').on('submit', function (e) {
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
            beforeSend: function () {
                $(form).find('span.error-text').text('');
                // Remove 'is-invalid' class and error messages on form submission
                $(form).find('.form-control').removeClass('is-invalid');
                $(form).find('.invalid-feedback').text('');
            },
            success: function (data) {
                if (data.code == 0) {
                    $.each(data.error, function (field, messages) {
                        // Add 'is-invalid' class to the input field
                        $(form).find('[name="' + field + '"]').addClass('is-invalid');
                        // Display the first error message for the field
                        $(form).find('span.' + field + '_error').text(messages[0]);

                        // Show Toastr error message


                    });
                    if (data.msg) {
                            toastr.error(data.msg);
                        }


                } else {
                    var redirectUrl = data.redirect;
                    toastr.success(data.msg);

                    setTimeout(function () {
                        window.location.href = redirectUrl;
                    }, 1000);
                }
            },
            complete: function () {
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
$(document).ready(function () {
    // Handle the change event of the section_id select box
    $('#section_id').on('change', function () {
        // Fetch the selected values
        var academicYear = $('#academic_year').val();
        var versionId = $('#version_id').val();
        var classId = $('#class_id').val();
        var sectionId = $(this).val();

        // Make an Ajax request to fetch students based on the selected values
        $.ajax({
            url: '{{ route("admin.fetchStudents") }}', // Replace with your actual route
            method: 'POST',
            data: {
                '_token': '{{ csrf_token() }}',
                'academic_year': academicYear,
                'version_id': versionId,
                'class_id': classId,
                'section_id': sectionId,
            },
            success: function (response) {
                // Clear previous content
                $('#records').html('');

                // Create a card for attendance information with dynamic heading
                var cardHtml = '<div class="card">';
                cardHtml += '<div class="card-header">';
                cardHtml += '<h5 class="mb-0">{{ __('language.attendance_info') }}</h5>';
                cardHtml += '</div>';
                cardHtml += '<div class="card-body">';

                // Class Name
                cardHtml += '<p class="card-text mb-2"><strong>{{ __('language.class_name') }}:</strong> ' + response.classData.class_name + '</p>';

                // Section Name
                cardHtml += '<p class="card-text mb-2"><strong>{{ __('language.section_name') }}:</strong> ' + response.sectionData.section_name + '</p>';

                // Academic Year
                cardHtml += '<p class="card-text mb-2"><strong>{{ __('language.academic_year') }}:</strong> ' + academicYear + '</p>';

                // Current Date
                cardHtml += '<p class="card-text mb-2"><strong>{{ __('language.date') }}:</strong> ' + response.currentDate + '</p>';

                cardHtml += '<table class="table">';
                cardHtml += '<thead><tr><th>{{ __('language.student_id') }}</th><th>{{ __('language.student_name') }}</th><th>{{ __('language.action') }}</th></tr></thead>';
                cardHtml += '<tbody>';

                // Populate rows with student data
                $.each(response.students, function (index, std) {
                    cardHtml += '<tr>';
                    cardHtml += '<td>' + std.std_id + '</td>';
                    cardHtml += '<td>' + std.std_name + '</td>';
                    cardHtml += '<td>';
                    cardHtml += '<input type="radio" name="action_' + std.std_id + '" value="Absent" checked> Absent ';
                    cardHtml += '<input type="radio" name="action_' + std.std_id + '" value="Present"> Present ';
                    cardHtml += '<input type="radio" name="action_' + std.std_id + '" value="Late"> Late ';
                    cardHtml += '</td>';
                    cardHtml += '</tr>';
                });

                cardHtml += '</tbody>';
                cardHtml += '</table>';
                cardHtml += '<div class="mt-3">';
                cardHtml += '<button type="button" class="btn btn-danger" onclick="toggleAllRadio(\'Absent\')">{{ __('language.chk_absent') }}</button>';
                cardHtml += '<button type="button" class="btn btn-success ml-2" onclick="toggleAllRadio(\'Present\')">{{ __('language.chk_present') }}</button>';
                cardHtml += '</div>';
                cardHtml += '</div></div>';

                // Append the card to the #records div
                $('#records').append(cardHtml);
            },
            error: function (error) {
                console.error('Error fetching students:', error);
            }
        });
    });
});

// Function to toggle all radio buttons for a specific value
function toggleAllRadio(value) {
    $('input[type="radio"][value="' + value + '"]').prop('checked', true);
}

</script>





@endpush
