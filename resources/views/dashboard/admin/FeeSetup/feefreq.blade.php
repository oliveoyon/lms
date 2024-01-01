@extends('dashboard.admin.layouts.admin-layout-with-cdn')
@section('title', 'Fee Frequency')
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
                        <h1 class="m-0">{{ __('language.fee_frequency') }}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ __('language.dashboard') }}</a></li>
                            <li class="breadcrumb-item">{{ __('language.fee_frequency') }}</li>
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
                                    {{ __('language.fee_frequency_list') }}
                                </h3>
                                <div class="card-tools">
                                    <ul class="nav nav-pills ml-auto">
                                        <li class="nav-item">
                                            <button class="btn btn-success btn-sm" data-toggle="modal"
                                                data-target="#addFeeFrequencyModal">
                                                <i class="fas fa-plus-square mr-1"></i>
                                                {{ __('language.fee_frequency_add') }}
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body table-responsive">
                                <!-- Your table for fee frequencies listing goes here -->
                                <table class="table table-bordered table-striped table-hover table-sm"
                                    id="fee-frequencies-table">
                                    <thead style="border-top: 1px solid #b4b4b4">
                                        <th style="width: 15px">#</th>
                                        <th>{{ __('language.fee_frequency_name') }}</th>
                                        <th>{{ __('language.no_of_installment') }}</th>
                                        <th>{{ __('language.installment_period') }}</th>
                                        <th>{{ __('language.status') }}</th>
                                        <th style="width: 40px">{{ __('language.action') }}</th>
                                    </thead>
                                    <tbody>
                                        <!-- Loop through fee frequencies and display them here -->
                                        @foreach ($feeFrequencies as $feeFrequency)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="font-weight-bold">{{ $feeFrequency->freq_name }}</td>
                                                <td>{{ $feeFrequency->no_of_installment }}</td>
                                                <td>{{ $feeFrequency->installment_period }}</td>
                                                <td
                                                    class="{{ $feeFrequency->freq_status == 1 ? 'text-success' : 'text-danger' }} font-weight-bold">
                                                    {{ $feeFrequency->freq_status == 1 ? 'Active' : 'Inactive' }}
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-warning btn-xs"
                                                            data-id="{{ $feeFrequency->id }}" id="editFeeFrequencyBtn">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-danger btn-xs"
                                                            data-id="{{ $feeFrequency->id }}" id="deleteFeeFrequencyBtn">
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



                <!-- Add Fee Frequency Modal -->
                <div class="modal fade" id="addFeeFrequencyModal" tabindex="-1" aria-labelledby="addFeeFrequencyModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-success">
                                <h5 class="modal-title" id="addFeeFrequencyModalLabel">
                                    {{ __('language.fee_frequency_add') }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('admin.addFeeFrequency') }}" method="POST" autocomplete="off"
                                    id="add-fee-frequency-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="freq_name">{{ __('language.fee_frequency_name') }}</label>
                                                <input type="text" class="form-control form-control-sm" name="freq_name"
                                                    placeholder="{{ __('language.fee_frequency_name') }}">
                                                <span class="text-danger error-text freq_name_error"></span>
                                            </div>
                                            <div class="form-group">
                                                <label
                                                    for="no_of_installment">{{ __('language.no_of_installment') }}</label>
                                                <input type="number" class="form-control form-control-sm"
                                                    name="no_of_installment"
                                                    placeholder="{{ __('language.no_of_installment') }}">
                                                <span class="text-danger error-text no_of_installment_error"></span>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label
                                                    for="installment_period">{{ __('language.installment_period') }}</label>
                                                <input type="text" class="form-control form-control-sm"
                                                    name="installment_period" placeholder="{{ __('language.installment_period') }}">
                                                <span class="text-danger error-text installment_period_error"></span>
                                            </div>
                                            <div class="form-group">
                                                <label for="freq_status">Status</label>
                                                <select class="form-control form-control-sm" name="freq_status"
                                                    id="freq_status">
                                                    <option value="1">{{ __('language.active') }}</option>
                                                    <option value="0">{{ __('language.inactive') }}</option>
                                                </select>
                                                <span class="text-danger error-text freq_status_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-success">{{ __('language.save') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- {{ __('language.fee_frequency_edit') }} Modal -->
                <div class="modal fade editFeeFrequency" tabindex="-1" role="dialog"
                    aria-labelledby="editFeeFrequencyLabel" aria-hidden="true" data-keyboard="false"
                    data-backdrop="static">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-purple">
                                <h5 class="modal-title" id="editFeeFrequencyLabel">
                                    {{ __('language.fee_frequency_edit') }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('admin.updateFeeFrequencyDetails') }}" method="post"
                                    autocomplete="off" id="update-fee-frequency-form">
                                    @csrf
                                    <input type="hidden" name="fee_frequency_id">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="freq_name">{{ __('language.fee_frequency_name') }}</label>
                                                <input type="text" class="form-control form-control-sm"
                                                    name="freq_name"
                                                    placeholder="{{ __('language.fee_frequency_name') }}">
                                                <span class="text-danger error-text freq_name_error"></span>
                                            </div>
                                            <div class="form-group">
                                                <label
                                                    for="no_of_installment">{{ __('language.no_of_installment') }}</label>
                                                <input type="number" class="form-control form-control-sm"
                                                    name="no_of_installment"
                                                    placeholder="{{ __('language.no_of_installment') }}">
                                                <span class="text-danger error-text no_of_installment_error"></span>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label
                                                    for="installment_period">{{ __('language.installment_period') }}</label>
                                                <input type="text" class="form-control form-control-sm"
                                                    name="installment_period" placeholder="Installment Period">
                                                <span class="text-danger error-text installment_period_error"></span>
                                            </div>
                                            <div class="form-group">
                                                <label for="freq_status">Status</label>
                                                <select class="form-control form-control-sm" name="freq_status"
                                                    id="freq_status">
                                                    <option value="1">{{ __('language.active') }}</option>
                                                    <option value="0">{{ __('language.inactive') }}</option>
                                                </select>
                                                <span class="text-danger error-text freq_status_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit"
                                            class="btn btn-block  bg-purple">{{ __('language.update') }}</button>
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
            // Handle fee frequency form submission
            $('#add-fee-frequency-form').on('submit', function(e) {
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
                            // Handle successful fee frequency addition
                            var redirectUrl = data.redirect;
                            $('#addFeeFrequencyModal').modal('hide');
                            $('#add-fee-frequency-form')[0].reset();
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


            $(document).on('click', '#editFeeFrequencyBtn', function() {
                var fee_frequency_id = $(this).data('id');
                $('.editFeeFrequency').find('form')[0].reset();
                $('.editFeeFrequency').find('span.error-text').text('');
                $.post("{{ route('admin.getFeeFrequencyDetails') }}", {
                    fee_frequency_id: fee_frequency_id
                }, function(data) {
                    $('.editFeeFrequency').find('input[name="fee_frequency_id"]').val(data.details
                        .id);
                    $('.editFeeFrequency').find('input[name="freq_name"]').val(data.details
                        .freq_name);
                    $('.editFeeFrequency').find('input[name="no_of_installment"]').val(data.details
                        .no_of_installment);
                    $('.editFeeFrequency').find('input[name="installment_period"]').val(data.details
                        .installment_period);
                    $('.editFeeFrequency').find('select[name="freq_status"]').val(data.details
                        .freq_status);
                    $('.editFeeFrequency').modal('show');
                }, 'json');
            });

            // Update Fee Frequency
            $('#update-fee-frequency-form').on('submit', function(e) {
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
                            $('.editFeeFrequency').modal('hide');
                            $('.editFeeFrequency').find('form')[0].reset();
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

            // Delete Fee Frequency
            $(document).on('click', '#deleteFeeFrequencyBtn', function() {
                var fee_frequency_id = $(this).data('id');
                var url = '<?= route('admin.deleteFeeFrequency') ?>';

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You want to delete this fee frequency',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it',
                    cancelButtonText: 'Cancel',
                    showLoaderOnConfirm: true,
                    preConfirm: function() {
                        // Show the loader overlay
                        $('#loader-overlay').show();

                        return $.post(url, {
                            fee_frequency_id: fee_frequency_id
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
