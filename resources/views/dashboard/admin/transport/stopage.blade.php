@extends('dashboard.admin.layouts.admin-layout-with-cdn')
@section('title', 'Stopage Management')

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
                        <h1 class="m-0">{{ __('language.stopage_mgmt') }}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">

                        </ol>
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a
                                    href="{{ route('admin.home') }}">{{ __('language.dashboard') }}</a></li>
                            <li class="breadcrumb-item">{{ __('language.stopage_mgmt') }}</li>
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
                                    <i class="fas fa-bus-stop mr-1"></i>
                                    {{ __('language.stopage_list') }}
                                </h3>
                                <div class="card-tools">
                                    <ul class="nav nav-pills ml-auto">
                                        <li class="nav-item">
                                            <button class="btn btn-success btn-sm" data-toggle="modal"
                                                data-target="#addStopageModal"><i class="fas fa-plus-square mr-1"></i>
                                                {{ __('language.stopage_add') }}</button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body table-responsive">
                                <!-- Existing table structure for stopages -->
                                <table class="table table-bordered table-striped table-hover table-sm" id="stopage-table">
                                    <thead style="border-top: 1px solid #b4b4b4">
                                        <th style="width: 10px">#</th>
                                        <th>{{ __('language.stopage_name') }}</th>
                                        <th>{{ __('language.stopage_type') }}</th>
                                        <th>{{ __('language.distance') }}</th>
                                        <th>{{ __('language.stopage_description') }}</th>
                                        <th>{{ __('language.status') }}</th>
                                        <th style="width: 40px">{{ __('language.action') }}</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($stopages as $stopage)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="font-weight-bold">{{ $stopage->stopage_name }}</td>
                                                <td>{{ $stopage->stopage_type }}</td>
                                                <td>{{ $stopage->distance }}</td>
                                                <td>{{ $stopage->stopage_description }}</td>
                                                <td
                                                    class="{{ $stopage->stopage_status == 1 ? 'text-success' : 'text-danger' }} font-weight-bold">
                                                    {{ $stopage->stopage_status == 1 ? __('language.active') : __('language.inactive') }}
                                                </td>

                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-warning btn-xs"
                                                            data-id="{{ $stopage->id }}" id="editStopageBtn">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-danger btn-xs"
                                                            data-id="{{ $stopage->id }}" id="deleteStopageBtn">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
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

                <!-- Add Stopage Modal -->
                <!-- Add Stopage Modal -->
                <div class="modal fade" id="addStopageModal" tabindex="-1" aria-labelledby="addStopageModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-xl">
                        <div class="modal-content">
                            <div class="modal-header bg-success">
                                <h5 class="modal-title" id="addStopageModalLabel">{{ __('language.stopage_add') }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('admin.addStopage') }}" method="POST" autocomplete="off"
                                    id="addstopageform">
                                    @csrf

                                    <div class="row">
                                        <!-- First Column -->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="stopage_name">{{ __('language.stopage_name') }}</label>
                                                <input type="text" class="form-control" name="stopage_name" id="stopage_name"
                                                    placeholder="{{ __('language.stopage_name') }}">
                                                <span class="text-danger error-text stopage_name_error"></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="stopage_type">{{ __('language.stopage_type') }}</label>
                                                <select name="stopage_type" class="form-control">
                                                    <option value="Pick">Pick</option>
                                                    <option value="Drop">Drop</option>
                                                    <option value="Pick and Drop">Pick and Drop</option>
                                                </select>
                                                <span class="text-danger error-text stopage_type_error"></span>
                                            </div>
                                        </div>

                                        <!-- Second Column -->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="distance">{{ __('language.distance') }}</label>
                                                <input type="text" class="form-control" name="distance" id="distance"
                                                    placeholder="{{ __('language.distance') }}">
                                                <span class="text-danger error-text distance_error"></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="stopage_status">{{ __('language.status') }}</label>
                                                <select class="form-control" name="stopage_status" id="stopage_status">
                                                    <option value="1">{{ __('language.active') }}</option>
                                                    <option value="0">{{ __('language.inactive') }}</option>
                                                </select>
                                                <span class="text-danger error-text stopage_status_error"></span>
                                            </div>
                                        </div>

                                        <!-- Third Column -->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="stopage_description">{{ __('language.stopage_description') }}</label>
                                                <textarea class="form-control" name="stopage_description" id="stopage_description"
                                                    placeholder="{{ __('language.stopage_description') }}"></textarea>
                                                <span class="text-danger error-text stopage_description_error"></span>
                                            </div>

                                        </div>

                                    </div>

                                    <button type="submit" class="btn btn-success">{{ __('language.save') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Edit Stopage Modal -->
                <!-- Edit Stopage Modal -->
                <div class="modal fade editStopage" tabindex="-1" role="dialog" aria-labelledby="editStopageLabel"
                    aria-hidden="true" data-keyboard="false" data-backdrop="static">
                    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-purple">
                                <h5 class="modal-title" id="editStopageLabel">{{ __('language.stopage_edit') }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('admin.updateStopageDetails') }}" method="post" autocomplete="off" id="update-stopage-form">
                                    @csrf
                                    <input type="hidden" name="sid">

                                    <div class="row">
                                        <!-- First Column -->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="stopage_name">{{ __('language.stopage_name') }}</label>
                                                <input type="text" class="form-control" name="stopage_name" id="stopage_name"
                                                    placeholder="{{ __('language.stopage_name') }}">
                                                <span class="text-danger error-text stopage_name_error"></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="stopage_type">{{ __('language.stopage_type') }}</label>
                                                <select name="stopage_type" class="form-control">
                                                    <option value="Pick">Pick</option>
                                                    <option value="Drop">Drop</option>
                                                    <option value="Pick and Drop">Pick and Drop</option>
                                                </select>
                                                <span class="text-danger error-text stopage_type_error"></span>
                                            </div>
                                        </div>

                                        <!-- Second Column -->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="distance">{{ __('language.distance') }}</label>
                                                <input type="text" class="form-control" name="distance" id="distance"
                                                    placeholder="{{ __('language.distance') }}">
                                                <span class="text-danger error-text distance_error"></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="stopage_status">{{ __('language.status') }}</label>
                                                <select class="form-control" name="stopage_status" id="stopage_status">
                                                    <option value="1">{{ __('language.active') }}</option>
                                                    <option value="0">{{ __('language.inactive') }}</option>
                                                </select>
                                                <span class="text-danger error-text stopage_status_error"></span>
                                            </div>
                                        </div>

                                        <!-- Third Column -->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="stopage_description">{{ __('language.stopage_description') }}</label>
                                                <textarea class="form-control" name="stopage_description" id="stopage_description"
                                                    placeholder="{{ __('language.stopage_description') }}"></textarea>
                                                <span class="text-danger error-text stopage_description_error"></span>
                                            </div>

                                        </div>

                                    </div>

                                    <button type="submit" class="btn bg-purple">{{ __('language.update') }}</button>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.2.0/dist/sweetalert2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {


            $('#addstopageform').on('submit', function(e) {
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
                        $(form).find('span.error-text').text('');
                    },
                    success: function(data) {
                        if (data.code == 0) {
                            $.each(data.error, function(prefix, val) {
                                $(form).find('span.' + prefix + '_error').text(val[0]);
                            });
                        } else {
                            var redirectUrl = data.redirect;
                            $('#addStopageModal').modal('hide');
                            $('#addStopageModal').find('form')[0].reset();
                            toastr.success(data.msg);

                            setTimeout(function() {
                                window.location.href = redirectUrl;
                            }, 1000);
                        }
                    },
                    complete: function() {
                        // Enable the submit button and hide the loader overlay
                        $(form).find(':submit').prop('disabled', false);
                        $('#loader-overlay').hide();
                    }
                });
            });



            $(document).on('click', '#editStopageBtn', function() {
                var stopage_id = $(this).data('id');

                $('.editStopage').find('form')[0].reset();
                $('.editStopage').find('span.error-text').text('');

                $.post("{{ route('admin.getStopageDetails') }}", {
                    stopage_id: stopage_id
                }, function(data) {
                    console.log('Stopage details received:',
                        data
                    ); // Check if this message and the stopage details appear in the console

                    $('.editStopage').find('input[name="sid"]').val(data.details.id);
                    $('.editStopage').find('input[name="stopage_name"]').val(data.details
                        .stopage_name);
                    $('.editStopage').find('select[name="stopage_type"]').val(data.details
                        .stopage_type);
                    $('.editStopage').find('input[name="distance"]').val(data.details.distance);
                    $('.editStopage').find('textarea[name="stopage_description"]').val(data.details
                        .stopage_description);
                    $('.editStopage').find('select[name="stopage_status"]').val(data.details
                        .stopage_status);
                    $('.editStopage').modal('show');

                    $('.editStopage').modal('show');
                }, 'json');
            });


            $('#update-stopage-form').on('submit', function(e) {
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
                            $('.editStopage').modal('hide');
                            $('.editStopage').find('form')[0].reset();
                            toastr.success(data.msg);

                            setTimeout(function() {
                                window.location.href = redirectUrl;
                            }, 1000);
                        }
                    },
                    complete: function() {
                        // Enable the submit button and hide the loader overlay
                        $(form).find(':submit').prop('disabled', false);
                        $('#loader-overlay').hide();
                    }
                });
            });

            $(document).on('click', '#deleteStopageBtn', function() {
                var stopage_id = $(this).data('id');
                var url = '<?= route('admin.deleteStopage') ?>';

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You want to delete this Stopage',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it',
                    cancelButtonText: 'Cancel',
                    showLoaderOnConfirm: true,
                    preConfirm: function() {
                        // Show the loader overlay
                        $('#loader-overlay').show();

                        return $.post(url, {
                            stopage_id: stopage_id
                        }, function(data) {
                            if (data.code == 1) {
                                var redirectUrl = data.redirect;
                                toastr.success(data.msg);
                                setTimeout(function() {
                                    window.location.href = redirectUrl;
                                }, 1000);
                            } else {
                                toastr.error(data.msg);
                            }
                        }, 'json');
                    },
                    allowOutsideClick: function() {
                        // Hide the loader overlay on outside click
                        $('#loader-overlay').hide();
                        return true;
                    }
                });
            });
        });
    </script>
@endpush
