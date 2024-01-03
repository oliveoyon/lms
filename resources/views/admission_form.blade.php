<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Admission Form - Salikha Thana High School</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.2.0/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link href="https://fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #2c3e50, #2c3e50);
            /* Dark gray to black gradient */
            color: #fff;
            /* White text for better contrast */
            font-family: solaimanlipi;
        }

        .content-section {
            padding: 30px;
            text-align: center;
        }

        .form-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .custom-form-row {
            margin-bottom: 20px;
        }

        .form-segment {
            background-color: rgba(0, 0, 0, 0.5);
            /* Darker shade of black with some transparency */
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .form-segment label {
            color: #fff;
        }

        .btn-primary {
            background-color: #2c3e50;
            /* Dark gray color */
            border-color: #2c3e50;
            /* Border color (same as background for a solid button) */
            color: #fff;
            /* White text for better contrast */
        }

        .btn-primary:hover {
            background-color: #34495e;
            /* Darker gray on hover */
            border-color: #34495e;
            /* Border color on hover */
            color: #fff;
            /* White text on hover */
        }

        /* Add a bolder red border to elements with the is-invalid class */
        .is-invalid {
            border: 2px solid #ff0000;
            /* Red border color */
            font-weight: bold;
            /* Make the text bolder */
        }

        /* Optionally, you can also change the text color to red */
        .is-invalid {
            color: #ff0000;
            /* Red text color */
        }

        .toast-success {
            background-color: #000000;
            /* Vibrant yellow background color for success messages */
            color: #000000;
            /* Dark text color for success messages */
            border: 3px solid #ffffff;
            /* Border color for success messages */
        }

        /* Optionally, you can style the close button */
        .toast-success button.toast-close-button {
            color: #000000;
            /* Close button color for success messages */
            font-weight: bolder;
            /* Make the close button text bolder */
        }

        /* Optionally, you can style the title */
        .toast-success .toast-title {
            font-weight: bold;
            /* Make the title bold for success messages */
        }

        .toast-error {
            background-color: #fe0202;
            /* Vibrant yellow background color for success messages */
            color: #ffffff;
            /* Dark text color for success messages */
            border: 3px solid #ffffff;
            /* Border color for success messages */
        }

        /* Optionally, you can style the close button */
        .toast-error button.toast-close-button {
            color: #000000;
            /* Close button color for success messages */
            font-weight: bolder;
            /* Make the close button text bolder */
        }

        /* Optionally, you can style the title */
        .toast-error .toast-title {
            font-weight: bold;
            /* Make the title bold for success messages */
        }

        .required:after {
            content: " *";
            color: rgb(255, 51, 0);
            font-size: 18px;
            font-weight: bold;
        }
    </style>






</head>

<body>

    <div class="content-section">
        <h1>Shalikha Thana High School</h1>
        <p>{{ __('language.online_form') }}</p>
        <p>{{ __('language.already_applied') }}<br><button type="button"
                class="btn btn-sm btn-success" data-toggle="modal" data-target="#myModal">
                {{ __('language.click_here') }}</button>
        </p>
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ $errors->first('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#languageCollapse" aria-expanded="false">
            <img height="13px" src="{{ asset('dashboard/img/'.App::getLocale().'.png') }}" alt=""> {{ Config::get('languages')[App::getLocale()] }}
        </button>

        <!-- Language Selection Options -->
        <div class="collapse" id="languageCollapse">
            <div class="card card-body">
                @foreach (Config::get('languages') as $lang => $language)
                    @if ($lang != App::getLocale())
                        <a class="dropdown-item" href="{{ route('lang.switch', $lang) }}">
                            <img height="15px" src="{{ asset('dashboard/img/'.$lang.'.png') }}" alt=""> {{$language}}
                        </a>
                    @endif
                @endforeach
            </div>
        </div>



        <div class="modal" id="myModal">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header bg-info">
                        <h4 class="modal-title text-warning">{{ __('language.type_phone') }}</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body">
                        <!-- Form inside the modal -->
                        <div class="form-group">
                            <label for="phoneInput">{{ __('language.type_phone') }}</label>
                            <input type="tel" required class="form-control" id="phoneInput"
                                placeholder="{{ __('language.type_phone') }}" pattern="\d{11}">
                        </div>
                        <button type="button" class="btn btn-primary" onclick="submitForm()">{{ __('language.submit') }}</button>
                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('language.close') }}</button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="form-container">
                    <form action="{{ route('stdApply') }}" method="POST" autocomplete="off" id="add-student-form">
                        @csrf
                        <h4>{{ __('language.academic_details') }}</h4>
                        <div class="form-row custom-form-row  form-segment">
                            <div class="col-md-4">
                                <label for="academicYear" class="required">{{ __('language.academic_year') }}</label>
                                <select class="form-control form-control-sm academic_year" name="academic_year"
                                    id="academic_year">
                                    <option value="2024">2024</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="versionName" class="required">{{ __('language.version_name') }}</label>
                                <select class="form-control form-control-sm version_id" name="version_id"
                                    id="version_id">
                                    <option value="">{{ __('language.select_version') }}</option>
                                    @foreach ($versions as $version)
                                        <option value="{{ $version->id }}">{{ $version->version_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="className" class="required">{{ __('language.class_name') }}</label>
                                <select class="form-control form-control-sm class_id" name="class_id" id="class_id"
                                    disabled>
                                    <option value="">{{ __('language.select_class') }}</option>
                                </select>
                            </div>


                        </div>
                        <h4>{{ __('language.personal_details') }}</h4>
                        <div class="form-row custom-form-row  form-segment">
                            <div class="col-md-4">
                                <label for="studentFullName" class="required">{{ __('language.student_full_name') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-user"></i>
                                        </span>
                                    </div>
                                    <input type="text" id="studentFullName" name="std_name"
                                        class="form-control form-control-sm">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="studentFullNameBangla">{{ __('language.student_full_name_bangla') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-user"></i>
                                        </span>
                                    </div>
                                    <input type="text" id="studentFullNameBangla" name="std_name_bn"
                                        class="form-control form-control-sm">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="fatherName" class="required">{{ __('language.fathers_name') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-user"></i>
                                        </span>
                                    </div>
                                    <input type="text" id="fatherName" name="std_fname"
                                        class="form-control form-control-sm">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="motherName" class="required">{{ __('language.mothers_name') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-user"></i>
                                        </span>
                                    </div>
                                    <input type="text" id="motherName" name="std_mname"
                                        class="form-control form-control-sm">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="studentPhone" class="required">{{ __('language.students_phone') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-phone"></i>
                                        </span>
                                    </div>
                                    <input type="tel" id="studentPhone" name="std_phone"
                                        class="form-control form-control-sm" pattern="\d{11}">

                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="studentPhoneAlt">{{ __('language.students_phone_alternative') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-phone"></i>
                                        </span>
                                    </div>
                                    <input type="tel" id="studentPhoneAlt" name="std_phone1"
                                        class="form-control form-control-sm">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="dob" class="required">{{ __('language.date_of_birth') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-calendar"></i>
                                        </span>
                                    </div>
                                    <input type="date" id="dob" name="std_dob"
                                        class="form-control form-control-sm">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="email">{{ __('language.email') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-envelope"></i>
                                        </span>
                                    </div>
                                    <input type="email" id="email" name="std_email"
                                        class="form-control form-control-sm">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="bloodGroup">{{ __('language.blood_group') }}</label>
                                <select id="bloodGroup" name="blood_group" class="form-control form-control-sm">
                                    <option value="A+">A+</option>
                                    <option value="A-">A-</option>
                                    <option value="B+">B+</option>
                                    <option value="B-">B-</option>
                                    <option value="AB+">AB+</option>
                                    <option value="AB-">AB-</option>
                                    <option value="O+">O+</option>
                                    <option value="O-">O-</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="presentAddress" class="required">{{ __('language.present_address') }}</label>
                                <textarea id="presentAddress" name="std_present_address" class="form-control form-control-sm" rows="5"></textarea>
                            </div>

                            <div class="col-md-4">
                                <label for="permanentAddress" class="required">{{ __('language.permanent_address') }}</label>
                                <textarea id="permanentAddress" name="std_permanent_address" class="form-control form-control-sm" rows="5"></textarea>
                            </div>

                            <div class="col-md-4">
                                <label for="gender" class="required">{{ __('language.birth_certificate') }}</label>
                                <input type="text" name="std_birth_reg" class="form-control form-control-sm">
                                <label for="gender" class="required">{{ __('language.gender') }}</label>
                                <select id="gender" name="std_gender" class="form-control form-control-sm">
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>

                        </div>
                        <h4>P{{ __('language.parents_guardian_details') }}</h4>
                        <div class="form-row custom-form-row  form-segment">
                            <div class="col-md-4">
                                <label for="fatherOccupation">{{ __('language.fathers_occupation') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-briefcase"></i>
                                        </span>
                                    </div>
                                    <input type="text" id="fatherOccupation" name="std_f_occupation"
                                        class="form-control form-control-sm">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="motherOccupation">{{ __('language.mothers_occupation') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-briefcase"></i>
                                        </span>
                                    </div>
                                    <input type="text" id="motherOccupation" name="std_m_occupation"
                                        class="form-control form-control-sm">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="yearlyIncome">{{ __('language.yearly_income') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-dollar-sign"></i>
                                        </span>
                                    </div>
                                    <input type="number" id="yearlyIncome" name="f_yearly_income"
                                        class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>
                        <h4>{{ __('language.image_upload') }}</h4>
                        <div class="form-row custom-form-row  form-segment">
                            <div class="form-group">
                                <input type="file" id="pic" name="std_picture" accept="image/*">
                            </div>
                        </div>
                        <h4>{{ __('language.guardians_information') }}</h4>
                        <div class="form-row custom-form-row  form-segment">
                            <div class="form-group col-md-4">
                                <label for="guardianName">{{ __('language.guardians_name') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-user"></i>
                                        </span>
                                    </div>
                                    <input type="text" id="guardianName" name="std_gurdian_name"
                                        class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="relationship">{{ __('language.relationship_with_student') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-users"></i>
                                        </span>
                                    </div>
                                    <input type="text" id="relationship" name="std_gurdian_relation"
                                        class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="guardianPhone">{{ __('language.guardians_phone') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-phone"></i>
                                        </span>
                                    </div>
                                    <input type="tel" id="guardianPhone" name="std_gurdian_mobile"
                                        class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <div class="form-group">
                                    <label for="guardianAddress">{{ __('language.guardians_address') }}</label>
                                    <textarea id="guardianAddress" name="std_gurdian_address" class="form-control form-control-sm summernote"
                                        rows="3"></textarea>
                                </div>
                            </div>

                        </div>
                        <button type="submit" class="btn btn-primary btn-block bg-success">{{ __('language.save') }}</button>
                        <br>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.2.0/dist/sweetalert2.min.js"></script>
    <!-- Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        $(document).ready(function() {
            // When the "Academic Year" dropdown changes


            // When the "Version" dropdown changes
            $('.version_id').on('change', function() {
                var versionId = $(this).val();

                // Enable the "Class" dropdown
                $('.class_id').prop('disabled', false).data('version-id', versionId);

                // Add the extra option to the "Class" dropdown
                $('.class_id').html('<option value="">-- Please select a class --</option>');

                // Make an AJAX request to fetch classes based on the selected version
                $.ajax({
                    url: '{{ route('getClassesByVersion') }}',
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



            $('#add-student-form').on('submit', function(e) {
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
                    beforeSend: function() {
                        // Clear previous error messages
                        toastr.clear();
                        $(form).find('.is-invalid').removeClass('is-invalid');
                    },
                    success: function(data) {
                        if (data.code == 0) {
                            $.each(data.error, function(field, messages) {
                                // Add 'is-invalid' class to the input field
                                $(form).find('[name="' + field + '"]').addClass(
                                    'is-invalid');

                                // Display the first error message for the field using Toastr
                                toastr.error(messages[0], '', {
                                    closeButton: true
                                });
                            });
                        } else {
                            var redirectUrl = data.redirect;

                            toastr.success(data.msg);

                            setTimeout(function() {
                                window.location.href = redirectUrl;
                            }, 3000);
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle any other errors (e.g., server error)
                        console.error(xhr.responseText);
                        toastr.error('An error occurred. Please try again.');
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

    <script>
        function submitForm() {
            // Get the entered phone number
            var phoneNumber = document.getElementById('phoneInput').value;

            // Check if the phone number is not blank
            if (phoneNumber.trim() !== '') {
                // Construct the URL with the phone number
                var url = '/getslip/' + encodeURIComponent(phoneNumber);

                // Redirect to the constructed URL
                window.location.href = url;
            } else {
                // Display an alert if the phone number is blank
                alert('Please enter a valid phone number.');
            }
        }
    </script>

</body>

</html>
