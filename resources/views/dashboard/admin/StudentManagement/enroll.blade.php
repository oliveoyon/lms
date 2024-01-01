@extends('dashboard.admin.layouts.admin-layout-with-cdn')
@section('title', 'Enroll Student')
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
                        <h1 class="m-0">{{ __('language.enroll_student') }}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ __('language.dashboard') }}</a></li>
                            <li class="breadcrumb-item">{{ __('language.enroll_student') }}</li>
                        </ol>
                    </div>
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
                                    Applied Students List
                                </h3>

                            </div>
                            <div class="card-body table-responsive">
                                <table class="table table-bordered table-striped table-hover table-sm" id="datas-table">
                                    <thead style="border-top: 1px solid #b4b4b4">
                                        <th style="width: 15px">#</th>
                                        <th>{{ __('language.student_name') }}</th>
                                        <th>{{ __('language.students_phone') }}</th>
                                        <th>{{ __('language.status') }}</th>
                                        <th>{{ __('language.action') }} <button
                                                class="btn btn-sm btn-danger d-none"
                                                id="deleteAllBtn">{{ __('language.deleteall') }}</button></th>
                                    </thead>
                                    <tbody>
                                        @foreach ($students as $student)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="font-weight-bold">{{ $student->std_name }}</td>
                                                <td>{{ $student->std_phone }}</td>
                                                <td
                                                    class="{{ $student->std_status == 1 ? 'text-success' : 'text-danger' }} font-weight-bold">
                                                    {{ $student->std_status == 1 ? 'Active' : 'Pending' }}
                                                </td>

                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-warning btn-xs"
                                                            data-id="{{ $student->std_phone }}"
                                                            id="viewButton">Admission Form</button>
                                                            <a class="btn btn-info btn-xs" href="{{ url('admin/applied-student-fullview/'.$student->std_hash_id) }}">Details</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>


                {{-- Edit Modal --}}
                <div class="modal fade editStd" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true" data-keyboard="false" data-backdrop="static">
                    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Student Detail</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            {{-- {{ route('admin.updatecategoryDetails'); }} --}}
                            <div class="modal-body">



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
        new DataTable('#data-table');
    </script>



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



            $(document).on('click', '#viewButton', function() {
                var std_phone = $(this).data('id');



                // Make an asynchronous AJAX request
                $.ajax({
                    url: "/getslip1/" + std_phone,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        if (data && data.htmlContent) {
                            // Use a callback function for modal show
                            updateModalContent(data.htmlContent, function() {
                                $('.editStd').modal('show');
                            });
                        } else {
                            console.error('Invalid response from the server.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error occurred:', error);
                    }
                });
            });

            // Callback function to update modal content
            function updateModalContent(content, callback) {
                $('.editStd').find('.modal-body').html(content);

                // Call the callback function
                if (typeof callback === 'function') {
                    callback();
                }
            }





        });
    </script>
@endpush
