@extends('student.layouts.student-layout')
@section('title', 'Payment History')
@push('admincss')
<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.2.0/dist/sweetalert2.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<!-- Add your custom CSS here -->

@endpush

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('language.payment_history') }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ __('language.home') }}</a></li>
                        <li class="breadcrumb-item">{{ __('language.my_payment') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-outline">
                        <div class="card-header bg-navy">
                            <h3 class="card-title">
                                <i class="fas fa-chalkboard-teacher mr-1"></i>
                                {{ __('language.payment_history') }}
                            </h3>
                            <div class="card-tools">
                                <ul class="nav nav-pills ml-auto">
                                    <li class="nav-item">
                                        <button class="btn btn-success btn-sm" id="printButton">
                                            <i class="fas fa-plus-square mr-1"></i>
                                            {{ __('language.print_report') }}
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body table-responsive">
                            <div class="alert alert-danger" id="errorAlert" style="display: none;">
                                <ul id="errorList"></ul>
                            </div>
                            <div id="reportDiv">
                                @foreach ($paymentHistory->groupBy('trnx_id') as $trnxId => $payments)
                                <h5>Transaction ID: {{ $trnxId }}</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered payment-table">
                                        <thead>
                                            <tr>
                                                <th>Payment Date</th>
                                                <th>Fee Head</th>
                                                <th>Amount Paid</th>
                                                <th>Payment Method</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($payments as $payment)
                                            <tr>
                                                <td>{{ $payment->payment_date }}</td>
                                                <td>{{ $payment->fee_description }}</td>
                                                <td>{{ $payment->amount_paid }}</td>
                                                <td>{{ $payment->payment_method }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div>





</div>
@endsection

@push('adminjs')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.2.0/dist/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


<script>
    $('#printButton').click(function() {
        var data = $('#reportDiv').html();

        // Show the loader overlay
        $('#loader-overlay').show();

        $.ajax({
            url: '/student/generate-pdf',
            method: 'POST',
            data: {
                pdf_data: data,
                title: 'Payment History',
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