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
                    <form action="{{ route('admin.class_summery') }}" method="POST" autocomplete="off" id="get-version-wise-class-list">
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
                                            <select class="form-control form-control-sm academic_year" name="academic_year" id="academic_year">
                                                <option value="">{{ __('language.academic_year') }}</option>
                                                @php
                                                $currentYear = date('Y');
                                                @endphp
                                                @for ($i = $currentYear - 10; $i <= $currentYear + 10; $i++) <option value="{{ $i }}">
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
                                                <option value="{{ $version->id }}">{{ $version->version_name }}
                                                </option>
                                                @endforeach
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
                                <table class="table table-bordered table-striped table-hover table-sm" id="class-table">
                                    <thead style="border-top: 1px solid #b4b4b4">
                                        <th style="width: 10px">#</th>
                                        <th>{{ __('language.academic_year') }}</th>
                                        <th>{{ __('language.class_name') }}</th>
                                        <th>{{ __('language.section_name') }}</th>
                                        <th>Class Teacher</th>
                                        <th>{{ __('language.version') }}</th>
                                        <th>Maximum Students</th>
                                        <th>Total Students</th>
                                    </thead>
                                    <tbody id="result-table-body">
                                        <!-- Rows will be dynamically populated here -->
                                    </tbody>
                                </table>
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

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });



    $(document).ready(function() {

        $('#get-version-wise-class-list').submit(function(e) {
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
                success: function(response) {
                    var tableBody = $('#result-table-body');
                    tableBody.empty(); // Clear existing rows

                    // Assuming response.classes contains the Laravel collection of classes
                    var classes = response.classes;

                    if (classes instanceof Array || classes instanceof Object) {
                        // Keep track of the serial number
                        var serialNumber = 1;

                        // Loop through the classes and append rows to the table
                        for (var i in classes) {
                            if (classes.hasOwnProperty(i)) {
                                var classData = classes[i];
                                var row = '<tr>' +
                                    '<td>' + serialNumber + '</td>' +
                                    '<td>' + classData.academic_year + '</td>' +
                                    '<td>' + classData.class_name + '</td>' +
                                    '<td>' + classData.section_name + '</td>' +
                                    '<td>' + (classData.class_teacher_name ? classData.class_teacher_name : '') + '</td>' +
                                    '<td>' + classData.version_name + '</td>' +
                                    '<td>' + classData.max_students + '</td>' +
                                    '<td>' + classData.current_students + '</td>' +
                                    '</tr>';
                                tableBody.append(row);

                                // Increment the serial number for the next iteration
                                serialNumber++;
                            }
                        }

                        // Show the entire content (assuming its class is 'content')
                        $('.content').removeClass('d-none');
                    } else {
                        console.error("Classes is not a valid collection:", classes);
                    }
                },
                error: function(error) {
                    // Handle errors if needed
                    console.log(error);
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
                    modalContent += '<h5 class="modal-title" id="pdfModalLabel">Generated Report</h5>';
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