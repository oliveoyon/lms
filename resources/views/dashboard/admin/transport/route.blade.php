@extends('dashboard.admin.layouts.admin-layout-with-cdn')
@section('title', 'Route')
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
                        <h1 class="m-0">{{ __('language.route_list') }}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ __('language.dashboard') }}</a></li>
                            <li class="breadcrumb-item">{{ __('language.route_list') }}</li>
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
                                    {{ __('language.route_list') }}
                                </h3>
                                <div class="card-tools">
                                    <ul class="nav nav-pills ml-auto">
                                        <li class="nav-item">

                                            <button class="btn btn-success btn-sm" data-toggle="modal"
                                                data-target="#addroutes"><i class="fas fa-plus-square mr-1"></i>
                                                {{ __('language.route_add') }}</button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body table-responsive">
                                <table class="table table-bordered table-striped table-hover table-sm" id="datas-table">
                                    <thead style="border-top: 1px solid #b4b4b4">
                                        <th style="width: 15px">#</th>
                                        <th>{{ __('language.route_name') }}</th>
                                        <th>{{ __('language.route_description') }}</th>
                                        <th>{{ __('language.vehicle_name') }}</th>
                                        <th>{{ __('language.stopage_name') }}</th>
                                        <th>{{ __('language.pickup_time') }}</th>
                                        <th>{{ __('language.drop_time') }}</th>
                                        <th>{{ __('language.status') }}</th>
                                        <th style="width: 40px">{{ __('language.action') }} <button
                                                class="btn btn-sm btn-danger d-none"
                                                id="deleteAllBtn">{{ __('language.deleteall') }}</button></th>
                                    </thead>
                                    <tbody>
                                        @foreach ($routes as $route)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="font-weight-bold">{{ $route->route_name }}</td>
                                                <td>{{ $route->route_description }}</td>
                                                <td>{{ $route->vehicle->vehicle_name }}</td>
                                                <td>{{ $route->stopage->stopage_name }}</td>
                                                <td>{{ $route->pickup_time }}</td>
                                                <td>{{ $route->drop_time }}</td>
                                                <td
                                                    class="{{ $route->route_status == 1 ? 'text-success' : 'text-danger' }} font-weight-bold">
                                                    {{ $route->route_status == 1 ? __('language.active') : __('language.inactive') }}
                                                </td>

                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-warning btn-xs"
                                                            data-id="{{ $route->id }}" id="editRouteBtn"><i
                                                                class="fas fa-edit"></i></button>
                                                        <button type="button" class="btn btn-danger btn-xs"
                                                            data-id="{{ $route->id }}" id="deleteRouteBtn"><i
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
                <div class="modal fade" id="addroutes" tabindex="-1" aria-labelledby="addRouteLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-xl">
                        <div class="modal-content">
                            <div class="modal-header bg-success">
                                <h5 class="modal-title" id="addRouteLabel">{{ __('language.route_add') }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('admin.addRoute') }}" enctype="multipart/form-data" files="true" method="post"
                                    autocomplete="off" id="add-route-form">
                                    @csrf

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="route_name">{{ __('language.route_name') }}</label>
                                                <input type="text" class="form-control" name="route_name" id="route_name"
                                                    placeholder="{{ __('language.route_name') }}">
                                                <span class="text-danger error-text route_name_error"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="vehicle_id">{{ __('language.vehicle') }}</label>
                                                <select class="form-control" name="vehicle_id" id="vehicle_id">
                                                    <!-- Populate this dropdown with data from the 'vehicles' table -->
                                                    @foreach($vehicles as $vehicle)
                                                        <option value="{{ $vehicle->id }}">{{ $vehicle->vehicle_name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger error-text vehicle_id_error"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="stopage_id">{{ __('language.stopage_name') }}</label>
                                                <select class="form-control" name="stopage_id" id="stopage_id">
                                                    <!-- Populate this dropdown with data from the 'trans_stopages' table -->
                                                    @foreach($stopages as $stopage)
                                                        <option value="{{ $stopage->id }}">{{ $stopage->stopage_name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger error-text stopage_id_error"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="pickup_time">{{ __('language.pickup_time') }}</label>
                                                <input type="time" class="form-control" name="pickup_time" id="pickup_time"
                                                    placeholder="{{ __('language.pickup_time') }}">
                                                <span class="text-danger error-text pickup_time_error"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="drop_time">{{ __('language.drop_time') }}</label>
                                                <input type="time" class="form-control" name="drop_time" id="drop_time"
                                                    placeholder="{{ __('language.drop_time') }}">
                                                <span class="text-danger error-text drop_time_error"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="route_status">{{ __('language.status') }}</label>
                                                <select class="form-control" name="route_status" id="route_status">
                                                    <option value="1">{{ __('language.active') }}</option>
                                                    <option value="0">{{ __('language.inactive') }}</option>
                                                </select>
                                                <span class="text-danger error-text route_status_error"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="route_description">{{ __('language.route_description') }}</label>
                                                <textarea class="form-control" name="route_description" id="route_description"
                                                    placeholder="{{ __('language.route_description') }}"></textarea>
                                                <span class="text-danger error-text route_description_error"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-block btn-success">{{ __('language.save') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


                {{-- Modal End --}}


                {{-- Edit Modal --}}
                <div class="modal fade editRoute" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true" data-keyboard="false" data-backdrop="static">
                    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-purple">
                                <h5 class="modal-title" id="exampleModalLabel">{{ __('language.route_edit') }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            {{-- {{ route('admin.updatecategoryDetails'); }} --}}
                            <div class="modal-body">
                                <form action="{{ route('admin.updateRouteDetails') }}" enctype="multipart/form-data"
                                    files="true" method="post" autocomplete="off" id="update-route-form">
                                    @csrf
                                    <input type="hidden" name="vid">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="route_name">{{ __('language.route_name') }}</label>
                                                <input type="text" class="form-control" name="route_name" id="route_name"
                                                    placeholder="{{ __('language.route_name') }}">
                                                <span class="text-danger error-text route_name_error"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="vehicle_id">{{ __('language.vehicle') }}</label>
                                                <select class="form-control" name="vehicle_id" id="vehicle_id">
                                                    <!-- Populate this dropdown with data from the 'vehicles' table -->
                                                    @foreach($vehicles as $vehicle)
                                                        <option value="{{ $vehicle->id }}">{{ $vehicle->vehicle_name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger error-text vehicle_id_error"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="stopage_id">{{ __('language.stopage_name') }}</label>
                                                <select class="form-control" name="stopage_id" id="stopage_id">
                                                    <!-- Populate this dropdown with data from the 'trans_stopages' table -->
                                                    @foreach($stopages as $stopage)
                                                        <option value="{{ $stopage->id }}">{{ $stopage->stopage_name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger error-text stopage_id_error"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="pickup_time">{{ __('language.pickup_time') }}</label>
                                                <input type="time" class="form-control" name="pickup_time" id="pickup_time"
                                                    placeholder="{{ __('language.pickup_time') }}">
                                                <span class="text-danger error-text pickup_time_error"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="drop_time">{{ __('language.drop_time') }}</label>
                                                <input type="time" class="form-control" name="drop_time" id="drop_time"
                                                    placeholder="{{ __('language.drop_time') }}">
                                                <span class="text-danger error-text drop_time_error"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="route_status">{{ __('language.status') }}</label>
                                                <select class="form-control" name="route_status" id="route_status">
                                                    <option value="1">{{ __('language.active') }}</option>
                                                    <option value="0">{{ __('language.inactive') }}</option>
                                                </select>
                                                <span class="text-danger error-text route_status_error"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="route_description">{{ __('language.route_description') }}</label>
                                                <textarea class="form-control" name="route_description" id="route_description"
                                                    placeholder="{{ __('language.route_description') }}"></textarea>
                                                <span class="text-danger error-text route_description_error"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-block bg-purple">{{ __('language.save') }}</button>
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

            // Add Route RECORD
            $('#add-route-form').on('submit', function(e) {
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
                            $('#addroutes').modal('hide');
                            $('#addroutes').find('form')[0].reset();
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

            $(document).on('click', '#editRouteBtn', function() {
                var route_id = $(this).data('id');

                $('.editRoute').find('form')[0].reset();
                $('.editRoute').find('span.error-text').text('');
                $.post("{{ route('admin.getRouteDetails') }}", {
                    route_id: route_id
                }, function(data) {
                    //alert(data.details.route_name);
                    var linkModal = $('.editRoute');
                    $('.editRoute').find('input[name="vid"]').val(data.details.id);
                    $('.editRoute').find('input[name="route_name"]').val(data.details.route_name);
                    $('.editRoute').find('select[name="route_status"]').val(data.details.route_status);
                    $('.editRoute').find('select[name="vehicle_id"]').val(data.details.vehicle_id);
                    $('.editRoute').find('select[name="stopage_id"]').val(data.details.stopage_id);
                    $('.editRoute').find('input[name="pickup_time"]').val(data.details.pickup_time);
                    $('.editRoute').find('input[name="drop_time"]').val(data.details.drop_time);
                    $('.editRoute').find('textarea[name="route_description"]').val(data.details.route_description);
                    $('.editRoute').modal('show');
                }, 'json');
            });

            // Update Route RECORD
            $('#update-route-form').on('submit', function(e) {
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
                            $('.editRoute').modal('hide');
                            $('.editRoute').find('form')[0].reset();
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

            // DELETE Route RECORD
            $(document).on('click', '#deleteRouteBtn', function() {
                var route_id = $(this).data('id');
                var url = '<?= route('admin.deleteRoute') ?>';

                swal.fire({
                    title: 'Are you sure?',
                    html: 'You want to <b>delete</b> this route name',
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
                            route_id: route_id
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
