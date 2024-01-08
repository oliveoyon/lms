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
                    </div>
                </div>



            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3">

                        <!-- Profile Image -->
                        <div class="card card-primary card-outline">
                            <div class="card-body box-profile">
                                <div class="text-center">

                                    @if ($student->std_picture)
                                        <img class="profile-user-img img-responsive img-fluid img-circle" src="{{ asset('storage/img/std_img/'.$student->std_picture) }}" alt="Student Image">
                                    @else
                                        @if ($student->std_gender == 'male')
                                            <img class="profile-user-img img-responsive img-fluid img-circle" src="{{ asset('storage/img/std_img/male.jpg') }}" alt="Male Default Image">
                                        @else
                                            <img class="profile-user-img img-responsive img-fluid img-circle" src="{{ asset('storage/img/std_img/female.jpg') }}" alt="Female Default Image">
                                        @endif
                                    @endif



                                </div>

                                <h3 class="profile-username text-center">{{ $student->std_name }}</h3>

                                <p class="text-muted text-center">Student ID: <strong>{{ $student->std_id }}</strong></p>

                                <ul class="list-group list-group-unbordered mb-3">
                                    <li class="list-group-item">
                                        <b>Version Name</b> <a class="float-right">{{ $student->version_name }}</a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Class Name</b> <a class="float-right">{{ $student->class_name }}</a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Section Name</b> <a class="float-right">{{ $student->section_name }}</a>
                                    </li>
                                </ul>

                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->

                        <!-- About Me Box -->
                        <div class="card card-purple">
                            <div class="card-header">
                                <h3 class="card-title">About Student</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <strong><i class="fas fa-user mr-1"></i> Father's Name</strong>
                                <p class="text-muted">
                                    {{ $student->std_fname }} </p>
                                <strong><i class="fas fa-user mr-1"></i> Mother's Name</strong>
                                <p class="text-muted">
                                    {{ $student->std_mname }} </p>
                                <strong><i class="fas fa-phone mr-1"></i> Phone No.</strong>
                                <p class="text-muted">
                                    {{ $student->std_phone }} &nbsp;&nbsp;&nbsp; </p>

                                <strong><i class="fas fa-map-marker-alt mr-1"></i> Present Address</strong>

                                <p class="text-muted">
                                <p>{{ $student->std_present_address }}</p>
                                </p>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-9">
                        <div class="card">
                            <div class="card-header p-2">
                                <ul class="nav nav-pills">
                                    <li class="nav-item"><a class="nav-link active" href="#Attendence"
                                            data-toggle="tab">Attendence</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#Fees" data-toggle="tab">Fees</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#Result" data-toggle="tab">Result</a>
                                    </li>
                                </ul>
                            </div><!-- /.card-header -->
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="active tab-pane" id="Attendence">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div style="width: 95%; margin: auto;">
                                                    <canvas id="attendanceChart"></canvas>
                                                </div>
                                            </div>
                                            <div class="col-md-8 float-right">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Month</th>
                                                            <th>Present</th>
                                                            <th>Absent</th>
                                                            <th>Late</th>
                                                            <th>Total Days</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($attendanceData as $index => $data)
                                                            <tr>
                                                                <td>{{ $index + 1 }}</td>
                                                                <td>{{ date('F', mktime(0, 0, 0, $data->month, 1)) }}</td>
                                                                <td>{{ $data->present_count }}</td>
                                                                <td>{{ $data->absent_count }}</td>
                                                                <td>{{ $data->late_count }}</td>
                                                                <td>{{ $data->total_days }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- /.tab-pane -->
                                    <!-- /.tab-pane -->
                                    <div class="tab-pane table-responsive" id="Fees">
                                        <table class="table">
                                            <tr>
                                                <th>Student ID</th>
                                                <th>Student Name</th>
                                                <th>Due Amount</th>
                                                <th>Action</th>
                                            </tr>
                                            <tr>
                                                <td>{{ $totalDues->std_id }}</td>
                                                <td>{{ $totalDues->std_name }}</td>
                                                <td style="color:red; font-weight:bolder">{{ $totalDues->total_due_amount }}</td>
                                                <td>
                                                    <a class="btn-sm btn-success" onclick="toggleDetails('{{ $totalDues->std_id }}')">Details</a>
                                                </td>
                                            </tr>
                                        </table>
                                        <div id="detailsDiv{{ $totalDues->std_id }}" style="display: none;">
                                            <!-- Inside your Blade view -->
                                            @foreach ($detailedDues as $data)
                                            @php
                                                $month = date('F', strtotime($data->due_date));
                                            @endphp

                                            @if (!isset($groupedData[$month]))
                                                <!-- Create a new group for the month if it doesn't exist -->
                                                @php
                                                    $groupedData[$month] = [];
                                                @endphp
                                            @endif

                                            <!-- Add the data to the corresponding month group -->
                                            @php
                                                $groupedData[$month][] = $data;
                                            @endphp
                                            @endforeach

                                            <!-- Render the organized data -->
                                            @foreach ($groupedData as $month => $monthData)
                                            <h4>{{ $month.' '.date('Y', strtotime($data->due_date)) }}</h4>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Payable Amount</th>
                                                        <th>Total Amount Paid</th>
                                                        <th>Total Due</th>
                                                        <th>Due Date</th>
                                                        <th>Fee Description</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($monthData as $rowData)
                                                        <tr>
                                                            <td>{{ $rowData->payable_amount }}</td>
                                                            <td>{{ $rowData->total_amount_paid }}</td>
                                                            <td>{{ ($rowData->payable_amount - $rowData->total_amount_paid) }}</td>
                                                            <td>{{ $rowData->due_date }}</td>
                                                            <td>{{ $rowData->fee_description }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            @endforeach

                                        </div>
                                    </div>
                                    <!-- /.tab-pane -->

                                    <div class="tab-pane" id="Result">


                                        <table class="table table-bordered table-striped">
                                            <thead>

                                                <tr>
                                                    <th>Exam Name</th>
                                                    <th>Total Obtain</th>
                                                    <th>Mark %</th>
                                                    <th>Grade</th>
                                                    <th></th>
                                                </tr>

                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>

                                    </div>
                                    <!-- /.tab-pane -->
                                </div>
                                <!-- /.tab-content -->
                            </div><!-- /.card-body -->
                        </div>
                        <!-- /.nav-tabs-custom -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
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
    </script>

    <script>
        // Sample data (replace this with your actual data)
        var attendanceData = <?php echo json_encode($attendanceData); ?>;
        var totalPresent = <?php echo $totalPresent; ?>;
        var totalAbsent = <?php echo $totalAbsent; ?>;
        var totalLate = <?php echo $totalLate; ?>;

        // Get the canvas element
        var ctx = document.getElementById('attendanceChart').getContext('2d');

        // Create the pie chart
        var myPieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Absent', 'Present', 'Late'],
                datasets: [{
                    data: [totalAbsent, totalPresent, totalLate],
                    backgroundColor: ['red', 'green', 'orange'],
                }]
            },
        });
    </script>

    <script>
    function toggleDetails(stdId) {
        // Get the details div element
        var detailsDiv = document.getElementById('detailsDiv' + stdId);

        // Toggle the visibility of the details div
        if (detailsDiv.style.display === 'none') {
            detailsDiv.style.display = 'block';
        } else {
            detailsDiv.style.display = 'none';
        }
    }
</script>
@endpush
