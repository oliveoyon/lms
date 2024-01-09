@extends('dashboard.admin.layouts.admin-layout-with-cdn')
@section('title', 'Dashboard')
@push('admincss')
@endpush

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Dashboard</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <section class="content">
            <div class="container-fluid">
                <h2 class="text-center">Bill Collection</h2>
                <form action="{{ route('admin.generateBill') }}" method="post" id="bill-search">
                    @csrf
                    <div class="row">
                        <div class="col-md-10 offset-md-1">
                            <div class="row">
                                <div class="col-3">
                                    <div class="form-group">
                                        <label>Academic Year</label>
                                        <select class="select2" style="width: 100%;" name="ac">
                                            <option value="">All Years</option>
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
                                <div class="col-3">
                                    <div class="form-group">
                                        <label>Month (Up To)</label>
                                        <select class="select2" style="width: 100%;" name="mon">
                                            <option value="">Every Month</option>
                                            @php
                                                $currentMonth = date('n');
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

                            </div>
                            <div class="form-group">
                                <div class="input-group input-group-lg">
                                    <input type="search" name="std_id" class="form-control" placeholder="Student ID" value="{{ isset($student_id) ? $student_id : '' }}">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-lg btn-default">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="row mt-3">
                    <div class="col-md-10 offset-md-1">
                        <div class="list-group">
                            <div id="generateBill"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->


    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
        <div class="p-3">
            <h5>Title</h5>
            <p>Sidebar content</p>
        </div>
    </aside>
    <!-- /.control-sidebar -->



@endsection

@push('adminjs')
    <!-- Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"
        integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        toastr.options.preventDuplicates = true;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                minimumResultsForSearch: Infinity
            });


            $('#bill-search').on('submit', function(e) {
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
                        // Clear any previous error messages
                        $(form).find('span.error-text').text('');
                    },
                    success: function(data) {
                        // Clear existing content in the #generateBill container
                        $('#generateBill').html('');

                        // Check if the JSON response contains HTML
                        if (data.html) {
                            // Append the received HTML to the container
                            $('#generateBill').html(data.html);
                        } else {
                            // Handle the case when no HTML is received
                            console.error('No HTML received in the response.');
                        }
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        // Handle AJAX error
                        console.error('Error:', errorThrown);
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
        $(document).ready(function() {
            // Click event listener for dynamically generated buttons
            $(document).on('click', '.open-modal-btn', function() {
                var month = $(this).data('month');
                var stdId = $(this).data('std-id');
                var academicYear = $(this).data('academic-year');

                // Populate modal content based on the clicked button
                populateModalContent(month, stdId, academicYear);

                // Show the modal
                $('#billModal').modal('show');
            });

            // Function to dynamically populate modal content based on the clicked button
            function populateModalContent(month, stdId, academicYear) {
                // Perform an AJAX request to fetch the data for the selected month, stdId, and academicYear
                $.ajax({
                    url: "{{route('admin.fetchColletcData')}}", // Replace with your actual route
                    method: 'POST', // Change the method to POST
                    data: {
                        _token: '{{ csrf_token() }}', // Add CSRF token for POST requests
                        month: month,
                        stdId: stdId,
                        academicYear: academicYear
                    },
                    success: function(data) {
                        // Update modal content with the received data
                        $('#modal-content').html(data.html);
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        console.error('Error:', errorThrown);
                    }
                });
            }
        });
    </script>
@endpush
