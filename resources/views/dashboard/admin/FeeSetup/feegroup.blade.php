@extends('dashboard.admin.layouts.admin-layout-with-cdn')
@section('title', 'Fee Group')
@push('admincss')
<!-- DataTables -->
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.2.0/dist/sweetalert2.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap4-duallistbox/4.0.2/bootstrap-duallistbox.min.css">
@endpush

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('language.aca_fee_group') }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ __('language.aca_fee_group') }}</a></li>
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
                                {{ __('language.fee_group_list') }}
                            </h3>
                            <div class="card-tools">
                                <ul class="nav nav-pills ml-auto">
                                    <li class="nav-item">
                                        <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#addFeeGroupModal">
                                            <i class="fas fa-plus-square mr-1"></i> {{ __('language.fee_group_add') }}
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body table-responsive">
                            <!-- Your table for fee groups listing goes here -->
                            <table class="table table-bordered table-striped table-hover table-sm" id="fee-groups-table">
                                <thead style="border-top: 1px solid #b4b4b4">
                                    <th style="width: 15px">#</th>
                                    <th>{{ __('language.fee_group_name') }}</th>
                                    <th>{{ __('language.aca_fee_head') }}</th>
                                    <th>{{ __('language.academic_year') }}</th>
                                    <th>{{ __('language.status') }}</th>
                                    <th style="width: 40px">{{ __('language.action') }}</th>
                                </thead>
                                <tbody>
                                    <!-- Loop through fee groups and display them here -->
                                    @foreach ($academicFeeGroups as $feeGroup)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="font-weight-bold">{{ $feeGroup->aca_group_name }}</td>
                                        <td>{{ $feeGroup->aca_feehead_id }}</td>
                                        <td>{{ $feeGroup->academic_year }}</td>
                                        <td class="{{ $feeGroup->aca_group_status == 1 ? 'text-success' : 'text-danger' }} font-weight-bold">
                                            {{ $feeGroup->aca_group_status == 1 ? 'Active' : 'Inactive' }}
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-warning btn-xs" data-id="{{ $feeGroup->id }}" id="editFeeGroupBtn">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger btn-xs" data-id="{{ $feeGroup->id }}" id="deleteFeeGroupBtn">
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

<!-- Add Fee Group Modal with Dual List Box -->
<div class="modal fade" id="addFeeGroupModal" tabindex="-1" role="dialog" aria-labelledby="addFeeGroupModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title" id="addFeeGroupModalLabel">{{ __('language.fee_group_add') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addFeeGroupForm" action="{{ route('admin.addAcademicFeeGroup') }}" method="POST" autocomplete="off"  id="add-fee-group-form">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="aca_group_name">{{ __('language.fee_group_name') }}</label>
                                <input type="text" class="form-control" id="aca_group_name" name="aca_group_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="academic_year">{{ __('language.fee_group_academic_year') }}</label>
                                <input type="number" class="form-control" id="academic_year" name="academic_year" required>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Dual List Box for Fee Heads -->
                    <div class="form-group">
                        <label>{{ __('language.aca_fee_head') }}</label>
                        <select multiple="multiple" class="duallistbox" id="aca_feehead_ids" name="aca_feehead_ids[]">
                            @foreach ($feeHeads as $feeHead)
                                <option value="{{ $feeHead->id }}">{{ $feeHead->aca_feehead_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="aca_group_status">{{ __('language.status') }}</label>
                        <select class="form-control" id="aca_group_status" name="aca_group_status" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="addFeeGroupForm" class="btn btn-success">{{ __('language.save') }}</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Fee Group Modal -->
<div class="modal fade editFeeGroup" id="editFeeGroupModal" tabindex="-1" role="dialog" aria-labelledby="editFeeGroupModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-navy">
                <h5 class="modal-title" id="editFeeGroupModalLabel">{{ __('language.fee_group_edit') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="update-fee-group-form" method="post" action="{{ route('admin.updateAcademicFeeGroupDetails') }}">
                @csrf
                <div class="modal-body">
                    <!-- Error Messages -->
                    <div class="alert alert-danger" style="display: none" id="editFeeGroupErrorAlert">
                        <ul id="editFeeGroupErrorList"></ul>
                    </div>

                    <!-- Fee Group ID (hidden input) -->
                    <input type="hidden" name="fee_group_id" id="editFeeGroupId">

                    <!-- Fee Group Name -->
                    <div class="form-group">
                        <label for="editFeeGroupName">{{ __('language.fee_group_name') }}</label>
                        <input type="text" class="form-control" id="editFeeGroupName" name="aca_group_name" required>
                        <span class="error-text aca_group_name_error text-danger"></span>
                    </div>

                    <!-- Academic Year -->
                    <div class="form-group">
                        <label for="editAcademicYear">{{ __('language.academic_year') }}</label>
                        <input type="text" class="form-control" id="editAcademicYear" name="academic_year" required>
                        <span class="error-text academic_year_error text-danger"></span>
                    </div>

                    <!-- Status -->
                    <div class="form-group">
                        <label for="editAcaGroupStatus">{{ __('language.status') }}</label>
                        <select class="form-control" id="editAcaGroupStatus" name="aca_group_status" required>
                            <option value="1">{{ __('language.active') }}</option>
                            <option value="0">{{ __('language.inactive') }}</option>
                        </select>
                        <span class="error-text aca_group_status_error text-danger"></span>
                    </div>

                    <div class="form-group">
                        <label for="editAcaFeeheadIds">{{ __('language.fee_head_list') }}</label>
                        <select class="duallistbox" id="feeHeadsDualList" name="aca_feehead_ids[]" multiple="multiple">
                            @foreach ($feeHeads as $feeHead)
                                <option value="{{ $feeHead->id }}">{{ $feeHead->aca_feehead_name }}</option>
                            @endforeach
                        </select>
                        
                        <span class="error-text aca_feehead_ids_error text-danger"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ __('language.save_changes') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

@push('adminjs')
<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.2.0/dist/sweetalert2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap4-duallistbox/4.0.2/jquery.bootstrap-duallistbox.min.js"></script>    
<script>
    $(function () {
        // Initialize the Dual List Box
        $('.duallistbox').bootstrapDualListbox();
    });
</script>   
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function () {
        // Handle fee group form submission
        $('#add-fee-group-form').on('submit', function (e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function () {
                    $(form).find('span.error-text').text('');
                },
                success: function (data) {
                    if (data.code == 0) {
                        // Handle validation errors
                        $.each(data.error, function (prefix, val) {
                            $(form).find('span.' + prefix + '_error').text(val[0]);
                        });
                    } else {
                        // Handle successful fee group addition
                        var redirectUrl = data.redirect;
                        $('#addFeeGroupModal').modal('hide');
                        $('#add-fee-group-form')[0].reset();
                        toastr.success(data.msg);

                        setTimeout(function () {
                            window.location.href = redirectUrl;
                        }, 1000); // Redirect after 1 second

                    }
                }
            });
        });

        $(document).on('click', '#editFeeGroupBtn', function () {
            var feeGroupId = $(this).data('id');
            $('.editFeeGroup').find('form')[0].reset();
            $('.editFeeGroup').find('span.error-text').text('');
            $.post("{{ route('admin.getAcademicFeeGroupDetails') }}", { fee_group_id: feeGroupId }, function (data) {
                $('.editFeeGroup').find('input[name="fee_group_id"]').val(data.details.id);
                $('.editFeeGroup').find('input[name="aca_group_name"]').val(data.details.aca_group_name);
                $('.editFeeGroup').find('input[name="academic_year"]').val(data.details.academic_year);
                $('.editFeeGroup').find('select[name="aca_group_status"]').val(data.details.aca_group_status);

                // Convert comma-separated fee heads to an array
                var feeHeads = data.details.aca_feehead_id.split(',');
                $('.editFeeGroup').find('select[name="aca_feehead_ids"]').val(feeHeads);

                
                

                $('.editFeeGroup').modal('show');
            }, 'json');
        });

        $('#update-fee-group-form').on('submit', function (e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function () {
                    $(form).find('span.error-text').text('');
                },
                success: function (data) {
                    if (data.code == 0) {
                        // Handle validation errors
                        $.each(data.error, function (prefix, val) {
                            $(form).find('span.' + prefix + '_error').text(val[0]);
                        });
                    } else {
                        // Handle successful fee group update
                        var redirectUrl = data.redirect;
                        $('.editFeeGroup').modal('hide');
                        $('.editFeeGroup').find('form')[0].reset();
                        toastr.success(data.msg);

                        setTimeout(function () {
                            window.location.href = redirectUrl;
                        }, 1000);
                    }
                }
            });
        });

        // Handle fee group deletion
        $(document).on('click', '#deleteFeeGroupBtn', function () {
            var feeGroupId = $(this).data('id');
            var url = '<?= route("admin.deleteAcademicFeeGroup"); ?>';
            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to delete this fee group',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Cancel',
            }).then(function (result) {
                if (result.isConfirmed) {
                    $.post(url, { fee_group_id: feeGroupId }, function (data) {
                        if (data.code == 1) {
                            var redirectUrl = data.redirect;
                            toastr.success(data.msg);
                            setTimeout(function () {
                                window.location.href = redirectUrl;
                            }, 1000);
                        } else {
                            toastr.error(data.msg);
                        }
                    }, 'json');
                }
            });
        });
    });
</script>

@endpush
