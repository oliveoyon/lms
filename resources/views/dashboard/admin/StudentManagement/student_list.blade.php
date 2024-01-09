@extends('dashboard.admin.layouts.admin-layout-with-cdn')
@section('title', 'Students List')
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
                    <h1 class="m-0">{{ __('language.student_list') }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ __('language.fee_head') }}</a></li>
                        <li class="breadcrumb-item">{{ __('language.student_list') }}</li>
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


                        <form action="{{ route('admin.getstdlist') }}" method="POST"  autocomplete="off" id="get-student-list">
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
                                    <div class="row align-items-end">
                                        <div class="col-md-2">
                                            <div class="form-group">
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
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <select class="form-control form-control-sm version_id" name="version_id" id="version_id">
                                                    <option value="">{{ __('language.select_version') }}</option>
                                                    @foreach ($versions as $version)
                                                        <option value="{{ $version->id }}">{{ $version->version_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <select class="form-control form-control-sm class_id" name="class_id" id="class_id" disabled>
                                                    <option value="">{{ __('language.select_class') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <select id="section_id" name="section_id" class="form-control form-control-sm section_id" disabled>
                                                    <option value="">{{ __('language.select_section') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <select id="studentCategory" name="std_category" class="form-control form-control-sm">
                                                    <option value="">{{ __('language.std_category') }}</option>
                                                    <option value="Regular">Regular</option>
                                                    <option value="Transferred">Transferred</option>
                                                    <option value="Interchanged">Interchanged</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary btn-sm btn-block">{{ __('language.submit') }}</button>
                                            </div>
                                        </div>
                                    </div>



                                </div>
                            </div>



                        </form>


                </div>
            </div>



        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <!-- Card (Initially hidden with "d-none" class) -->
                    <div class="card d-none" id="student-card">
                        <div class="card-header bg-info">
                            <h3 class="card-title">Student Data</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table id="student-table" class="table" style="display: none;">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('language.student_id') }}</th>
                                    <th>{{ __('language.student_full_name') }}</th>
                                    <th>{{ __('language.student_full_name_bangla') }}</th>
                                    <th>{{ __('language.version_name') }}</th>
                                    <th>{{ __('language.class_name') }}</th>
                                    <th>{{ __('language.section_name') }}</th>
                                    <th>{{ __('language.action') }}</th>
                                </tr>
                                </thead>
                                <tbody id="student-table-body">
                                <!-- Table rows will be dynamically populated here -->
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
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


    $('#get-student-list').on('submit', function (e) {
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
            // Clear any previous error messages
            $(form).find('span.error-text').text('');
        },
        success: function (data) {
    // Clear existing table rows
    $('#student-table-body').empty();

    if (data.students && data.students.length > 0) {
        // Assuming data.students contains the array of student objects from the controller
        var students = data.students;

        // Loop through the student data and append rows to the table
        $.each(students, function (index, student) {
            // Use index + 1 as serial number
            var serialNumber = index + 1;

            var row = '<tr>' +
                '<td>' + serialNumber + '</td>' +
                '<td>' + student.std_id + '</td>' +
                '<td>' + student.std_name + '</td>' +
                '<td>' + student.std_name_bn + '</td>' +
                '<td>' + student.version_name + '</td>' +
                '<td>' + student.class_name + '</td>' +
                '<td>' + student.section_name + '</td>' +
                '<td>' +
                    '<div class="dropdown">' +
                        '<button class="btn bg-purple btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                            '<i class="fas fa-cogs"></i>' +
                        '</button>' +
                        '<div class="dropdown-menu dropdown-menu-right">' +
                            '<a class="dropdown-item" href="/admin/edit-student/' + student.std_hash_id + '"><i class="fas fa-edit"></i> Edit</a>' +
                            '<a class="dropdown-item" href="#"><i class="fas fa-trash-alt"></i> Delete</a>' +
                            '<a class="dropdown-item" href="/admin/student-profile/' + student.std_hash_id + '"><i class="fas fa-user"></i> Visit Profile</a>' +
                            '<a class="dropdown-item" href="#"><i class="fas fa-poll"></i> Get Result</a>' +
                            '<a class="dropdown-item" href="/admin/collect-fees/' + student.std_hash_id + '"><i class="fas fa-money-bill"></i> Collect Fee</a>' +
                            <!-- Add more actions as needed -->
                        '</div>' +
                    '</div>' +
                '</td>' +
                '</tr>';
            $('#student-table-body').append(row);
        });

        // Show the card and table
        $('#student-card').removeClass('d-none');
        $('#student-table').show();
    } else {
        // Display a message within a row with colspan
        var noDataRow = '<tr><td colspan="8">No students found.</td></tr>';
        $('#student-table-body').append(noDataRow);
        // Hide the card and table
        // $('#student-card').addClass('d-none');
        $('#student-card').removeClass('d-none');
        $('#student-table').show();
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


@endpush
