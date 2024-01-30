@extends('dashboard.admin.layouts.admin-layout-with-cdn')
@section('title', 'Unit')
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
                        <h1 class="m-0">{{ __('language.unit') }}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ __('language.dashboard') }}</a></li>
                            <li class="breadcrumb-item">{{ __('language.unit') }}</li>
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
                                    {{ __('language.unit_list') }}
                                </h3>
                                <div class="card-tools">
                                    <ul class="nav nav-pills ml-auto">
                                        <li class="nav-item">

                                            <button class="btn btn-success btn-sm" data-toggle="modal"
                                                data-target="#addunits"><i class="fas fa-plus-square mr-1"></i>
                                                {{ __('language.unit_add') }}</button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body table-responsive">
                                <table class="table table-bordered table-striped table-hover table-sm" id="datas-table">
                                    <thead style="border-top: 1px solid #b4b4b4">
                                        <th style="width: 15px">#</th>
                                        <th>{{ __('language.unit_name') }}</th>
                                        <th>{{ __('language.status') }}</th>
                                        <th style="width: 40px">{{ __('language.action') }} <button
                                                class="btn btn-sm btn-danger d-none"
                                                id="deleteAllBtn">{{ __('language.deleteall') }}</button></th>
                                    </thead>
                                    <tbody>
                                        @foreach ($units as $unit)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="font-weight-bold">{{ $unit->unit_name }}</td>
                                                <td
                                                    class="{{ $unit->unit_status == 1 ? 'text-success' : 'text-danger' }} font-weight-bold">
                                                    {{ $unit->unit_status == 1 ? __('language.active') : __('language.inactive') }}
                                                </td>

                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-warning btn-xs"
                                                            data-id="{{ $unit->id }}" id="editUnitBtn"><i
                                                                class="fas fa-edit"></i></button>
                                                        <button type="button" class="btn btn-danger btn-xs"
                                                            data-id="{{ $unit->id }}" id="deleteUnitBtn"><i
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
                <div class="modal fade" id="addunits" tabindex="-1" aria-labelledby="addUnitLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-success">
                                <h5 class="modal-title" id="addUnitLabel">{{ __('language.unit_add') }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('admin.addUnit') }}" enctype="multipart/form-data" files="true"
                                    method="post" autocomplete="off" id="add-unit-form">
                                    @csrf

                                    <div class="form-group">
                                        <label for="unit_name">{{ __('language.unit_name') }}</label>
                                        <input type="text" class="form-control form-control-sm" name="unit_name"
                                            id="unit_name" placeholder="{{ __('language.unit_name') }}">
                                        <span class="text-danger error-text unit_name_error"></span>
                                    </div>

                                    <div class="form-group">
                                        <label for="status">{{ __('language.status') }}</label>
                                        <select class="form-control form-control-sm" name="unit_status"
                                            id="unit_status">
                                            <option value="1">{{ __('language.active') }}</option>
                                            <option value="0">{{ __('language.inactive') }}</option>
                                        </select>
                                        <span class="text-danger error-text unit_status_error"></span>
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
                <div class="modal fade editUnit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true" data-keyboard="false" data-backdrop="static">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-purple">
                                <h5 class="modal-title" id="exampleModalLabel">{{ __('language.unit_edit') }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            {{-- {{ route('admin.updatecategoryDetails'); }} --}}
                            <div class="modal-body">
                                <form action="{{ route('admin.updateUnitDetails') }}" enctype="multipart/form-data"
                                    files="true" method="post" autocomplete="off" id="update-unit-form">
                                    @csrf
                                    <input type="hidden" name="vid">
                                    <div class="form-group">
                                        <label for="unit_name">{{ __('language.unit_name') }}</label>
                                        <input type="text" class="form-control form-control-sm" name="unit_name"
                                            id="unit_name" placeholder="{{ __('language.unit_name') }}">
                                        <span class="text-danger error-text unit_name_error"></span>
                                    </div>

                                    <div class="form-group">
                                        <label for="status">{{ __('language.status') }}</label>
                                        <select class="form-control form-control-sm" name="unit_status"
                                            id="unit_status">
                                            <option value="1">{{ __('language.active') }}</option>
                                            <option value="0">{{ __('language.inactive') }}</option>
                                        </select>
                                        <span class="text-danger error-text unit_status_error"></span>
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

            // Add Unit RECORD
            $('#add-unit-form').on('submit', function(e) {
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
                            $('#addunits').modal('hide');
                            $('#addunits').find('form')[0].reset();
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

            $(document).on('click', '#editUnitBtn', function() {
                var unit_id = $(this).data('id');

                $('.editUnit').find('form')[0].reset();
                $('.editUnit').find('span.error-text').text('');
                $.post("{{ route('admin.getUnitDetails') }}", {
                    unit_id: unit_id
                }, function(data) {
                    //alert(data.details.unit_name);
                    var linkModal = $('.editUnit');
                    $('.editUnit').find('input[name="vid"]').val(data.details.id);
                    $('.editUnit').find('input[name="unit_name"]').val(data.details
                        .unit_name);
                    $('.editUnit').find('select[name="unit_status"]').val(data.details
                        .unit_status);
                    $('.editUnit').modal('show');
                }, 'json');
            });

            // Update Unit RECORD
            $('#update-unit-form').on('submit', function(e) {
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
                            $('.editUnit').modal('hide');
                            $('.editUnit').find('form')[0].reset();
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

            // DELETE Unit RECORD
            $(document).on('click', '#deleteUnitBtn', function() {
                var unit_id = $(this).data('id');
                var url = '<?= route('admin.deleteUnit') ?>';

                swal.fire({
                    title: 'Are you sure?',
                    html: 'You want to <b>delete</b> this unit name',
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
                            unit_id: unit_id
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
