@extends('dashboard.admin.layouts.admin-layout-with-cdn')
@section('title', 'Fee Amount')
@push('admincss')
    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.2.0/dist/sweetalert2.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endpush

@section('content')
    <style>
        /* Style the table */
        #fee-frequencies-table {
            border: 1px solid #ccc;
            border-collapse: collapse;
            width: 100%;
            margin-top: 10px;
        }

        /* #fee-frequencies-table th {
            background-color: #007bff;
            color: #fff;
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        } */

        #fee-frequencies-table td {
            border: 1px solid #ccc;
            padding: 8px;
        }

        /* Style the Edit and Delete buttons */
        /* .btn-group button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 6px 12px;
            cursor: pointer;
        } */

        .btn-group button:hover {
            background-color: #0056b3;
        }

        /* Style the list within the table cell */
        td ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        td li {
            margin-bottom: 5px;
        }

        /* Add space between the button group and cell content */
        td .btn-group {
            margin-top: 10px;
        }
    </style>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ __('language.fee_amount_group') }}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ __('language.dashboard') }}</a></li>
                            <li class="breadcrumb-item">{{ __('language.fee_amount_group') }}</li>
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
                                    {{ __('language.fee_amount_group_list') }}
                                </h3>
                                <div class="card-tools">
                                    <ul class="nav nav-pills ml-auto">
                                        <li class="nav-item">
                                            <button class="btn btn-success btn-sm" data-toggle="modal"
                                                data-target="#addFeeAmountModal">
                                                <i class="fas fa-plus-square mr-1"></i>
                                                {{ __('language.fee_amount_group_add') }}
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body table-responsive">
                                <!-- Your table for fee frequencies listing goes here -->
                                <table class="table table-bordered table-striped table-hover table-sm example"
                                    id="fee-frequencies-table">
                                    <thead style="border-top: 1px solid #b4b4b4">
                                        <th style="width: 15px">#</th>
                                        <th>{{ __('language.fee_amount_group_name') }}</th>
                                        <th>{{ __('language.fee_head_name') }}</th>
                                        <th>{{ __('language.class_name') }}</th>
                                        <th>{{ __('language.academic_year') }}</th>
                                        <th>{{ __('language.status') }}</th>
                                        <th style="width: 40px">{{ __('language.action') }}</th>
                                    </thead>
                                    <tbody>
                                        @if (count($academicFeeAmounts) > 0)
                                            @foreach ($academicFeeAmounts as $feeAmount)
                                                @php
                                                    // Group fee amounts by group name, class name, and academic year
                                                    $groupKey = $feeAmount->academicFeeGroup->aca_group_name . '-' . $feeAmount->class_id . '-' . $feeAmount->academic_year;
                                                    $groupedFeeAmounts[$groupKey][] = [
                                                        'fee_head_name' => $feeAmount->academicFeeHead->aca_feehead_name,
                                                        'amount' => $feeAmount->amount,
                                                        'id' => $feeAmount->id,
                                                    ];
                                                @endphp
                                            @endforeach

                                            @foreach ($groupedFeeAmounts as $groupKey => $feeAmounts)
                                                @php
                                                    // Split the group key to extract group name, class name, and academic year
                                                    [$groupName, $className, $academicYear] = explode('-', $groupKey);

                                                    $groupIds = [];
                                                    foreach ($feeAmounts as $fee) {
                                                        $groupIds[] = $fee['id'];
                                                    }

                                                    // Create a comma-separated string of 'id' values
                                                    $groupIdsString = implode(',', $groupIds);

                                                    $class = \App\Models\Admin\EduClasses::find($className[0]);

                                                @endphp
                                                <tr data-row-id="{{ $loop->iteration }}">
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $groupName }}</td>
                                                    <td>
                                                        <ul>
                                                            @foreach ($feeAmounts as $fee)
                                                                <li>{{ $fee['fee_head_name'] }} - {{ $fee['amount'] }}
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </td>
                                                    <td>{{ $class->class_name . ' - ' . $class->version->version_name }}
                                                    </td>
                                                    <td>{{ $academicYear }}</td>
                                                    <td></td>
                                                    <!-- You may leave this column empty or display some other data if needed -->
                                                    <td>
                                                        <div class="btn-group">
                                                            <button type="button"
                                                                class="btn btn-warning btn-xs edit-group-button"
                                                                data-toggle="modal" data-target="#editGroupModal"
                                                                data-id="{{ $groupIdsString }}"
                                                                data-row-id="{{ $loop->iteration }}">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-danger btn-xs"
                                                                data-id="{{ $groupIdsString }}" id="deleteFeeAmountBtn">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7">No data available.</td>
                                            </tr>
                                        @endif

                                    </tbody>
                                </table>




                            </div>
                        </div>
                    </div>
                </div>


                <!-- Add Fee Amount Modal -->
                <div class="modal fade" id="addFeeAmountModal" tabindex="-1" aria-labelledby="addFeeAmountModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-success">
                                <h5 class="modal-title" id="addFeeAmountModalLabel">
                                    {{ __('language.fee_amount_group_add') }}
                                </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('admin.addAcademicFeeAmount') }}" method="POST" autocomplete="off"
                                    id="add-fee-amount-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="freq_name">{{ __('language.aca_fee_group') }}</label>
                                                <select class="form-control form-control-sm" name="aca_group_id"
                                                    id="aca_feegroup_id">
                                                    <option value="">Select</option>
                                                    @foreach ($feeGroups as $fgroup)
                                                        <option value="{{ $fgroup->id }}">{{ $fgroup->aca_group_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger error-text freq_name_error"></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="academic_year">{{ __('language.academic_year') }}</label>
                                                <select class="form-control form-control-sm" name="academic_year"
                                                    id="academic_yeasr">
                                                    @php
                                                        $currentYear = date('Y');
                                                    @endphp
                                                    @for ($i = $currentYear - 5; $i <= $currentYear + 5; $i++)
                                                        <option value="{{ $i }}" <?php if ($i == $currentYear) {
                                                            echo 'selected';
                                                        } ?>>
                                                            {{ $i }} - {{ $i + 1 }}
                                                        </option>
                                                    @endfor
                                                </select>
                                                <span class="text-danger error-text academic_year_error"></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="classes">{{ __('language.class') }}</label>
                                                <select class="form-control form-control-sm select2" multiple="multiple" name="class_id[]" style="width: 100%;" >
                                                    <option>Select Class (Multiple)</option>
                                                    @foreach ($classes as $class)
                                                        <option value="{{ $class->id }}">{{ $class->class_name }}
                                                        </option>
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

                                    <button type="submit" class="btn btn-success">{{ __('language.save') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Edit Group Modal -->
                <div class="modal fade editmodal" id="editGroupModal" tabindex="-1" role="dialog"
                    aria-labelledby="editGroupModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-purple">
                                <h5 class="modal-title" id="editGroupModalLabel">
                                    {{ __('language.fee_amount_group_edit') }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('admin.updateAcademicFeeAmountDetails') }}" method="post"
                                    autocomplete="off" id="update-fee-amount-form">
                                    @csrf
                                    <div id="dynamicInputFields">
                                        <!-- Input fields will be added here -->
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.2.0/dist/sweetalert2.min.js"></script>

    <!-- Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


    <script>
        new DataTable('#data-table');
    </script>


    <script>
        $(document).ready(function() {
            $('#aca_feegroup_id').change(function() {
                var feeGroupId = $(this).val();

                // Make an AJAX request to get data based on the selected "Fee Group."
                $.ajax({
                    type: 'GET',
                    url: "{{ route('admin.get-fee-heads') }}",
                    data: {
                        feeGroupId: feeGroupId
                    },
                    dataType: 'json',
                    success: function(data) {
                        // Clear existing input fields
                        $('#feeHeadInputsContainer').empty();

                        // Create input fields based on the fetched data
                        var colCounter = 0;
                        var currentRow;

                        $.each(data.feeHeads, function(index, feeHead) {
                            if (colCounter === 0) {
                                currentRow = $('<div class="row">');
                            }

                            var colDiv = $(
                                '<div class="col-md-6"><div class="form-group">');

                            var label = $('<label>')
                                .text(feeHead.aca_feehead_name);

                            var inputField = $('<input>')
                                .attr('type', 'number')
                                .addClass('form-control form-control-sm')
                                .attr('name', 'aca_feehead_id[' + feeHead.id + ']')
                                .attr('placeholder', 'amount')
                                .attr('required', 'required');

                            var errorText = $('<span>')
                                .addClass(
                                    'text-danger error-text installment_period_error');

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
                    error: function(error) {
                        console.log(error);
                    }
                });
            });
        });
    </script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            // Handle fee amount form submission
            $('#add-fee-amount-form').on('submit', function(e) {
                e.preventDefault();
                var form = this;

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
                        // Clear any previous error messages
                        $(form).find('span.error-text').text('');
                    },
                    success: function(data) {
                        if (data.code == 0) {
                            // Handle validation errors
                            $.each(data.error, function(prefix, val) {
                                $(form).find('span.' + prefix + '_error').text(val[0]);
                            });
                        } else {
                            // Handle successful fee amount addition
                            var redirectUrl = data.redirect;

                            // Hide the loader overlay on success
                            $('#loader-overlay').hide();

                            $('#addFeeAmountModal').modal('hide');
                            $('#add-fee-amount-form')[0].reset();
                            toastr.success(data.msg);

                            setTimeout(function() {
                                window.location.href = redirectUrl;
                            }, 1000);
                        }
                    },
                    complete: function() {
                        // Hide the loader overlay on completion (success or failure)
                        $('#loader-overlay').hide();
                    }
                });
            });



        });

        $(document).on('click', '.edit-group-button', function() {
            var aca_feehead_ids = $(this).data('id');
            var rowId = $(this).data('row-id');

            $.get("{{ route('admin.getGroupData') }}", {
                aca_feehead_ids: aca_feehead_ids
            }, function(data) {
                $('#editGroupModal').modal('show');
                var inputFieldsHtml = '';

                // Create two columns for input fields
                var column1 = '';
                var column2 = '';

                data.data.forEach((item, index) => {
                    // Decide which column to place the input field
                    if (index % 2 === 0) {
                        column1 += `
                            <div class="form-group">
                                <label for="amount_${item.id}">${item.fee_head_name}</label>
                                <input type="text" class="form-control form-control-sm" required id="amount_${item.id}" name="amount_id['${item.id}']" value="${item.amount}">
                            </div>`;
                    } else {
                        column2 += `
                            <div class="form-group">
                                <label for="amount_${item.id}">${item.fee_head_name}</label>
                                <input type="text" class="form-control form-control-sm" required id="amount_${item.id}" name="amount_id['${item.id}']" value="${item.amount}">
                            </div>`;
                    }
                });

                // Add the columns to the modal
                inputFieldsHtml += `<div class="row">
                    <div class="col-md-6">${column1}</div>
                    <div class="col-md-6">${column2}</div>
                </div>`;

                $('#dynamicInputFields').html(inputFieldsHtml);
            }, 'json');
        });


        // Handle fee amount form submission
        $('#update-fee-amount-form').on('submit', function(e) {
            e.preventDefault();
            var form = this;

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
                    // Clear any previous error messages
                    $(form).find('span.error-text').text('');
                },
                success: function(data) {
                    if (data.code == 0) {
                        // Handle validation errors
                        $.each(data.error, function(prefix, val) {
                            $(form).find('span.' + prefix + '_error').text(val[0]);
                        });
                    } else {
                        // Handle successful fee frequency update
                        var redirectUrl = data.redirect;

                        // Hide the loader overlay on success
                        $('#loader-overlay').hide();

                        $('.editmodal').modal('hide');
                        $('.editmodal').find('form')[0].reset();
                        toastr.success(data.msg);

                        setTimeout(function() {
                            window.location.href = redirectUrl;
                        }, 1000);
                    }
                },
                complete: function() {
                    // Hide the loader overlay on completion (success or failure)
                    $('#loader-overlay').hide();
                }
            });
        });

        // Handle fee amount deletion
        $(document).on('click', '#deleteFeeAmountBtn', function() {
            var fee_amount_id = $(this).data('id');
            var url = '<?= route('admin.deleteAcademicFeeAmount') ?>';

            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to delete this fee amount group',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Cancel',
                showLoaderOnConfirm: true,
                preConfirm: function() {
                    // Show the loader overlay
                    $('#loader-overlay').show();

                    return $.post(url, {
                        fee_amount_id: fee_amount_id
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
    </script>
    <script>
        $(document).ready(function() {
            $('.example').DataTable();
            $('.select2').select2();
        });
    </script>
@endpush
