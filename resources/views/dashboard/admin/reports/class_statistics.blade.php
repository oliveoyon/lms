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

        /* Style for table titles */
        h2 {
            color: #333;
            margin-top: 10px;
        }

        /* Style for tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        thead th,
        tbody td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        thead th {
            background-color: #f2f2f2;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
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
                            <li class="breadcrumb-item"><a
                                    href="{{ route('admin.home') }}">{{ __('language.fee_head') }}</a></li>
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
                        <form action="{{ route('admin.class_statistics') }}" method="POST" autocomplete="off"
                            id="get-version-wise-class-list">
                            @csrf
                            <div class="card">
                                <div class="card-header bg-gray">
                                    <h3 class="card-title">{{ __('language.academic_details') }}</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row align-items-end">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <select class="form-control form-control-sm academic_year"
                                                    name="academic_year" id="academic_year">
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
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <select class="form-control form-control-sm class_id" name="class_id"
                                                    id="class_id" disabled>
                                                    <option value="">{{ __('language.select_class') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <button type="submit"
                                                    class="btn btn-primary btn-sm btn-block">{{ __('language.submit') }}</button>
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

        <div class="content d-none">
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
                                            <button class="btn btn-success btn-sm" id="printButton">
                                                <i class="fas fa-plus-square mr-1"></i>
                                                {{ __('language.print_report') }}
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body table-responsive">
                                <div class="alert alert-danger" id="errorAlert" style="display: none;">
                                    <ul id="errorList"></ul>
                                </div>
                                <div id="reportDiv">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </div>





    </div>
@endsection

@push('adminjs')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.2.0/dist/sweetalert2.min.js"></script>
    <!-- Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });



        $(document).ready(function() {

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




        });
    </script>

<script>
    $(document).ready(function () {
        $('#get-version-wise-class-list').submit(function (e) {
            e.preventDefault();

            // Disable the submit button to prevent double-clicking
            $(this).find(':submit').prop('disabled', true);

            // Show the loader overlay
            $('#loader-overlay').show();

            var form = this;

            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: $(form).serialize(),
                success: function (response) {
                    // Clear existing content in reportDiv
                    $('#reportDiv').empty();

                    // Create and append Attendance Stats Table
                    createTable('Attendance Stats', 'attendanceStatsTable', response.attendanceStats, [
                        { label: 'Gender', key: 'std_gender' },
                        { label: 'Category', key: 'std_category' },
                        { label: 'Total Students', key: 'total_students' },
                        { label: 'Present Students', key: 'present_students' },
                        { label: 'Absent Students', key: 'absent_students' },
                        { label: 'Latecomers', key: 'latecomers' }
                    ]);

                    // Create and append Blood Group Stats Table
                    createTable('Blood Group Stats', 'bloodGroupStatsTable', response.bloodGroupStats, [
                        { label: 'Blood Group', key: 'blood_group_category' },
                        { label: 'Count', key: 'count' }
                    ], true);

                    // Show the entire content (assuming its class is 'content')
                    $('.content').removeClass('d-none');
                },
                error: function (error) {
                    // Handle errors if needed
                    console.log(error);
                },
                complete: function () {
                    // Enable the submit button and hide the loader overlay
                    $(form).find(':submit').prop('disabled', false);
                    $('#loader-overlay').hide();
                }
            });
        });

        function createTable(tableTitle, tableId, data, columns, isBloodGroupStats) {
            // Create a table with a title
            var table = '<h2 style="margin-top:20px;">' + tableTitle + '</h2>' +
                '<table id="' + tableId + '">' +
                '<thead>' +
                '<tr>' +
                columns.map(col => '<th>' + col.label + '</th>').join('') +
                '</tr>' +
                '</thead>' +
                '<tbody id="' + tableId + 'Body">' +
                '</tbody>' +
                '</table>';

            // Append the table to reportDiv
            $('#reportDiv').append(table);

            // Populate the table body
            populateTableBody(tableId, data, columns, isBloodGroupStats);
        }

        function populateTableBody(tableId, data, columns, isBloodGroupStats) {
            var tableBody = $('#' + tableId + 'Body');
            tableBody.empty(); // Clear existing rows

            // Loop through the data and append rows to the table
            for (var i in data) {
                if (data.hasOwnProperty(i)) {
                    var rowData = isBloodGroupStats ? { blood_group_category: i, count: data[i] } : data[i];
                    var row = '<tr>' +
                        columns.map(col => '<td>' + (rowData[col.key] ? rowData[col.key] : '') + '</td>').join('') +
                        '</tr>';
                    tableBody.append(row);
                }
            }
        }
    });
</script>



    <script>
        $('#printButton').click(function() {
            var data = $('#reportDiv').html();

            // Show the loader overlay
            $('#loader-overlay').show();

            $.ajax({
                url: '/admin/generate-pdf',
                method: 'POST',
                data: {
                    pdf_data: data,
                    title: 'Class Summery Report',
                    orientation: 'P',
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.pdf_url && isValidUrl(response.pdf_url)) {
                        // Create a modal element
                        var modalContent =
                            '<div class="modal fade modal-fullscreen" id="pdfModal" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel" aria-hidden="true">';
                        modalContent +=
                            '<div class="modal-dialog modal-dialog-centered modal-lg" role="document">';
                        modalContent += '<div class="modal-content">';
                        modalContent += '<div class="modal-header">';
                        modalContent +=
                            '<h5 class="modal-title" id="pdfModalLabel">Generated Report</h5>';
                        modalContent +=
                            '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
                        modalContent += '<span aria-hidden="true">&times;</span>';
                        modalContent += '</button>';
                        modalContent += '</div>';
                        modalContent += '<div class="modal-body">';
                        modalContent +=
                            '<div id="pdfLoaderOverlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.8); display: flex; justify-content: center; align-items: center;">';
                        modalContent += '<img src="/path/to/loader.gif" alt="Loader">';
                        modalContent += '</div>';
                        modalContent += '<iframe id="pdfIframe" src="' + response.pdf_url +
                            '" style="width: 100%; height: 80vh; display: none;"></iframe>';
                        modalContent += '</div>';
                        modalContent += '</div>';
                        modalContent += '</div>';
                        modalContent += '</div>';

                        // Append modal to the body and show it
                        $('body').append(modalContent);
                        $('#pdfModal').modal('show');

                        // Hide the loader overlay when the PDF is loaded
                        $('#pdfIframe').on('load', function() {
                            $('#pdfLoaderOverlay').hide();
                            $('#pdfIframe').show();
                        });

                        console.log('PDF generated successfully');
                    } else {
                        console.error('Invalid PDF response:', response);
                        alert('Error generating PDF. Please try again.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX request failed:', error);
                    alert('Error generating PDF. Please try again.');
                },
                complete: function() {
                    // Hide the loader overlay when the request is complete
                    $('#loader-overlay').hide();
                }
            });
        });

        function isValidUrl(url) {
            // Implement a function to check if the URL is valid based on your requirements
            return /^https?:\/\/.+/.test(url);
        }
    </script>
@endpush
