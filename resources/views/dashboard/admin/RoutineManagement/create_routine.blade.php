@extends('dashboard.admin.layouts.admin-layout-with-cdn')
@section('title', 'Fee Head')
@push('admincss')
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.2.0/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<!-- Add your custom CSS here -->
<style>
    .required:after {
        content: " *";
        color: red;
    }

    .card-title {
        font-size: 20px;
        color: white;
        font-family: 'Lucida Sans', 'SolaimanLipi'
    } 
    
  
</style>

@endpush

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('language.fee_head') }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ __('language.fee_head') }}</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-danger alert-dismissible" id="errorAlert" style="display: none;">
                        <button type="button" class="close" id="closeErrorAlert">&times;</button>
                        <span id="errorAlertText"></span>
                    </div>
                    
                    <div class="alert alert-success alert-dismissible" id="completionAlert" style="display: none;">
                        <button type="button" class="close" id="closeCompletionAlert">&times;</button>
                        <span id="completionAlertText"></span>
                    </div>
                    
                    
                        
                        <form action="{{ route('admin.addPeriods') }}" method="POST" autocomplete="off" id="add-period-form">
                            @csrf
                        
                            <div class="card">
                                <div class="card-header bg-gray">
                                    <h3 class="card-title">Academic Details</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="academicYear" class="required">Academic Year:</label>
                                                <select class="form-control form-control-sm academic_year" name="academic_year" id="academic_yeasr">
                                                    <option value="">Academic Year</option>
                                                    @php
                                                        $currentYear = date('Y');
                                                    @endphp
                                                    @for ($i = $currentYear - 5; $i <= $currentYear + 5; $i++)
                                                        <option value="{{ $i }}">
                                                            {{ $i }} - {{ $i + 1 }}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="versionName" class="required">Version Name:</label>
                                                <select class="form-control form-control-sm version_id" name="version_id" id="version_id">
                                                    <option value="">{{ __('language.select_version') }}</option>
                                                    @foreach ($versions as $version)
                                                        <option value="{{ $version->id }}">{{ $version->version_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="className" class="required">Class Name:</label>
                                                <select class="form-control form-control-sm class_id" name="class_id" id="class_id" disabled>
                                                    <option value="">{{ __('language.select_class') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="section_id" class="required">Section Name:</label>
                                                <select id="section_id" name="section_id" class="form-control form-control-sm section_id" disabled>
                                                    <option value="">{{ __('language.select_section') }}</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="section_id" class="required">Total Period (with Tiffin):</label>
                                                <input type="number" name="no_of_period" id="no_of_period" class="form-control form-control-sm" min="1" max="12">
                                            </div>
                                        </div>
                                        
                                    </div>
                                    
                                </div>
                            </div>

                            <div id="periods"></div>
                        
                            
                        
                            <div class="btn-container">
                                <button type="submit" class="btn btn-primary">Finish</button>
                            </div>
                        
                        </form>
                        
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('adminjs')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.2.0/dist/sweetalert2.min.js"></script>
    <!-- Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>

$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


$(document).ready(function() {
    // When the "Academic Year" dropdown changes
    $('.academic_year').on('change', function() {
        var academic_year = $(this).val();
        
        // Enable the "Class" dropdown
        $('.feesetup').prop('disabled', false).data('academic_year', academic_year);
        
        // Add the extra option to the "Class" dropdown
        $('.feesetup').html('<option value="">-- Please select a Fee --</option>');
        
        // Make an AJAX request to fetch classes based on the selected version
        $.ajax({
            url: '{{ route('admin.getFeegroupByAcademicYear') }}',
            method: 'POST',
            data: {
                academic_year: academic_year,
                _token: '{{ csrf_token() }}'
            },
            success: function(data) {
                var feeDropdown = $('.feesetup');
                
                // Populate the "Class" dropdown with the fetched data
                $.each(data.feegroups, function(key, value) {
                    feeDropdown.append($('<option>', {
                        value: value.id,
                        text: value.aca_group_name
                    }));
                });
            }
        });
    });

    // When the "Version" dropdown changes
    $('.version_id').on('change', function() {
        var versionId = $(this).val();
        
        // Enable the "Class" dropdown
        $('.class_id').prop('disabled', false).data('version-id', versionId);
        
        // Add the extra option to the "Class" dropdown
        $('.class_id').html('<option value="">-- Please select a class --</option>');
        
        // Make an AJAX request to fetch classes based on the selected version
        $.ajax({
            url: '{{ route('admin.getClassesByVersion') }}',
            method: 'POST',
            data: {
                version_id: versionId,
                _token: '{{ csrf_token() }}'
            },
            success: function(data) {
                var classDropdown = $('.class_id');
                
                // Populate the "Class" dropdown with the fetched data
                $.each(data.classes, function(key, value) {
                    classDropdown.append($('<option>', {
                        value: value.id,
                        text: value.class_name
                    }));
                });
            }
        });
    });

    // When the "Class" dropdown changes
    $('.class_id').on('change', function() {
        var classId = $(this).val();
        var versionId = $(this).data('version-id'); // Retrieve the version_id
        
        // Enable the "Section" dropdown
        $('.section_id').prop('disabled', false).data('version-id', versionId); // Pass version_id to the Section dropdown
        
        // Add the extra option to the "Section" dropdown
        $('.section_id').html('<option value="">-- Please select a section --</option>');
        
        // Make an AJAX request to fetch sections based on the selected class
        $.ajax({
            url: '{{ route('admin.getSectionByClass') }}',
            method: 'POST',
            data: {
                class_id: classId,
                version_id: versionId, // Pass version_id to the server
                _token: '{{ csrf_token() }}'
            },
            success: function(data) {
                var sectionDropdown = $('.section_id');
                
                // Populate the "Section" dropdown with the fetched data
                $.each(data.sections, function(key, value) {
                    sectionDropdown.append($('<option>', {
                        value: value.id,
                        text: value.section_name
                    }));
                });
            }
        });
    });


    $('#add-period-form').on('submit', function (e) {
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
            beforeSend: function () {
                $(form).find('span.error-text').text('');
                // Remove 'is-invalid' class and error messages on form submission
                $(form).find('.form-control').removeClass('is-invalid');
                $(form).find('.invalid-feedback').text('');
            },
            success: function (data) {
                if (data.code == 0) {
                    $.each(data.error, function (field, messages) {
                        // Add 'is-invalid' class to the input field
                        $(form).find('[name="' + field + '"]').addClass('is-invalid');
                        // Display the first error message for the field
                        $(form).find('span.' + field + '_error').text(messages[0]);
                    });
                } else {
                    var redirectUrl = data.redirect;
                    toastr.success(data.msg);

                    setTimeout(function () {
                        window.location.href = redirectUrl;
                    }, 1000);
                }
            },
            complete: function () {
                // Enable the submit button and hide the loader overlay
                $(form).find(':submit').prop('disabled', false);
                $('#loader-overlay').hide();
            }
        });
    });


});

</script>

<!-- Add this script after your form content -->
<script>
    $(document).ready(function () {
        $('#no_of_period').on('input', function () {
            var noOfPeriod = $(this).val();
            generatePeriodCards(noOfPeriod);
        });

        function generatePeriodCards(noOfPeriod) {
            // Clear existing cards
            $('#periods').empty();

            for (var i = 1; i <= noOfPeriod; i++) {
                var cardHtml = '<div class="card">' +
                    '<div class="card-header bg-info">' +
                    '<h3 class="card-title">Period ' + i + '</h3>' +
                    '</div>' +
                    '<div class="card-body">' +
                    '<div class="row">' +
                    '<div class="col-md-4">' +
                    '<div class="form-group">' +
                    '<label for="periodName' + i + '">Period Name:</label>' +
                    '<input type="text" name="period_name[]" id="periodName' + i + '" class="form-control form-control-sm" value="Period-' + i + '">' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-md-4">' +
                    '<div class="form-group">' +
                    '<label for="startTime' + i + '">Start Time:</label>' +
                    '<input type="time" name="start_time[]" id="startTime' + i + '" class="form-control form-control-sm">' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-md-4">' +
                    '<div class="form-group">' +
                    '<label for="endTime' + i + '">End Time:</label>' +
                    '<input type="time" name="end_time[]" id="endTime' + i + '" class="form-control form-control-sm">' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>';

                $('#periods').append(cardHtml);
            }
        }
    });
</script>




@endpush
