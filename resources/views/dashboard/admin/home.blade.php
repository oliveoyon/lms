@extends('dashboard.admin.layouts.admin-layout-with-cdn')
@section('title', 'Dashboard')
@push('admincss')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" />
<style>
    .notice_title {
        font-weight: bold;
    }

    .notice-lists {
        background-color: #334d37;
        color: #FFFFFF;
        padding: 5px;
        display: flex;
    }

    .left-bar {
        background-color: #3498db;
        color: #fff;
        padding: 5px;
        border-radius: 5px;
        flex-shrink: 0;
        margin-right: 3px;
    }

    .marquee-container {
        flex-grow: 1;
        overflow: hidden;
        min-width: 0;
    }

    .marquee-content {
        display: flex;
    }

    .notice-items {
        font-size: 16px;
        padding: 5px;
        margin-right: 50px;
        text-decoration: none;
        color: #FFFFFF;

    }

    .notice-items:hover {
        color: #F4A623;
        font-weight: bold;
        text-decoration: none;
    }

    .info-box-link {
        text-decoration: none;
        color: inherit;
        /* Remove underline for the link */
    }

    .info-box:hover .info-box-content {
        background-color: #f4f4f4;
        /* Change background color on hover */
        cursor: pointer;
        /* Show hand cursor on hover */
    }
</style>
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
            <div class="notice-lists">
                <div class="left-bar">
                    <span class="notice-icon"><i class="fas fa-exclamation-circle"></i></span>
                    <div>
                        <span id="content"></span>
                    </div>
                </div>

                <div class="marquee-container">
                    <div class="marquee-content">
                        <marquee behavior="scroll" direction="left" onmouseover="this.stop();" onmouseout="this.start();">
                            <a href="https://example.com/notice/1" class="notice-items">Important Announcement for Class 6-10</a>
                            <a href="https://example.com/notice/2" class="notice-items">Meet the New School Principal - John Doe</a>
                            <a href="https://example.com/notice/3" class="notice-items">Upcoming Events and Activities</a>
                            <a href="https://example.com/notice/4" class="notice-items">Reminder: Parent-Teacher Meeting on February 15</a>
                            <a href="https://example.com/notice/5" class="notice-items">School Holiday Schedule for the Month</a>
                        </marquee>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row mt-1">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>150</h3>
                            <p>Total Students</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">

                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>53<sup style="font-size: 20px">%</sup></h3>
                            <p>Bounce Rate</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">

                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>44</h3>
                            <p>Fee Collection Today</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-coins"></i>

                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">

                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>65</h3>
                            <p>Due Amount</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-hand-holding-usd"></i>

                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-body">
                            <div id="fullCalendar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('admin.stdlist') }}" class="info-box-link">
                        <div class="info-box">
                            <span class="info-box-icon bg-success"><i class="fas fa-user-graduate"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Students</span>
                                <span class="info-box-number">{{ $data->total_students }}</span>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('admin.teacher-list') }}" class="info-box-link">
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="fas fa-chalkboard-teacher"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Teachers</span>
                                <span class="info-box-number">{{ $data->total_teachers }}</span>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('admin.book-list') }}" class="info-box-link">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning"><i class="fas fa-book"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Books</span>
                                <span class="info-box-number">{{ $data->total_books }}</span>
                            </div>
                        </div>
                    </a>

                    <a href="#" class="info-box-link">
                        <div class="info-box">
                            <span class="info-box-icon bg-danger"><i class="fas fa-bus"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Students<br>Using Transport</span>
                                <span class="info-box-number">{{ $data->total_assigned_students }}</span>
                            </div>
                        </div>
                    </a>

                    <a href="#" class="info-box-link">
                        <div class="info-box">
                            <span class="info-box-icon bg-purple"><i class="fas fa-bed"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Students <br> in Dormitory</span>
                                <span class="info-box-number">{{ $data->total_students }}</span>
                            </div>
                        </div>
                    </a>

                    <a href="#" class="info-box-link">
                        <div class="info-box">
                            <span class="info-box-icon bg-navy"><i class="fas fa-flag"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Your Text Here</span>
                                <span class="info-box-number">{{ $data->total_students }}</span>
                            </div>
                        </div>
                    </a>


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
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- FullCalendar JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>

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
{{-- <script>
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
                // Your code to update modal content based on the month, stdId, and academicYear
                var content = '<p>Student ID: ' + stdId + '</p>';
                content += '<p>Month: ' + month + '</p>';
                content += '<p>Academic Year: ' + academicYear + '</p>';
                // Add more content as needed

                // Set the content in the modal
                $('#modal-content').html(content);
            }
        });
    </script> --}}

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
                url: "{{ route('admin.fetchColletcData') }}", // Replace with your actual route
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

<?php
// Sample PHP dataset array
$events = [
    [
        'title' => 'Event 1',
        'start' => '2024-02-15T10:00:00',
        'end' => '2024-02-15T12:00:00',
        'url' => 'https://example.com/event1',
        'color' => '#FF5733', // Hex color code
    ],
    [
        'title' => 'Event 2',
        'start' => '2024-02-16T14:00:00',
        'end' => '2024-02-16T16:00:00',
        'url' => 'https://example.com/event2',
        'color' => '#33FF57', // Hex color code
    ],
    // Add more events as needed
];
?>
<script>
    $(document).ready(function() {
        // Use PHP dataset array
        var events = <?php echo $eventsJson; ?>;

        // Initialize FullCalendar
        $('#fullCalendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay',
            },
            events: events,
            eventRender: function(event, element) {
                element.attr('title', event.title);
            },
        });
    });
</script>
@endpush