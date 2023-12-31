@extends('dashboard.admin.layouts.admin-layout-with-cdn')
@section('title', 'Version')
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
                        <h1 class="m-0">{{ __('language.version') }}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ __('language.version') }}</a>
                            </li>
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
                                    {{ __('language.version_list') }}
                                </h3>
                                <div class="card-tools">
                                    <ul class="nav nav-pills ml-auto">
                                        <li class="nav-item">

                                            <button class="btn btn-success btn-sm" data-toggle="modal"
                                                data-target="#addversions"><i class="fas fa-plus-square mr-1"></i>
                                                {{ __('language.version_add') }}</button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body table-responsive">
                                <table class="table table-bordered table-striped table-hover table-sm" id="datas-table">
                                    <thead style="border-top: 1px solid #b4b4b4">
                                        <th style="width: 15px">#</th>
                                        <th>Student Name</th>
                                        <th>{{ __('language.status') }}</th>
                                        <th style="width: 40px">{{ __('language.action') }} <button
                                                class="btn btn-sm btn-danger d-none"
                                                id="deleteAllBtn">{{ __('language.deleteall') }}</button></th>
                                    </thead>
                                    <tbody>
                                        @foreach ($students as $student)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="font-weight-bold">{{ $student->std_name }}</td>
                                                <td
                                                    class="{{ $student->std_status == 1 ? 'text-success' : 'text-danger' }} font-weight-bold">
                                                    {{ $student->std_status == 1 ? 'Active' : 'Pending' }}
                                                </td>

                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-warning btn-xs"
                                                            data-id="{{ $student->std_phone }}" id="viewButton">View</button>

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
                var version_id = $(this).data('id');

                $('.editStd').find('form')[0].reset();
                $('.editStd').find('span.error-text').text('');
                $.post("{{ route('admin.getVersionDetails') }}", {
                    version_id: version_id
                }, function(data) {
                    //alert(data.details.version_name);
                    var linkModal = $('.editVersion');
                    $('.editStd').find('input[name="vid"]').val(data.details.id);

                    $('.editStd').find('select[name="std_status"]').val(data.details
                        .version_status);
                    $('.editStd').modal('show');
                }, 'json');
            });

            // Update Version RECORD
            $('#update-version-form').on('submit', function(e) {
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
                        $(form).find('span.error-text').text('');
                    },
                    success: function(data) {
                        if (data.code == 0) {
                            $.each(data.error, function(prefix, val) {
                                $(form).find('span.' + prefix + '_error').text(val[0]);
                            });
                        } else {
                            var redirectUrl = data.redirect;
                            $('.editVersion').modal('hide');
                            $('.editVersion').find('form')[0].reset();
                            toastr.success(data.msg);

                            setTimeout(function() {
                                window.location.href = redirectUrl;
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

            // DELETE Version RECORD
            $(document).on('click', '#deleteVersionBtn', function() {
                var version_id = $(this).data('id');
                var url = '<?= route('admin.deleteVersion') ?>';

                swal.fire({
                    title: 'Are you sure?',
                    html: 'You want to <b>delete</b> this version name',
                    showCancelButton: true,
                    showCloseButton: true,
                    cancelButtonText: 'Cancel',
                    confirmButtonText: 'Yes, Delete',
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',

                    allowOutsideClick: false
                }).then(function(result) {
                    if (result.value) {
                        // Show the loader overlay
                        $('#loader-overlay').show();

                        $.post(url, {
                            version_id: version_id
                        }, function(data) {
                            if (data.code == 1) {
                                var redirectUrl = data.redirect;
                                toastr.success(data.msg);

                                setTimeout(function() {
                                    window.location.href = redirectUrl;
                                }, 1000); // Adjust the delay as needed (in milliseconds)

                            } else {
                                toastr.error(data.msg);
                            }
                        }, 'json').always(function() {
                            // Hide the loader overlay regardless of the request result
                            $('#loader-overlay').hide();
                        });
                    }
                });
            });

        });
    </script>
@endpush
