@extends('dashboard.admin.layouts.admin-layout-with-cdn')
@section('title', 'Vehicletype')
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
                        <h1 class="m-0">{{ __('language.vehicletype') }}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ __('language.dashboard') }}</a></li>
                            <li class="breadcrumb-item">{{ __('language.vehicletype') }}</li>
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
                                    {{ __('language.vehicletype_list') }}
                                </h3>
                                <div class="card-tools">
                                    <ul class="nav nav-pills ml-auto">
                                        <li class="nav-item">

                                            <button class="btn btn-success btn-sm" data-toggle="modal"
                                                data-target="#addvehicletypes"><i class="fas fa-plus-square mr-1"></i>
                                                {{ __('language.vehicletype_add') }}</button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body table-responsive">
                                <table class="table table-bordered table-striped table-hover table-sm" id="datas-table">
                                    <thead style="border-top: 1px solid #b4b4b4">
                                        <th style="width: 15px">#</th>
                                        <th>{{ __('language.vehicle_type') }}</th>

                                        <th>{{ __('language.status') }}</th>
                                        <th style="width: 40px">{{ __('language.action') }} <button
                                                class="btn btn-sm btn-danger d-none"
                                                id="deleteAllBtn">{{ __('language.deleteall') }}</button></th>
                                    </thead>
                                    <tbody>
                                        @foreach ($vehicletypes as $vehicletype)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="font-weight-bold">{{ $vehicletype->vehicle_type }}</td>
                                                <td
                                                    class="{{ $vehicletype->vehicle_type_status == 1 ? 'text-success' : 'text-danger' }} font-weight-bold">
                                                    {{ $vehicletype->vehicle_type_status == 1 ? __('language.active') : __('language.inactive') }}
                                                </td>

                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-warning btn-xs"
                                                            data-id="{{ $vehicletype->id }}" id="editVehicletypeBtn"><i
                                                                class="fas fa-edit"></i></button>
                                                        <button type="button" class="btn btn-danger btn-xs"
                                                            data-id="{{ $vehicletype->id }}" id="deleteVehicletypeBtn"><i
                                                                class="fas fa-trash-alt "></i></button>
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


                <!--Add Menu Modal -->
                <div class="modal fade" id="addvehicletypes" tabindex="-1" aria-labelledby="addVehicletypeLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-success">
                                <h5 class="modal-title" id="addVehicletypeLabel">{{ __('language.vehicletype_add') }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('admin.addVehicletype') }}" enctype="multipart/form-data" files="true"
                                    method="post" autocomplete="off" id="add-vehicletype-form">
                                    @csrf

                                    <div class="form-group">
                                        <label for="vehicle_type">{{ __('language.vehicle_type') }}</label>
                                        <input type="text" class="form-control form-control-sm" name="vehicle_type"
                                            id="vehicle_type" placeholder="{{ __('language.vehicle_type') }}">
                                        <span class="text-danger error-text vehicle_type_error"></span>
                                    </div>

                                    <div class="form-group">
                                        <label for="status">{{ __('language.status') }}</label>
                                        <select class="form-control form-control-sm" name="vehicle_type_status"
                                            id="vehicle_type_status">
                                            <option value="1">{{ __('language.active') }}</option>
                                            <option value="0">{{ __('language.inactive') }}</option>
                                        </select>
                                        <span class="text-danger error-text vehicle_type_status_error"></span>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit"
                                            class="btn btn-block btn-success">{{ __('language.save') }}</button>
                                    </div>
                                </form>


                            </div>

                        </div>
                    </div>
                </div>
                {{-- Modal End --}}


                {{-- Edit Modal --}}
                <div class="modal fade editVehicletype" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true" data-keyboard="false" data-backdrop="static">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-purple">
                                <h5 class="modal-title" id="exampleModalLabel">{{ __('language.vehicletype_edit') }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            {{-- {{ route('admin.updatecategoryDetails'); }} --}}
                            <div class="modal-body">
                                <form action="{{ route('admin.updateVehicletypeDetails') }}" enctype="multipart/form-data"
                                    files="true" method="post" autocomplete="off" id="update-vehicletype-form">
                                    @csrf
                                    <input type="hidden" name="vid">
                                    <div class="form-group">
                                        <label for="vehicle_type">{{ __('language.vehicle_type') }}</label>
                                        <input type="text" class="form-control form-control-sm" name="vehicle_type"
                                            id="vehicle_type" placeholder="{{ __('language.vehicle_type') }}">
                                        <span class="text-danger error-text vehicle_type_error"></span>
                                    </div>

                                    <div class="form-group">
                                        <label for="status">{{ __('language.status') }}</label>
                                        <select class="form-control form-control-sm" name="vehicle_type_status"
                                            id="vehicle_type_status">
                                            <option value="1">{{ __('language.active') }}</option>
                                            <option value="0">{{ __('language.inactive') }}</option>
                                        </select>
                                        <span class="text-danger error-text vehicle_type_status_error"></span>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit"
                                            class="btn btn-block bg-purple">{{ __('language.update') }}</button>
                                    </div>
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

            // Add Vehicletype RECORD
            $('#add-vehicletype-form').on('submit', function(e) {
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
                            $('#addvehicletypes').modal('hide');
                            $('#addvehicletypes').find('form')[0].reset();
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

            $(document).on('click', '#editVehicletypeBtn', function() {
                var vehicletype_id = $(this).data('id');

                $('.editVehicletype').find('form')[0].reset();
                $('.editVehicletype').find('span.error-text').text('');
                $.post("{{ route('admin.getVehicletypeDetails') }}", {
                    vehicletype_id: vehicletype_id
                }, function(data) {
                    //alert(data.details.vehicle_type);
                    var linkModal = $('.editVehicletype');
                    $('.editVehicletype').find('input[name="vid"]').val(data.details.id);
                    $('.editVehicletype').find('input[name="vehicle_type"]').val(data.details
                        .vehicle_type);
                    $('.editVehicletype').find('select[name="vehicle_type_status"]').val(data.details
                        .vehicle_type_status);
                    $('.editVehicletype').modal('show');
                }, 'json');
            });

            // Update Vehicletype RECORD
            $('#update-vehicletype-form').on('submit', function(e) {
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
                            $('.editVehicletype').modal('hide');
                            $('.editVehicletype').find('form')[0].reset();
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

            // DELETE Vehicletype RECORD
            $(document).on('click', '#deleteVehicletypeBtn', function() {
                var vehicletype_id = $(this).data('id');
                var url = '<?= route('admin.deleteVehicletype') ?>';

                swal.fire({
                    title: 'Are you sure?',
                    html: 'You want to <b>delete</b> this vehicletype name',
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
                            vehicletype_id: vehicletype_id
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
