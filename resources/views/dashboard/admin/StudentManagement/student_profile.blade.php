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
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ __('language.fee_head') }}</a></li>
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
                    <img class="profile-user-img img-responsive img-fluid img-circle"
                      src="https://edu.iconbangla.net/assets/student_image/male.jpg" alt="User profile picture">

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
                    <li class="nav-item"><a class="nav-link active" href="#Attendence" data-toggle="tab">Attendence</a></li>
                    <li class="nav-item"><a class="nav-link" href="#Fees" data-toggle="tab">Fees</a></li>
                    <li class="nav-item"><a class="nav-link" href="#Result" data-toggle="tab">Result</a></li>
                  </ul>
                </div><!-- /.card-header -->
                <div class="card-body">
                  <div class="tab-content">
                    <div class="active tab-pane" id="Attendence">
                      <div class="col-md-4 float-left">
                        <canvas id="donutChart" style="height:230px; min-height:230px"></canvas>
                      </div>
                      <div class="col-md-8 float-right">
                        <table class="table table-condensed">
                          <tbody>
                            <tr>
                              <th>#</th>
                              <th>Month</th>
                              <th>Present</th>
                              <th>Absent</th>
                              <th>Total Days</th>
                            </tr>
                            <tr>
                              <td>1</td>
                              <td>January</td>
                              <td><span class="badge bg-green">1</span></td>
                              <td><span class="badge bg-red">0</span></td>
                              <td><span class="badge bg-gray">1</span></td>
                            </tr>
                            <tr>
                              <td>2</td>
                              <td>February</td>
                              <td><span class="badge bg-green">0</span></td>
                              <td><span class="badge bg-red">0</span></td>
                              <td><span class="badge bg-gray">0</span></td>
                            </tr>
                            <tr>
                              <td>3</td>
                              <td>March</td>
                              <td><span class="badge bg-green">0</span></td>
                              <td><span class="badge bg-red">0</span></td>
                              <td><span class="badge bg-gray">0</span></td>
                            </tr>
                            <tr>
                              <td>4</td>
                              <td>April</td>
                              <td><span class="badge bg-green">0</span></td>
                              <td><span class="badge bg-red">0</span></td>
                              <td><span class="badge bg-gray">0</span></td>
                            </tr>
                            <tr>
                              <td>5</td>
                              <td>May</td>
                              <td><span class="badge bg-green">0</span></td>
                              <td><span class="badge bg-red">0</span></td>
                              <td><span class="badge bg-gray">0</span></td>
                            </tr>
                            <tr>
                              <td>6</td>
                              <td>June</td>
                              <td><span class="badge bg-green">0</span></td>
                              <td><span class="badge bg-red">0</span></td>
                              <td><span class="badge bg-gray">0</span></td>
                            </tr>
                            <tr>
                              <td>7</td>
                              <td>July</td>
                              <td><span class="badge bg-green">0</span></td>
                              <td><span class="badge bg-red">0</span></td>
                              <td><span class="badge bg-gray">0</span></td>
                            </tr>
                            <tr>
                              <td>8</td>
                              <td>August</td>
                              <td><span class="badge bg-green">0</span></td>
                              <td><span class="badge bg-red">0</span></td>
                              <td><span class="badge bg-gray">0</span></td>
                            </tr>
                            <tr>
                              <td>9</td>
                              <td>September</td>
                              <td><span class="badge bg-green">0</span></td>
                              <td><span class="badge bg-red">0</span></td>
                              <td><span class="badge bg-gray">0</span></td>
                            </tr>
                            <tr>
                              <td>10</td>
                              <td>October</td>
                              <td><span class="badge bg-green">0</span></td>
                              <td><span class="badge bg-red">0</span></td>
                              <td><span class="badge bg-gray">0</span></td>
                            </tr>
                            <tr>
                              <td>11</td>
                              <td>November</td>
                              <td><span class="badge bg-green">0</span></td>
                              <td><span class="badge bg-red">0</span></td>
                              <td><span class="badge bg-gray">0</span></td>
                            </tr>
                            <tr>
                              <td>12</td>
                              <td>December</td>
                              <td><span class="badge bg-green">1</span></td>
                              <td><span class="badge bg-red">0</span></td>
                              <td><span class="badge bg-gray">1</span></td>
                            </tr>

                          </tbody>
                        </table>
                      </div>

                    </div>
                    <!-- /.tab-pane -->
                    <!-- /.tab-pane -->
                    <div class="tab-pane table-responsive" id="Fees">
                      <table class="table">
                        <tr>
                          <th>Student ID</th>
                          <th>Student Name</th>
                          <th>Roll No</th>

                          <th>Due Amount</th>
                          <th>Action</th>
                        </tr>
                        <tr>
                          <td>22504001</td>
                          <td>Test Student</td>
                          <td>1</td>

                          <td style="color:red; font-weight:bolder">13400</td>
                          <td><a class="btn-sm  btn-success" onclick="myFunction()">Details</a></td>
                        </tr>
                      </table>
                      <div id="feeDetail" style="display: none;" class="table-responsive">
                        <table class="table">
                          <tr class="bg-purple">
                            <th>Fee Name</th>
                            <th>Due Date</th>
                            <th>Payable Amount</th>
                            <th>Payment Date</th>
                            <th>Paid</th>
                            <th>Discount</th>
                            <th>Due Amount</th>
                          </tr>

                          <tr class="bg-secondary">
                            <td>Admission Fee </td>
                            <td>2022-12-31</td>
                            <td>10000</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                          </tr>
                          <!-- for detail paid -->

                          <tr>
                            <td></td>
                            <td></td>
                            <td><span class="label label-warning">Installment 1</span></td>
                            <td>2023-12-02 19:48:44</td>
                            <td>9000</td>
                            <td>0</td>
                            <td><span style='color:red'>1,000.00</span></td>
                          </tr>

                          <tr>
                            <td></td>
                            <td></td>
                            <td><span class="label label-warning">Installment 2</span></td>
                            <td>2023-12-02 19:51:10</td>
                            <td>1000</td>
                            <td>0</td>
                            <td><span style='color:green'>0.00</span></td>
                          </tr>
                          <tr style='background-color:whitesmoke;'>
                            <td colspan='7'></td>
                          </tr> <!-- Detail Paid end -->


                          <tr class="bg-secondary">
                            <td>Tution Fee (January)</td>
                            <td>2022-01-31</td>
                            <td>1000</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                          </tr>
                          <!-- for detail paid -->

                          <tr>
                            <td></td>
                            <td></td>
                            <td><span class="label label-warning">Installment 1</span></td>
                            <td>2023-12-02 19:48:44</td>
                            <td>1000</td>
                            <td>0</td>
                            <td><span style='color:green'>0.00</span></td>
                          </tr>
                          <tr style='background-color:whitesmoke;'>
                            <td colspan='7'></td>
                          </tr> <!-- Detail Paid end -->


                          <tr class="bg-secondary">
                            <td>Tution Fee (February)</td>
                            <td>2022-02-28</td>
                            <td>1000</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                          </tr>
                          <tr>
                            <td colspan='7'>No Payment has been deposited yet for this Fee Head!</td>
                          </tr> <!-- for detail paid -->
                          <!-- Detail Paid end -->


                          <tr class="bg-secondary">
                            <td>Tution Fee (March)</td>
                            <td>2022-03-31</td>
                            <td>1000</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                          </tr>
                          <tr>
                            <td colspan='7'>No Payment has been deposited yet for this Fee Head!</td>
                          </tr> <!-- for detail paid -->
                          <!-- Detail Paid end -->


                          <tr class="bg-secondary">
                            <td>Tution Fee (April)</td>
                            <td>2022-04-30</td>
                            <td>1000</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                          </tr>
                          <tr>
                            <td colspan='7'>No Payment has been deposited yet for this Fee Head!</td>
                          </tr> <!-- for detail paid -->
                          <!-- Detail Paid end -->


                          <tr class="bg-secondary">
                            <td>Tution Fee (May)</td>
                            <td>2022-05-31</td>
                            <td>1000</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                          </tr>
                          <tr>
                            <td colspan='7'>No Payment has been deposited yet for this Fee Head!</td>
                          </tr> <!-- for detail paid -->
                          <!-- Detail Paid end -->


                          <tr class="bg-secondary">
                            <td>Tution Fee (June)</td>
                            <td>2022-06-30</td>
                            <td>1000</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                          </tr>
                          <tr>
                            <td colspan='7'>No Payment has been deposited yet for this Fee Head!</td>
                          </tr> <!-- for detail paid -->
                          <!-- Detail Paid end -->


                          <tr class="bg-secondary">
                            <td>Tution Fee (July)</td>
                            <td>2022-07-31</td>
                            <td>1000</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                          </tr>
                          <tr>
                            <td colspan='7'>No Payment has been deposited yet for this Fee Head!</td>
                          </tr> <!-- for detail paid -->
                          <!-- Detail Paid end -->


                          <tr class="bg-secondary">
                            <td>Tution Fee (August)</td>
                            <td>2022-08-31</td>
                            <td>1000</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                          </tr>
                          <tr>
                            <td colspan='7'>No Payment has been deposited yet for this Fee Head!</td>
                          </tr> <!-- for detail paid -->
                          <!-- Detail Paid end -->


                          <tr class="bg-secondary">
                            <td>Tution Fee (September)</td>
                            <td>2022-09-30</td>
                            <td>1000</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                          </tr>
                          <tr>
                            <td colspan='7'>No Payment has been deposited yet for this Fee Head!</td>
                          </tr> <!-- for detail paid -->
                          <!-- Detail Paid end -->


                          <tr class="bg-secondary">
                            <td>Tution Fee (October)</td>
                            <td>2022-10-31</td>
                            <td>1000</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                          </tr>
                          <tr>
                            <td colspan='7'>No Payment has been deposited yet for this Fee Head!</td>
                          </tr> <!-- for detail paid -->
                          <!-- Detail Paid end -->


                          <tr class="bg-secondary">
                            <td>Tution Fee (November)</td>
                            <td>2022-11-30</td>
                            <td>1000</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                          </tr>
                          <tr>
                            <td colspan='7'>No Payment has been deposited yet for this Fee Head!</td>
                          </tr> <!-- for detail paid -->
                          <!-- Detail Paid end -->


                          <tr class="bg-secondary">
                            <td>Tution Fee (December)</td>
                            <td>2022-12-31</td>
                            <td>1000</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                          </tr>
                          <tr>
                            <td colspan='7'>No Payment has been deposited yet for this Fee Head!</td>
                          </tr> <!-- for detail paid -->
                          <!-- Detail Paid end -->


                          <div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

                            <h4>A PHP Error was encountered</h4>

                            <p>Severity: Notice</p>
                            <p>Message: Trying to get property of non-object</p>
                            <p>Filename: student_management/student_profile.php</p>
                            <p>Line Number: 180</p>


                            <p>Backtrace:</p>






                            <p style="margin-left:10px">
                              File:
                              /home/iconbangla/edu.iconbangla.net/application/views/manager/student_management/student_profile.php<br />
                              Line: 180<br />
                              Function: _error_handler </p>








                            <p style="margin-left:10px">
                              File:
                              /home/iconbangla/edu.iconbangla.net/application/controllers/manager/Student_management.php<br />
                              Line: 430<br />
                              Function: view </p>








                            <p style="margin-left:10px">
                              File: /home/iconbangla/edu.iconbangla.net/index.php<br />
                              Line: 317<br />
                              Function: require_once </p>




                          </div>
                          <tr class="bg-secondary">
                            <td>
                              <div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

                                <h4>A PHP Error was encountered</h4>

                                <p>Severity: Notice</p>
                                <p>Message: Trying to get property of non-object</p>
                                <p>Filename: student_management/student_profile.php</p>
                                <p>Line Number: 184</p>


                                <p>Backtrace:</p>






                                <p style="margin-left:10px">
                                  File:
                                  /home/iconbangla/edu.iconbangla.net/application/views/manager/student_management/student_profile.php<br />
                                  Line: 184<br />
                                  Function: _error_handler </p>








                                <p style="margin-left:10px">
                                  File:
                                  /home/iconbangla/edu.iconbangla.net/application/controllers/manager/Student_management.php<br />
                                  Line: 430<br />
                                  Function: view </p>








                                <p style="margin-left:10px">
                                  File: /home/iconbangla/edu.iconbangla.net/index.php<br />
                                  Line: 317<br />
                                  Function: require_once </p>




                              </div>
                            </td>
                            <td>2022-01-31</td>
                            <td>200</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                          </tr>
                          <tr>
                            <td colspan='7'>No Payment has been deposited yet for this Fee Head!</td>
                          </tr> <!-- for detail paid -->
                          <!-- Detail Paid end -->





                        </table>
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

<script>

$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


</script>


@endpush
