@extends('dashboard.admin.layouts.admin-layout-with-cdn')
@section('title', 'Class Management')

@push('admincss')
    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
@endpush

@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ __('language.class_mgmt') }}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">

                        </ol>
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a
                                    href="{{ route('admin.home') }}">{{ __('language.dashboard') }}</a></li>
                            <li class="breadcrumb-item">{{ __('language.class_mgmt') }}</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">

                        <div class="card card-outline">
                            <div class="card-header bg-navy">
                                <h3 class="card-title">
                                    <i class="fas fa-chalkboard-teacher mr-1"></i>
                                    {{ __('language.class_list') }}
                                </h3>
                                <div class="card-tools">
                                    <ul class="nav nav-pills ml-auto">
                                        <li class="nav-item">

                                            <button class="btn btn-success btn-sm" id="printButton"><i
                                                    class="fas fa-plus-square mr-1"></i>
                                                {{ __('language.print_report') }}</button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body table-responsive">
                                <div class="alert alert-danger" id="errorAlert" style="display: none;">
                                    <ul id="errorList">
                                    </ul>
                                </div>
                                <div id="reportDiv">
                                    <table class="table table-bordered table-striped table-hover table-sm" id="class-table">
                                        <thead style="border-top: 1px solid #b4b4b4">
                                            <th style="width: 15px">#</th>
                                            <th>{{ __('language.fee_amount_group_name') }}</th>
                                            <th>{{ __('language.fee_head_name') }}</th>
                                            <th>{{ __('language.amount') }}</th>
                                            <th>{{ __('language.class_name') }}</th>
                                            <th>{{ __('language.academic_year') }}</th>
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
                                                            <ul style="list-style-type:none">
                                                                @foreach ($feeAmounts as $fee)
                                                                    <li>{{ $fee['fee_head_name'] }}
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </td>
                                                        <td>
                                                            <ul style="list-style-type:none">
                                                                @foreach ($feeAmounts as $fee)
                                                                    <li>{{ $fee['amount'] }}
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </td>
                                                        <td>{{ $class->class_name . ' - ' . $class->version->version_name }}
                                                        </td>
                                                        <td>{{ $academicYear }}</td>
                                                        <!-- You may leave this column empty or display some other data if needed -->

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
                </div>

            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

@push('adminjs')
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script>
        $('#printButton').click(function() {
            var data = $('#reportDiv').html();

            // Show the loader overlay
            $('#loader-overlay').show();

            $.ajax({
                url: '/admin/generate-pdf',
                method: 'POST',
                data: {
                    pdf_data: data,
                    title: 'Gropu Wise Fee Amount List',
                    orientation: 'P',
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.pdf_url && isValidUrl(response.pdf_url)) {
                        // Create a modal element
                        var modalContent =
                            '<div class="modal fade modal-fullscreen" id="pdfModal" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel" aria-hidden="true">';
                        modalContent +=
                            '<div class="modal-dialog modal-dialog-centered modal-lg" role="document">';
                        modalContent += '<div class="modal-content">';
                        modalContent += '<div class="modal-header">';
                        modalContent += '<h5 class="modal-title" id="pdfModalLabel">Generated Report</h5>';
                        modalContent +=
                            '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
                        modalContent += '<span aria-hidden="true">&times;</span>';
                        modalContent += '</button>';
                        modalContent += '</div>';
                        modalContent += '<div class="modal-body">';
                        modalContent +=
                            '<div id="pdfLoaderOverlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.8); display: flex; justify-content: center; align-items: center;">';
                        modalContent += '<img src="/path/to/loader.gif" alt="Loader">';
                        modalContent += '</div>';
                        modalContent += '<iframe id="pdfIframe" src="' + response.pdf_url +
                            '" style="width: 100%; height: 80vh; display: none;"></iframe>';
                        modalContent += '</div>';
                        modalContent += '</div>';
                        modalContent += '</div>';
                        modalContent += '</div>';

                        // Append modal to the body and show it
                        $('body').append(modalContent);
                        $('#pdfModal').modal('show');

                        // Hide the loader overlay when the PDF is loaded
                        $('#pdfIframe').on('load', function() {
                            $('#pdfLoaderOverlay').hide();
                            $('#pdfIframe').show();
                        });

                        console.log('PDF generated successfully');
                    } else {
                        console.error('Invalid PDF response:', response);
                        alert('Error generating PDF. Please try again.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX request failed:', error);
                    alert('Error generating PDF. Please try again.');
                },
                complete: function() {
                    // Hide the loader overlay when the request is complete
                    $('#loader-overlay').hide();
                }
            });
        });

        function isValidUrl(url) {
            // Implement a function to check if the URL is valid based on your requirements
            return /^https?:\/\/.+/.test(url);
        }
    </script>
@endpush
