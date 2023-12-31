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
        background-color: #000000; /* Vibrant yellow background color for success messages */
        color: #000000; /* Dark text color for success messages */
        border: 3px solid #ffffff; /* Border color for success messages */
    }

    /* Optionally, you can style the close button */
    .toast-success button.toast-close-button {
        color: #000000; /* Close button color for success messages */
        font-weight: bolder; /* Make the close button text bolder */
    }

    /* Optionally, you can style the title */
    .toast-success .toast-title {
        font-weight: bold; /* Make the title bold for success messages */
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
        <p>Online Admission Form</p>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="form-container">
                    <form action="{{ route('stdApply') }}" method="POST" autocomplete="off" id="add-student-form">
                        @csrf
                        <h4>Academic Details</h4>
                        <div class="form-row custom-form-row  form-segment">
                            <div class="col-md-4">
                                <label for="academicYear" class="required">Academic Year:</label>
                                <select class="form-control form-control-sm academic_year" name="academic_year"
                                    id="academic_year">
                                    <option value="2024">2024</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="versionName" class="required">Version Name:</label>
                                <select class="form-control form-control-sm version_id" name="version_id"
                                    id="version_id">
                                    <option value="">{{ __('language.select_version') }}</option>
                                    @foreach ($versions as $version)
                                        <option value="{{ $version->id }}">{{ $version->version_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="className" class="required">Class Name:</label>
                                <select class="form-control form-control-sm class_id" name="class_id" id="class_id"
                                    disabled>
                                    <option value="">{{ __('language.select_class') }}</option>
                                </select>
                            </div>


                        </div>
                        <h4>Personal Details</h4>
                        <div class="form-row custom-form-row  form-segment">
                            <div class="col-md-4">
                                <label for="studentFullName" class="required">Student Full Name (In English):</label>
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
                                <label for="studentFullNameBangla">Student Full Name (In Bangla):</label>
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
                                <label for="fatherName" class="required">Father's Name:</label>
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
                                <label for="motherName" class="required">Mother's Name:</label>
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
                                <label for="studentPhone" class="required">Student's Phone:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-phone"></i>
                                        </span>
                                    </div>
                                    <input type="tel" id="studentPhone" name="std_phone"
                                        class="form-control form-control-sm">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="studentPhoneAlt">Student's Phone Alternative:</label>
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
                                <label for="dob" class="required">Date of Birth:</label>
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
                                <label for="email">Email:</label>
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
                                <label for="bloodGroup">Blood Group:</label>
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
                                <label for="presentAddress" class="required">Present Address:</label>
                                <textarea id="presentAddress" name="std_present_address" class="form-control form-control-sm" rows="5"></textarea>
                            </div>

                            <div class="col-md-4">
                                <label for="permanentAddress" class="required">Permanent Address:</label>
                                <textarea id="permanentAddress" name="std_permanent_address" class="form-control form-control-sm" rows="5"></textarea>
                            </div>

                            <div class="col-md-4">
                                <label for="gender" class="required">Birth Certificate No:</label>
                                <input type="text" name="std_birth_reg" class="form-control form-control-sm">
                                <label for="gender" class="required">Gender:</label>
                                <select id="gender" name="std_gender" class="form-control form-control-sm">
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>

                        </div>
                        <h4>Parents/Guardian Details</h4>
                        <div class="form-row custom-form-row  form-segment">
                            <div class="col-md-4">
                                <label for="fatherOccupation">Father's Occupation:</label>
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
                                <label for="motherOccupation">Mother's Occupation:</label>
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
                                <label for="yearlyIncome">Yearly Income:</label>
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
                        <h4>Photo Upload</h4>
                        <div class="form-row custom-form-row  form-segment">
                            <div class="form-group">
                                <input type="file" id="pic" name="std_picture" accept="image/*">
                            </div>
                        </div>
                        <h4>Guardian's Information (If the student does not live with parents)</h4>
                        <div class="form-row custom-form-row  form-segment">
                            <div class="form-group col-md-4">
                                <label for="guardianName">Guardian's Name:</label>
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
                                <label for="relationship">Relationship with Student:</label>
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
                                <label for="guardianPhone">Guardian's Phone:</label>
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
                                    <label for="guardianAddress">Guardian's Address:</label>
                                    <textarea id="guardianAddress" name="std_gurdian_address" class="form-control form-control-sm summernote"
                                        rows="3"></textarea>
                                </div>
                            </div>

                        </div>
                        <button type="submit" class="btn btn-primary btn-block bg-success">Submit</button>
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
                        $(form).find('span.error-text').text('');
                    },
                    success: function(data) {
                        if (data.code == 0) {
                            $.each(data.error, function(field, messages) {
                                // Add 'is-invalid' class to the input field
                                $(form).find('[name="' + field + '"]').addClass(
                                    'is-invalid');
                                // Display the first error message for the field
                                $(form).find('span.' + field + '_error').text(messages[
                                    0]);
                            });
                        } else {
                            var redirectUrl = data.redirect;

                            toastr.success(data.msg);

                            setTimeout(function() {
                                window.location.href = redirectUrl;
                            }, 5000);
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
</body>

</html>
