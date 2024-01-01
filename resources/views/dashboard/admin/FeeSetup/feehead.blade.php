@extends('dashboard.admin.layouts.admin-layout-with-cdn')
@section('title', 'Fee Head')
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
                        <h1 class="m-0">{{ __('language.fee_head') }}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ __('language.dashboard') }}</a></li>
                            <li class="breadcrumb-item">{{ __('language.fee_head') }}</li>
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
                                    <i class="fas fa-money-bill-wave mr-1"></i>
                                    {{ __('language.fee_head_list') }}
                                </h3>
                                <div class="card-tools">
                                    <ul class="nav nav-pills ml-auto">
                                        <li class="nav-item">
                                            <button class="btn btn-success btn-sm" data-toggle="modal"
                                                data-target="#addFeeHeadModal">
                                                <i class="fas fa-plus-square mr-1"></i> {{ __('language.fee_head_add') }}
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body table-responsive">
                                <!-- Your table for fee heads listing goes here -->
                                <table class="table table-bordered table-striped table-hover table-sm" id="fee-heads-table">
                                    <thead style="border-top: 1px solid #b4b4b4">
                                        <th style="width: 15px">#</th>
                                        <th>{{ __('language.fee_head_name') }}</th>
                                        <th>{{ __('language.fee_head_description') }}</th>
                                        <th>{{ __('language.fee_frequency') }}</th>
                                        <th>{{ __('language.no_of_installment') }}</th>
                                        <th>{{ __('language.status') }}</th>
                                        <th style="width: 40px">{{ __('language.action') }}</th>
                                    </thead>
                                    <tbody>
                                        <!-- Loop through fee heads and display them here -->
                                        @foreach ($academicFeeHeads as $feeHead)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="font-weight-bold">{{ $feeHead->aca_feehead_name }}</td>
                                                <td>{{ $feeHead->aca_feehead_description }}</td>
                                                <td>
                                                    <!-- Get the frequency based on the ID from another database table -->
                                                    @foreach ($feeFrequencies as $freq)
                                                        @if ($freq->id == $feeHead->aca_feehead_freq)
                                                            {{ $freq->freq_name }}
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td>{{ $feeHead->no_of_installment }}</td>
                                                <td
                                                    class="{{ $feeHead->status == 1 ? 'text-success' : 'text-danger' }} font-weight-bold">
                                                    {{ $feeHead->status == 1 ? 'Active' : 'Inactive' }}
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-warning btn-xs"
                                                            data-id="{{ $feeHead->id }}" id="editFeeHeadBtn">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-danger btn-xs"
                                                            data-id="{{ $feeHead->id }}" id="deleteFeeHeadBtn">
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

            </div>
        </div>
    </div>
    <!-- /.content-wrapper -->

    <div class="modal fade" id="addFeeHeadModal" tabindex="-1" aria-labelledby="addFeeHeadModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title" id="addFeeHeadModalLabel">{{ __('language.fee_head_add') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.addAcademicFeeHead') }}" method="POST" autocomplete="off"
                        id="add-fee-head-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="aca_feehead_name">{{ __('language.fee_head_name') }}</label>
                                    <input type="text" class="form-control form-control-sm" name="aca_feehead_name"
                                        id="aca_feehead_name" placeholder="{{ __('language.fee_head_name') }}">
                                    <span class="text-danger error-text aca_feehead_name_error"></span>
                                </div>
                                <div class="form-group">
                                    <label for="aca_feehead_description">{{ __('language.fee_head_description') }}</label>
                                    <input type="text" class="form-control form-control-sm"
                                        name="aca_feehead_description" id="aca_feehead_description"
                                        placeholder="{{ __('language.fee_head_description') }}">
                                    <span class="text-danger error-text aca_feehead_description_error"></span>
                                </div>
                                <div class="form-group">
                                    <label for="aca_feehead_freq">{{ __('language.fee_frequency') }}</label>
                                    <select class="form-control form-control-sm" name="aca_feehead_freq"
                                        id="aca_feehead_freq">
                                        @foreach ($feeFrequencies as $freq)
                                            <option value="{{ $freq->id }}">{{ $freq->freq_name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error-text aca_feehead_freq_error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label for="status">{{ __('language.status') }}</label>
                                    <select class="form-control form-control-sm" name="status" id="status">
                                        <option value="1">{{ __('language.active') }}</option>
                                        <option value="0">{{ __('language.inactive') }}</option>
                                    </select>
                                    <span class="text-danger error-text status_error"></span>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">{{ __('language.save') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>




    <div class="modal fade editFeeHead" tabindex="-1" role="dialog" aria-labelledby="editFeeHeadLabel"
        aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-purple">
                    <h5 class="modal-title" id="editFeeHeadLabel">{{ __('language.fee_head_edit') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.updateAcademicFeeHeadDetails') }}" method="post" autocomplete="off"
                        id="update-fee-head-form">
                        @csrf
                        <input type="hidden" name="fee_head_id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="aca_feehead_name">{{ __('language.fee_head_name') }}</label>
                                    <input type="text" class="form-control form-control-sm" name="aca_feehead_name"
                                        id="aca_feehead_name" placeholder="{{ __('language.fee_head_name') }}">
                                    <span class="text-danger error-text aca_feehead_name_error"></span>
                                </div>
                                <div class="form-group">
                                    <label for="aca_feehead_description">{{ __('language.fee_head_description') }}</label>
                                    <input type="text" class="form-control form-control-sm"
                                        name="aca_feehead_description" id="aca_feehead_description"
                                        placeholder="{{ __('language.fee_head_description') }}">
                                    <span class="text-danger error-text aca_feehead_description_error"></span>
                                </div>
                                <div class="form-group">
                                    <label for="aca_feehead_freq">{{ __('language.fee_frequency') }}</label>
                                    <select class="form-control form-control-sm" name="aca_feehead_freq"
                                        id="aca_feehead_freq">
                                        @foreach ($feeFrequencies as $freq)
                                            <option value="{{ $freq->id }}">{{ $freq->freq_name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error-text aca_feehead_freq_error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label for="status">{{ __('language.status') }}</label>
                                    <select class="form-control form-control-sm" name="status" id="status">
                                        <option value="1">{{ __('language.active') }}</option>
                                        <option value="0">{{ __('language.inactive') }}</option>
                                    </select>
                                    <span class="text-danger error-text status_error"></span>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn  bg-purple">{{ __('language.update') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

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

            // Handle form submission for adding Academic Fee Head
            $('#add-fee-head-form').on('submit', function(e) {
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
                            // Handle validation errors
                            $.each(data.error, function(prefix, val) {
                                $(form).find('span.' + prefix + '_error').text(val[0]);
                            });
                        } else {
                            // Handle successful Academic Fee Head addition
                            var redirectUrl = data.redirect;
                            $('#addFeeHeadModal').modal('hide');
                            $('#add-fee-head-form')[0].reset();
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

            // Handle form submission for editing Academic Fee Head
            $('#update-fee-head-form').on('submit', function(e) {
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
                        if (data.code === 0) {
                            // Handle validation errors
                            $.each(data.error, function(prefix, val) {
                                $(form).find('span.' + prefix + '_error').text(val[0]);
                            });
                        } else {
                            // Handle successful fee head update
                            var redirectUrl = data.redirect;
                            $('.editFeeHead').modal('hide');
                            $('.editFeeHead').find('form')[0].reset();
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

            // Handle edit Fee Head button click
            $(document).on('click', '#editFeeHeadBtn', function() {
                var fee_head_id = $(this).data('id');
                $('.editFeeHead').find('form')[0].reset();
                $('.editFeeHead').find('span.error-text').text('');
                $.post("{{ route('admin.getAcademicFeeHeadDetails') }}", {
                    academic_feehead_id: fee_head_id
                }, function(data) {
                    $('.editFeeHead').find('input[name="fee_head_id"]').val(data.details.id);
                    $('.editFeeHead').find('input[name="aca_feehead_name"]').val(data.details
                        .aca_feehead_name);
                    $('.editFeeHead').find('input[name="aca_feehead_description"]').val(data.details
                        .aca_feehead_description);
                    $('.editFeeHead').find('select[name="aca_feehead_freq"]').val(data.details
                        .aca_feehead_freq);
                    $('.editFeeHead').find('input[name="no_of_installment"]').val(data.details
                        .no_of_installment);
                    $('.editFeeHead').find('select[name="status"]').val(data.details.status);
                    $('.editFeeHead').modal('show');
                }, 'json');
            });

            // Handle Academic Fee Head deletion
            $(document).on('click', '#deleteFeeHeadBtn', function() {
                var feeHeadId = $(this).data('id');
                var url = '<?= route('admin.deleteAcademicFeeHead') ?>';

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You want to delete this Academic Fee Head',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it',
                    cancelButtonText: 'Cancel',
                    showLoaderOnConfirm: true,
                    preConfirm: function() {
                        // Show the loader overlay
                        $('#loader-overlay').show();

                        return $.post(url, {
                            academic_feehead_id: feeHeadId
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
