@extends('dashboard.admin.layouts.admin-layout-with-cdn')
@section('title', 'Supplier Management')

@push('admincss')
<!-- Add CSS dependencies for suppliers here -->
<!-- DataTables -->
<link href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css" rel="stylesheet">
<!-- SweetAlert2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.2.0/dist/sweetalert2.min.css">
<!-- Toastr -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endpush

@section('content')
<style>
    .modal {
        z-index: 9999;
    }

    .background-content {
        pointer-events: none;
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('language.supplier') }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ __('language.dashboard') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('language.supplier') }}</li>
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
                                {{ __('language.supplier_list') }}
                            </h3>
                            <div class="card-tools">
                                <ul class="nav nav-pills ml-auto">
                                    <li class="nav-item">
                                        <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#addSupplierModal">
                                            <i class="fas fa-plus-square mr-1"></i> {{ __('language.supplier_add') }}
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body table-responsive">
                            <!-- Your table for supplier listing goes here -->
                            <table class="table table-bordered table-striped table-hover table-sm" id="supplier-table">
                                <thead style="border-top: 1px solid #b4b4b4">
                                    <th style="width: 15px">#</th>
                                    <th>{{ __('language.supplier_name') }}</th>
                                    <th>{{ __('language.supplier_address') }}</th>
                                    <th>{{ __('language.supplier_phone') }}</th>
                                    <th>{{ __('language.supplier_email') }}</th>
                                    <th>{{ __('language.status') }}</th>
                                    <th style="width: 40px">{{ __('language.action') }}</th>
                                </thead>
                                <tbody>
                                    <!-- Loop through suppliers and display them here -->
                                    @foreach ($suppliers as $supplier)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="font-weight-bold">{{ $supplier->supplier_name }}</td>
                                        <td>{{ $supplier->supplier_address }}</td>
                                        <td>{{ $supplier->supplier_phone }}</td>
                                        <td>{{ $supplier->supplier_email }}</td>
                                        <td class="{{ $supplier->supplier_status == 1 ? 'text-success' : 'text-danger' }} font-weight-bold">
                                            {{ $supplier->supplier_status == 1 ? __('language.active') : __('language.inactive') }}
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-warning btn-xs" data-id="{{ $supplier->id }}" id="editSupplierBtn">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger btn-xs" data-id="{{ $supplier->id }}" id="deleteSupplierBtn">
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
    <!-- /.content -->
</div>

<!-- Add Supplier Modal -->
<!-- Add Supplier Modal -->
<div class="modal fade" id="addSupplierModal" tabindex="-1" aria-labelledby="addSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title" id="addSupplierModalLabel">{{ __('language.supplier_add') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.addSupplier') }}" method="POST" autocomplete="off" id="add-supplier-form">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="supplier_name">{{ __('language.supplier_name') }}</label>
                                <input type="text" class="form-control form-control-sm" name="supplier_name" id="supplier_name" placeholder="{{ __('language.supplier_name') }}">
                                <span class="text-danger error-text supplier_name_error"></span>
                            </div>
                            <div class="form-group">
                                <label for="supplier_address">{{ __('language.supplier_address') }}</label>
                                <input type="text" class="form-control form-control-sm" name="supplier_address" id="supplier_address" placeholder="{{ __('language.supplier_address') }}">
                                <span class="text-danger error-text supplier_address_error"></span>
                            </div>
                            <div class="form-group">
                                <label for="supplier_phone">{{ __('language.supplier_phone') }}</label>
                                <input type="text" class="form-control form-control-sm" name="supplier_phone" id="supplier_phone" placeholder="{{ __('language.supplier_phone') }}">
                                <span class="text-danger error-text supplier_phone_error"></span>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="supplier_email">{{ __('language.supplier_email') }}</label>
                                <input type="email" class="form-control form-control-sm" name="supplier_email" id="supplier_email" placeholder="{{ __('language.supplier_email') }}">
                                <span class="text-danger error-text supplier_email_error"></span>
                            </div>

                            <div class="form-group">
                                <label for="supplier_status">{{ __('language.status') }}</label>
                                <select class="form-control form-control-sm" name="supplier_status" id="supplier_status">
                                    <option value="1">{{ __('language.active') }}</option>
                                    <option value="0">{{ __('language.inactive') }}</option>
                                </select>
                                <span class="text-danger error-text supplier_status_error"></span>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success">{{ __('language.save') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Edit Supplier Modal -->
<div class="modal fade editSupplier" tabindex="-1" role="dialog" aria-labelledby="editSupplierLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-purple">
                <h5 class="modal-title" id="editSupplierLabel">{{ __('language.supplier_edit') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.updateSupplierDetails') }}" method="post" autocomplete="off" id="update-supplier-form">
                    @csrf
                    <input type="hidden" name="supplier_id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="supplier_name">{{ __('language.supplier_name') }}</label>
                                <input type="text" class="form-control form-control-sm" name="supplier_name" id="supplier_name" placeholder="{{ __('language.supplier_name') }}">
                                <span class="text-danger error-text supplier_name_error"></span>
                            </div>
                            <div class="form-group">
                                <label for="supplier_address">{{ __('language.supplier_address') }}</label>
                                <input type="text" class="form-control form-control-sm" name="supplier_address" id="supplier_address" placeholder="{{ __('language.supplier_address') }}">
                                <span class="text-danger error-text supplier_address_error"></span>
                            </div>
                            <div class="form-group">
                                <label for="supplier_phone">{{ __('language.supplier_phone') }}</label>
                                <input type="text" class="form-control form-control-sm" name="supplier_phone" id="supplier_phone" placeholder="{{ __('language.supplier_phone') }}">
                                <span class="text-danger error-text supplier_phone_error"></span>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="supplier_email">{{ __('language.supplier_email') }}</label>
                                <input type="email" class="form-control form-control-sm" name="supplier_email" id="supplier_email" placeholder="{{ __('language.supplier_email') }}">
                                <span class="text-danger error-text supplier_email_error"></span>
                            </div>

                            <div class="form-group">
                                <label for="supplier_status">{{ __('language.status') }}</label>
                                <select class="form-control form-control-sm" name="supplier_status" id="supplier_status">
                                    <option value="1">{{ __('language.active') }}</option>
                                    <option value="0">{{ __('language.inactive') }}</option>
                                </select>
                                <span class="text-danger error-text supplier_status_error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-block bg-purple">{{ __('language.update') }}</button>
                    </div>
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
        // Handle supplier form submission
        $('#add-supplier-form').on('submit', function(e) {
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
                        // Handle successful supplier addition
                        var redirectUrl = data.redirect;
                        $('#addSupplierModal').modal('hide');
                        $('#add-supplier-form')[0].reset();
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


        $(document).on('click', '#editSupplierBtn', function() {
            var supplier_id = $(this).data('id');
            $('.editSupplier').find('form')[0].reset();
            $('.editSupplier').find('span.error-text').text('');
            $.post("{{ route('admin.getSupplierDetails') }}", {
                supplier_id: supplier_id
            }, function(data) {
                $('.editSupplier').find('input[name="supplier_id"]').val(data.details.id);
                $('.editSupplier').find('input[name="supplier_name"]').val(data.details.supplier_name);
                $('.editSupplier').find('input[name="supplier_address"]').val(data.details.supplier_address);
                $('.editSupplier').find('input[name="supplier_phone"]').val(data.details.supplier_phone);
                $('.editSupplier').find('input[name="supplier_email"]').val(data.details.supplier_email);
                $('.editSupplier').find('select[name="supplier_status"]').val(data.details.supplier_status);
                $('.editSupplier').modal('show');
            }, 'json');
        });

        $('#update-supplier-form').on('submit', function(e) {
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
                        // Handle successful supplier update
                        var redirectUrl = data.redirect;
                        $('.editSupplier').modal('hide');
                        $('.editSupplier').find('form')[0].reset();
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

        // Handle supplier deletion
        $(document).on('click', '#deleteSupplierBtn', function() {
            var supplier_id = $(this).data('id');
            var url = '<?= route("admin.deleteSupplier"); ?>';

            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to delete this supplier',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Cancel',
                showLoaderOnConfirm: true,
                preConfirm: function() {
                    // Show the loader overlay
                    $('#loader-overlay').show();

                    return $.post(url, {
                        supplier_id: supplier_id
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