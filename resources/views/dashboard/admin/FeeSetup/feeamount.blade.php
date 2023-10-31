@extends('dashboard.admin.layouts.admin-layout-with-cdn')
@section('title',  'Fee Amount')
@push('admincss')
<!-- DataTables -->
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.2.0/dist/sweetalert2.min.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
@endpush

@section('content')
     <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Fee Amount</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Fee Amount</a></li>
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
                            Amount List
                        </h3>
                        <div class="card-tools">
                            <ul class="nav nav-pills ml-auto">
                                <li class="nav-item">
                                    <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#addFeeAmountModal">
                                        <i class="fas fa-plus-square mr-1"></i> Add Amount
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <!-- Your table for fee frequencies listing goes here -->
                        <table class="table table-bordered table-striped table-hover table-sm" id="fee-frequencies-table">
                            <thead style="border-top: 1px solid #b4b4b4">
                                <th style="width: 15px">#</th>
                                <th>{{ __('language.fee_group_name') }}</th>
                                <th>{{ __('language.fee_head_name') }}</th>
                                <th>{{ __('language.class_name') }}</th>
                                <th>{{ __('language.amount') }}</th>
                                <th>{{ __('language.academic_year') }}</th>
                                <th>{{ __('language.status') }}</th>
                                <th style="width: 40px">{{ __('language.action') }}</th>
                            </thead>
                            <tbody>
                                <!-- Loop through fee frequencies and display them here -->
                                @foreach ($academicFeeAmounts as $feeAmount)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $feeAmount->academicFeeGroup->aca_group_name }}</td>
                                    <td>{{ $feeAmount->academicFeeHead->aca_feehead_name }}</td>
                                    <td>{{ $feeAmount->eduClass->class_name }}</td>
                                    <td>{{ $feeAmount->amount }}</td>
                                    <td>{{ $feeAmount->academic_year }}</td>
                                    <td class="{{ $feeAmount->aca_feeamount_status == 1 ? 'text-success' : 'text-danger' }} font-weight-bold">
                                        {{ $feeAmount->aca_feeamount_status == 1 ? 'Active' : 'Inactive' }}
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-warning btn-xs" data-id="{{ $feeAmount->id }}" id="editFeeFrequencyBtn">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-xs" data-id="{{ $feeAmount->id }}" id="deleteFeeFrequencyBtn">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <table class="table table-hover">
                            <tbody>
                            <tr>
                            <td class="border-0">183</td>
                            </tr>
                            <tr data-widget="expandable-table" aria-expanded="false">
                            <td>
                            <i class="expandable-table-caret fas fa-caret-right fa-fw"></i>
                            219
                            </td>
                            </tr>
                            <tr class="expandable-body">
                            <td>
                            <div class="p-0" style="">
                            <table class="table table-hover">
                            <tbody>
                            <tr data-widget="expandable-table" aria-expanded="false">
                            <td>
                            <i class="expandable-table-caret fas fa-caret-right fa-fw"></i>
                            219-1
                            </td>
                            </tr>
                            <tr class="expandable-body d-none">
                            <td>
                            <div class="p-0" style="display: none;">
                            <table class="table table-hover">
                            <tbody>
                            <tr>
                            <td>219-1-1</td>
                            </tr>
                            <tr>
                            <td>219-1-2</td>
                            </tr>
                            <tr>
                            <td>219-1-3</td>
                            </tr>
                            </tbody>
                            </table>
                            </div>
                            </td>
                            </tr>
                            <tr data-widget="expandable-table" aria-expanded="false">
                            <td>
                            <button type="button" class="btn btn-primary p-0">
                            <i class="expandable-table-caret fas fa-caret-right fa-fw"></i>
                            </button>
                            219-2
                            </td>
                            </tr>
                            <tr class="expandable-body d-none">
                            <td>
                            <div class="p-0" style="display: none;">
                            <table class="table table-hover">
                            <tbody>
                            <tr>
                            <td>219-2-1</td>
                            </tr>
                            <tr>
                            <td>219-2-2</td>
                            </tr>
                            <tr>
                            <td>219-2-3</td>
                            </tr>
                            </tbody>
                            </table>
                            </div>
                            </td>
                            </tr>
                            <tr>
                            <td>219-3</td>
                            </tr>
                            </tbody>
                            </table>
                            </div>
                            </td>
                            </tr>
                            <tr>
                            <td>657</td>
                            </tr>
                            <tr>
                            <td>175</td>
                            </tr>
                            <tr>
                            <td>134</td>
                            </tr>
                            <tr>
                            <td>494</td>
                            </tr>
                            <tr>
                            <td>832</td>
                            </tr>
                            <tr>
                            <td>982</td>
                            </tr>
                            </tbody>
                            </table>
                    </div>
                </div>
            </div>
        </div>
        
        
<!-- Add Fee Frequency Modal -->
<div class="modal fade" id="addFeeAmountModal" tabindex="-1" aria-labelledby="addFeeFrequencyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title" id="addFeeAmountModalLabel">{{ __('language.fee_frequency_add') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.addAcademicFeeAmount') }}" method="POST" autocomplete="off" id="add-fee-amount-form">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="freq_name">Fee Group</label>
                                <select class="form-control form-control-sm" name="aca_group_id" id="aca_feegroup_id">
                                    <option value="">Select</option>
                                    @foreach($feeGroups as $fgroup)
                                        <option value="{{ $fgroup->id }}">{{ $fgroup->aca_group_name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger error-text freq_name_error"></span>
                            </div>
                        
                            <div class="form-group">
                                <label for="academic_year">{{ __('language.academic_year') }}</label>
                                <select class="form-control form-control-sm" name="academic_year" id="academic_yeasr">
                                    @php
                                        $currentYear = date('Y');
                                    @endphp
                                    @for ($i = $currentYear - 5; $i <= $currentYear + 5; $i++)
                                        <option value="{{ $i }}" <?php if($i == $currentYear){echo "selected";}?> >
                                            {{ $i }} - {{ $i + 1 }}
                                        </option>
                                    @endfor
                                </select>
                                <span class="text-danger error-text academic_year_error"></span>
                            </div>
                        
                            <div class="form-group">
                                <label for="classes">{{ __('language.class') }}</label>
                                <select class="select2bs4" multiple="multiple" name="class_id[]" data-placeholder="Select a State" data-dropdown-css-class="select2-purple" style="width: 100%;" data-select2-id="15" tabindex="-1" aria-hidden="true">
                                    <option>Select Class (Multiple)</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                    @endforeach
                                    </select>
                                <span class="text-danger error-text academic_year_error"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div id="feeHeadInputsContainer">
                                <!-- Input fields will be added here -->
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-success">Save</button>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.2.0/dist/sweetalert2.min.js"></script>

<!-- Toastr -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


<script>
  new DataTable('#data-table');
</script>


<script>
    $(document).ready(function () {
        $('#aca_feegroup_id').change(function () {
            var feeGroupId = $(this).val();

            // Make an AJAX request to get data based on the selected "Fee Group."
            $.ajax({
                type: 'GET',
                url: "{{ route('admin.get-fee-heads') }}",
                data: { feeGroupId: feeGroupId },
                dataType: 'json',
                success: function (data) {
                    // Clear existing input fields
                    $('#feeHeadInputsContainer').empty();

                    // Create input fields based on the fetched data
                    var colCounter = 0;
                    var currentRow;

                    $.each(data.feeHeads, function (index, feeHead) {
                        if (colCounter === 0) {
                            currentRow = $('<div class="row">');
                        }

                        var colDiv = $('<div class="col-md-6"><div class="form-group">');

                        var label = $('<label>')
                            .text(feeHead.aca_feehead_name);

                        var inputField = $('<input>')
                            .attr('type', 'number')
                            .addClass('form-control form-control-sm')
                            .attr('name', 'aca_feehead_id[' + feeHead.id + ']')
                            .attr('placeholder', 'amount')
                            .attr('required', 'required');

                        var errorText = $('<span>')
                            .addClass('text-danger error-text installment_period_error');

                        colDiv.append(label);
                        colDiv.append(inputField);
                        colDiv.append(errorText);

                        currentRow.append(colDiv);

                        colCounter++;
                        if (colCounter === 2) {
                            $('#feeHeadInputsContainer').append(currentRow);
                            colCounter = 0;
                        }
                    });

                    if (colCounter === 1) {
                        $('#feeHeadInputsContainer').append(currentRow);
                    }
                },
                error: function (error) {
                    console.log(error);
                }
            });
        });
    });
</script>

<script>
    
    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function () {
        $('#add-fee-amount-form').on('submit', function (e) {
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
                        // Handle successful academic fee amount addition
                        var redirectUrl = data.redirect;
                        $('#addFeeAmountModal').modal('hide');
                        $('#add-fee-amount-form')[0].reset();
                        toastr.success(data.msg);

                        setTimeout(function () {
                            window.location.href = redirectUrl;
                        }, 1000);
                    }
                }
            });
        });

        
    });
</script>


    





    
@endpush

