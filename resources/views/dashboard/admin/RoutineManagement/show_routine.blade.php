@extends('dashboard.admin.layouts.admin-layout-with-cdn')
@section('title', 'Show Routine')

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
                        <h1 class="m-0">{{ __('language.class_mgmt') }}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a
                                    href="{{ route('admin.home') }}">{{ __('language.class_mgmt') }}</a></li>
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
                                    {{ __('language.class_list') }}
                                </h3>
                                <div class="card-tools">
                                    <ul class="nav nav-pills ml-auto">
                                        <li class="nav-item">

                                            <a href="{{ route('admin.createClassRoutine') }}" class="btn btn-success btn-m">Add Class Period</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body table-responsive">
                                <div class="alert alert-danger" id="errorAlert" style="display: none;">
                                    <ul id="errorList">
                                        <!-- Error messages will be inserted here dynamically -->
                                    </ul>
                                </div>
                            
                                <table class="table table-bordered table-striped table-hover table-sm" id="class-routines-table">
                                    <thead style="border-top: 1px solid #b4b4b4">
                                        <th style="width: 10px">#</th>
                                        <th>{{ __('language.version') }}</th>
                                        <th>{{ __('language.academic_year') }}</th>
                                        <th>{{ __('language.class_name') }}</th>
                                        <th>{{ __('language.section_name') }}</th>
                                        <th>{{ __('language.action') }}</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($classRoutines as $classRoutine)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $classRoutine->version->version_name }}</td>
                                                <td>{{ $classRoutine->academic_year }}</td>
                                                <td>{{ $classRoutine->eduClass->class_name }}</td>
                                                <td>{{ $classRoutine->section->section_name }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-warning btn-xs" data-id="{{ $classRoutine->id }}"
                                                        id="viewClassRoutineDetailsBtn"><i class="fas fa-edit"></i>
                                                        </button>
                                                    
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                        </div>
                    </div>

                </div>

                <!-- View Class Routine Details Modal -->
{{-- <div class="modal fade editPeriod" id="viewClassRoutineDetailsModal" tabindex="-1" aria-labelledby="viewClassRoutineDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="viewClassRoutineDetailsModalLabel">{{ __('language.view_details') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.updateClassDetails') }}" method="post" autocomplete="off" id="update-classPeriod-form">
                    @csrf
                    
                </form>
            </div>
        </div>
    </div>
</div> --}}

<div class="modal fade editPeriod" id="viewClassRoutineDetailsModal" tabindex="-1" aria-labelledby="viewClassRoutineDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="viewClassRoutineDetailsModalLabel">{{ __('language.view_details') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.updateClassPeriodDetails') }}" method="post" autocomplete="off" id="update-classPeriod-form">
                    @csrf

                    <!-- Add a hidden input for class_id if needed -->
                    <input type="hidden" name="cid" value="">

                    <!-- Add an editable table for periods -->
                    <table class="table table-bordered" id="periodTable">
                        <thead>
                            <tr>
                                <th>Period Name</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be dynamically populated here -->
                        </tbody>
                    </table>

                    <!-- Button to add a new row -->
                    <button type="button" class="btn btn-success" id="addRowBtn">Add Row</button>

                    <!-- Button to submit the form -->
                    <button type="submit" class="btn btn-primary">Save Changes</button>
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
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            // Image preview
            $("#upload").change(function() {
                readURL(this);
            });

            // Modal show with data
            $(document).on('click', '#viewClassRoutineDetailsBtn', function() {
    var class_id = $(this).data('id');
    $('.editPeriod').find('form')[0].reset();
    $('.editPeriod').find('span.error-text').text('');
    $('.editPeriod').find('input[name="cid"]').val(class_id);
    
    // Show the loader overlay
    $('#loader-overlay').show();

    $.post("{{ route('admin.getPeriods') }}", {
        class_id: class_id
    }, function(data) {
        // Set the class_id in the hidden input
        $('.editPeriod').find('#class_id').val(class_id);

        // Assuming data.periods is an array containing period information
        var periods = data.periods;

        // Assuming the table body has id 'periodTableBody'
        var tableBody = $('.editPeriod').find('#periodTable tbody');

        // Clear existing rows
        tableBody.empty();

        // Populate table rows with data
        for (var i = 0; i < periods.length; i++) {
            var rowHtml = '<tr>' +
                '<td><input type="text" name="period_name[]" class="form-control" value="' + periods[i].name + '"></td>' +
                '<td><input type="time" name="start_time[]" class="form-control" value="' + periods[i].start_time + '"></td>' +
                '<td><input type="time" name="end_time[]" class="form-control" value="' + periods[i].end_time + '"></td>' +
                '<td><button type="button" class="btn btn-danger btn-sm deleteRowBtn">Delete</button></td>' +
                '</tr>';
            tableBody.append(rowHtml);
        }

        $('.editPeriod').modal('show');
    }, 'json')
    .always(function() {
        // Hide the loader overlay after completion
        $('#loader-overlay').hide();
    });
});


            // Handle adding a new row
            $(document).on('click', '#addRowBtn', function() {
                var newRowHtml = '<tr>' +
                    '<td><input type="text" name="period_name[]" class="form-control"></td>' +
                    '<td><input type="time" name="start_time[]" class="form-control"></td>' +
                    '<td><input type="time" name="end_time[]" class="form-control"></td>' +
                    '<td><button type="button" class="btn btn-danger btn-sm deleteRowBtn">Delete</button></td>' +
                    '</tr>';
                $('.editPeriod').find('#periodTable tbody').append(newRowHtml);
            });

            // Handle deleting a row
            $(document).on('click', '.deleteRowBtn', function() {
                $(this).closest('tr').remove();
            });


// Update Class Period Record
$('#update-classPeriod-form').on('submit', function(e) {
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
            // Clear previous error messages and remove 'is-invalid' class
            $(form).find('.is-invalid').removeClass('is-invalid');
            $(form).find('span.error-text').text('');
        },
        success: function(data) {
            if (data.code == 0) {
                // Display validation errors and add 'is-invalid' class to corresponding fields
                $.each(data.error, function(prefix, val) {
                    $(form).find('[name="' + prefix + '"]').addClass('is-invalid');
                    $(form).find('span.' + prefix + '_error').text(val[0]);
                    toastr.error(val[0]); // Display error in Toastr
                });
            } else {
                // Hide the modal and reset the form on success
                $('.editPeriod').modal('hide');
                
                // Display success message using Toastr
                toastr.success(data.msg);
                setTimeout(function() {
                    window.location.href = data.redirect;
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



            
            
          
        });
    </script>
@endpush
