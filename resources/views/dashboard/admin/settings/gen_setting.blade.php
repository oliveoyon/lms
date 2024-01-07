@extends('dashboard.admin.layouts.admin-layout-with-cdn')
@section('title', 'General Settings')
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
                    <h1 class="m-0">{{ __('language.general_settings') }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ __('language.dashboard') }}</a></li>
                        <li class="breadcrumb-item">{{ __('language.general_settings') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <form action="{{ route('admin.editGenSetting') }}" method="POST" autocomplete="off" id="update-settings-form">
                        @csrf
                        <input type="hidden" id="hiddenField" name="sid" value="{{ $settings->id }}">
                        <div class="card">
                            <div class="card-header bg-gray">
                                <h3 class="card-title">{{ __('language.general_settings') }}</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="schoolTitle" class="required">{{ __('language.school_title') }}</label>
                                        <input type="text" id="schoolTitle" name="school_title" class="form-control form-control-sm" value="{{ $settings->school_title ?? '' }}">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="schoolTitleBangla">{{ __('language.school_title_bangla') }}</label>
                                        <input type="text" id="schoolTitleBangla" name="school_title_bn" class="form-control form-control-sm" value="{{ $settings->school_title_bn ?? '' }}">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="schoolShortName">{{ __('language.school_short_name') }}</label>
                                        <input type="text" id="schoolShortName" name="school_short_name" class="form-control form-control-sm" value="{{ $settings->school_short_name ?? '' }}">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="schoolCode">{{ __('language.school_code') }}</label>
                                        <input type="text" id="schoolCode" name="school_code" class="form-control form-control-sm" value="{{ $settings->school_code ?? '' }}">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="schoolEiinNo">{{ __('language.school_eiin_no') }}</label>
                                        <input type="text" id="schoolEiinNo" name="school_eiin_no" class="form-control form-control-sm" value="{{ $settings->school_eiin_no ?? '' }}">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="schoolEmail">{{ __('language.school_email') }}</label>
                                        <input type="email" id="schoolEmail" name="school_email" class="form-control form-control-sm" value="{{ $settings->school_email ?? '' }}">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="schoolPhone">{{ __('language.school_phone') }}</label>
                                        <input type="tel" id="schoolPhone" name="school_phone" class="form-control form-control-sm" value="{{ $settings->school_phone ?? '' }}">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="schoolPhone1">{{ __('language.school_phone1') }}</label>
                                        <input type="tel" id="schoolPhone1" name="school_phone1" class="form-control form-control-sm" value="{{ $settings->school_phone1 ?? '' }}">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="schoolPhone2">{{ __('language.school_phone2') }}</label>
                                        <input type="tel" id="schoolPhone2" name="school_phone2" class="form-control form-control-sm" value="{{ $settings->school_phone2 ?? '' }}">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="schoolFax">{{ __('language.school_fax') }}</label>
                                        <input type="tel" id="schoolFax" name="school_fax" class="form-control form-control-sm" value="{{ $settings->school_fax ?? '' }}">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="schoolAddress">{{ __('language.school_address') }}</label>
                                        <textarea id="schoolAddress" name="school_address" class="form-control form-control-sm" rows="5">{{ $settings->school_address ?? '' }}</textarea>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="schoolCountry">{{ __('language.school_country') }}</label>
                                        <input type="text" id="schoolCountry" name="school_country" class="form-control form-control-sm" value="{{ $settings->school_country ?? '' }}">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="currencySign">{{ __('language.currency_sign') }}</label>
                                        <input type="text" id="currencySign" name="currency_sign" class="form-control form-control-sm" value="{{ $settings->currency_sign ?? '' }}">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="currencyName">{{ __('language.currency_name') }}</label>
                                        <input type="text" id="currencyName" name="currency_name" class="form-control form-control-sm" value="{{ $settings->currency_name ?? '' }}">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="schoolGeocode">{{ __('language.school_geocode') }}</label>
                                        <input type="text" id="schoolGeocode" name="school_geocode" class="form-control form-control-sm" value="{{ $settings->school_geocode ?? '' }}">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="schoolFacebook">{{ __('language.school_facebook') }}</label>
                                        <input type="text" id="schoolFacebook" name="school_facebook" class="form-control form-control-sm" value="{{ $settings->school_facebook ?? '' }}">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="schoolTwitter">{{ __('language.school_twitter') }}</label>
                                        <input type="text" id="schoolTwitter" name="school_twitter" class="form-control form-control-sm" value="{{ $settings->school_twitter ?? '' }}">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="schoolGoogle">{{ __('language.school_google') }}</label>
                                        <input type="text" id="schoolGoogle" name="school_google" class="form-control form-control-sm" value="{{ $settings->school_google ?? '' }}">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="schoolLinkedin">{{ __('language.school_linkedin') }}</label>
                                        <input type="text" id="schoolLinkedin" name="school_linkedin" class="form-control form-control-sm" value="{{ $settings->school_linkedin ?? '' }}">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="schoolYoutube">{{ __('language.school_youtube') }}</label>
                                        <input type="text" id="schoolYoutube" name="school_youtube" class="form-control form-control-sm" value="{{ $settings->school_youtube ?? '' }}">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="schoolCopyrights">{{ __('language.school_copyrights') }}</label>
                                        <input type="text" id="schoolCopyrights" name="school_copyrights" class="form-control form-control-sm" value="{{ $settings->school_copyrights ?? '' }}">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="schoolLogo">{{ __('language.school_logo') }}</label>
                                        <input type="file" id="schoolLogo" name="school_logo" class="form-control form-control-sm">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="currency">{{ __('language.currency') }}</label>
                                        <input type="text" id="currency" name="currency" class="form-control form-control-sm" value="{{ $settings->currency ?? '' }}">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="setStatus">{{ __('language.set_status') }}</label>
                                        <select class="form-control form-control-sm" name="set_status" id="setStatus">
                                            <option value="1" {{ $settings->set_status == 1 ? 'selected' : '' }}>{{ __('language.active') }}</option>
                                            <option value="0" {{ $settings->set_status == 0 ? 'selected' : '' }}>{{ __('language.inactive') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="timezone">{{ __('language.timezone') }}</label>
                                        <input type="text" id="timezone" name="timezone" class="form-control form-control-sm" value="{{ $settings->timezone ?? 'UTC' }}">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="language">{{ __('language.language') }}</label>
                                        <input type="text" id="language" name="language" class="form-control form-control-sm" value="{{ $settings->language ?? 'en' }}">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="enableNotifications">{{ __('language.enable_notifications') }}</label>
                                        <div class="form-check">
                                            <input type="checkbox" id="enableNotifications" name="enable_notifications" class="form-check-input" {{ $settings->enable_notifications ? 'checked' : '' }}>
                                            <label class="form-check-label" for="enableNotifications">{{ __('language.enable_notifications') }}</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="btn-container">
                                    <button type="submit" class="btn btn-primary btn-block">{{ __('language.save') }}</button>
                                </div>

                            </div>
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

// Update General Settings RECORD
$('#update-settings-form').on('submit', function(e) {
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
            // Remove the 'is-invalid' class from all form fields before making the AJAX request
            $(form).find('.form-control').removeClass('is-invalid');
        },
        success: function(data) {
            if (data.code == 0) {
                $.each(data.error, function(field, val) {
                    // Add the 'is-invalid' class to the form field with an error
                    $(form).find('[name="' + field + '"]').addClass('is-invalid');
                    $(form).find('span.' + field + '_error').text(val[0]);
                });
            } else {
                // Update the UI or perform other actions
                var redirectUrl = data.redirect;

                // Optionally, close a modal and reset the form
                $('#edit-settings-modal').modal('hide');
                $(form)[0].reset();

                toastr.success(data.msg);

                // Optionally, redirect after a delay
                setTimeout(function() {
                    window.location.href = redirectUrl;
                }, 1000); // Adjust the delay as needed (in milliseconds)
            }
        },
        complete: function() {
            // Enable the submit button and hide the loader overlay
            $(form).find(':submit').prop('disabled', false);
            $('#loader-overlay').hide();
        }
    });
});


});




</script>


@endpush
