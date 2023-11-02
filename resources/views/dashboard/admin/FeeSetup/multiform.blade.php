@extends('dashboard.admin.layouts.admin-layout-with-cdn')
@section('title', 'Fee Head')
@push('admincss')
<!-- Add your custom CSS here -->
<style>
    .custom-form {
        background: white;
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .custom-form label {
        font-weight: bold;
    }

    .custom-form .form-group {
        margin-bottom: 15px;
    }

    .timeline {
        list-style-type: none;
        display: flex;
        justify-content: space-between;
        padding: 0;
        background: #673AB7;
        color: white;
    }

    .timeline li {
        flex: 1;
        text-align: center;
        cursor: pointer;
        padding: 10px 0;
        transition: background-color 0.3s, color 0.3s;
    }

    .timeline li.active {
        background: #9a63f8;
    }

    .timeline li i {
        font-size: 20px;
        display: block;
        margin-bottom: 10px;
    }

    .btn-container {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }

    .btn-container button {
        background-color: #673AB7;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 10px 20px;
        cursor: pointer;
    }

    .required:after {
        content: " *";
        color: red;
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
                    
                    
                   
                    <div class="card custom-form">
                        <ul class="timeline">
                            <li class="timeline-item active" data-step="1">
                                <i class="fas fa-graduation-cap"></i>
                                Step 1
                            </li>
                            <li class="timeline-item" data-step="2">
                                <i class="fas fa-user"></i>
                                Step 2
                            </li>
                            <li class="timeline-item" data-step="3">
                                <i class="fas fa-users"></i>
                                Step 3
                            </li>
                            <li class="timeline-item" data-step="4">
                                <i class="fa fa-image"></i>
                                Step 4
                            </li>
                            <li class="timeline-item" data-step="5">
                                <i class="fa fa-check-circle"></i>
                                Finish
                            </li>
                        </ul>
                        
                        <form id="customForm">
                            
                            <fieldset>
                                <h2>Academic Details</h2>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="academicYear" class="required">Academic Year:</label>
                                            <select id="academicYear" name="academicYear" class="form-control" required>
                                                <option value="">Select Academic Year</option>
                                                <option value="2023-2024">2023-2024</option>
                                                <option value="2024-2025">2024-2025</option>
                                                <option value="2025-2026">2025-2026</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="versionName" class="required">Version Name:</label>
                                            <select id="versionName" name="versionName" class="form-control" required>
                                                <option value="">Select Version Name</option>
                                                <option value="Version A">Version A</option>
                                                <option value="Version B">Version B</option>
                                                <option value="Version C">Version C</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="className" class="required">Class Name:</label>
                                            <select id="className" name="className" class="form-control" required>
                                                <option value="">Select Class Name</option>
                                                <option value="Class 1">Class 1</option>
                                                <option value="Class 2">Class 2</option>
                                                <option value="Class 3">Class 3</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="sectionName" class="required">Section Name:</label>
                                            <select id="sectionName" name="sectionName" class="form-control" required>
                                                <option value="">Select Section Name</option>
                                                <option value="Section A">Section A</option>
                                                <option value="Section B">Section B</option>
                                                <option value="Section C">Section C</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="rollNo" class="required">Roll No:</label>
                                            <div class="input-group">
                                                <input type="text" id="rollNo" name="rollNo" class="form-control" placeholder="Roll No" required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i class="fa fa-user"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="admissiondate" class="required">Admission Date:</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-calendar"></i>
                                                    </span>
                                                </div>
                                                <input type="date" id="admissiondate" name="admissiondate" class="form-control" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="studentCategory" class="required">Student Category:</label>
                                            <select id="studentCategory" name="studentCategory" class="form-control" required>
                                                <option value="">Select Student Category</option>
                                                <option value="Category A">Category A</option>
                                                <option value="Category B">Category B</option>
                                                <option value="Category C">Category C</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="feeSetup" class="required">Fee Setup:</label>
                                            <select id="feeSetup" name="feeSetup" class="form-control" required>
                                                <option value="">Select Fee Setup</option>
                                                <option value="Setup 1">Setup 1</option>
                                                <option value="Setup 2">Setup 2</option>
                                                <option value="Setup 3">Setup 3</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="btn-container">
                                    <button type="button" class="btn btn-primary next">Next</button>
                                </div>
                            </fieldset>
                            
                            <fieldset>
                                <h2>Personal Details</h2>
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="studentFullName" class="required">Student Full Name (In English):</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-user"></i>
                                                </span>
                                            </div>
                                            <input type="text" id="studentFullName" name="studentFullName" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="studentFullNameBangla">Student Full Name (In Bangla):</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-user"></i>
                                                </span>
                                            </div>
                                            <input type="text" id="studentFullNameBangla" name="studentFullNameBangla" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="fatherName" class="required">Father's Name:</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-user"></i>
                                                </span>
                                            </div>
                                            <input type="text" id="fatherName" name="fatherName" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="motherName" class="required">Mother's Name:</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-user"></i>
                                                </span>
                                            </div>
                                            <input type="text" id="motherName" name="motherName" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="studentPhone" class="required">Student's Phone:</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-phone"></i>
                                                </span>
                                            </div>
                                            <input type="tel" id="studentPhone" name="studentPhone" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="studentPhoneAlt">Student's Phone Alternative:</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-phone"></i>
                                                </span>
                                            </div>
                                            <input type="tel" id="studentPhoneAlt" name="studentPhoneAlt" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="dob" class="required">Date of Birth:</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-calendar"></i>
                                                </span>
                                            </div>
                                            <input type="date" id="dob" name="dob" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="email">Email:</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-envelope"></i>
                                                </span>
                                            </div>
                                            <input type="email" id="email" name="email" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="bloodGroup">Blood Group:</label>
                                        <select id="bloodGroup" name="bloodGroup" class="form-control">
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
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="presentAddress" class="required">Present Address:</label>
                                        <textarea id="presentAddress" name="presentAddress" class="form-control" rows="5" required></textarea>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="permanentAddress" class="required">Permanent Address:</label>
                                        <textarea id="permanentAddress" name="permanentAddress" class="form-control" rows="5" required></textarea>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="gender" class="required">Gender:</label>
                                        <select id="gender" name="gender" class="form-control" required>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- File Upload for Step Two -->
                                <div class="btn-container">
                                    <button type="button" class="btn btn-primary previous">Previous</button>
                                    <button type="button" class="btn btn-primary next">Next</button>
                                </div>
                            </fieldset>
                            
                            <fieldset>
                                <h2>Parents/Guardian Details</h2>
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="fatherOccupation">Father's Occupation:</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-briefcase"></i>
                                                </span>
                                            </div>
                                            <input type="text" id="fatherOccupation" name="fatherOccupation" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="motherOccupation">Mother's Occupation:</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-briefcase"></i>
                                                </span>
                                            </div>
                                            <input type="text" id="motherOccupation" name="motherOccupation" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="yearlyIncome">Yearly Income:</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-dollar-sign"></i>
                                                </span>
                                            </div>
                                            <input type="number" id="yearlyIncome" name="yearlyIncome" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="btn-container">
                                    <button type="button" class="btn btn-primary previous">Previous</button>
                                    <button type="button" class="btn btn-primary next">Next</button>
                                </div>
                            </fieldset>
                            <fieldset>
                                <h2>Image Upload</h2>
                                <div class="form-group">
                                    <label for="pic">Upload Your Photo:</label>
                                    <input type="file" id="pic" name="pic" accept="image/*">
                                </div>
                                <!-- Other form fields for Step 3 -->
                                <div class="btn-container">
                                    <button type="button" class="btn btn-primary previous">Previous</button>
                                    <button type="button" class="btn btn-primary next">Next</button>
                                </div>
                            </fieldset>
                            
                            <fieldset>
                                <h2>Guardian's Information (If the student does not live with parents)</h2>
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="guardianName">Guardian's Name:</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-user"></i>
                                                </span>
                                            </div>
                                            <input type="text" id="guardianName" name="guardianName" class="form-control">
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
                                            <input type="text" id="relationship" name="relationship" class="form-control">
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
                                            <input type="tel" id="guardianPhone" name="guardianPhone" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="guardianAddress">Guardian's Address:</label>
                                    <textarea id="guardianAddress" name="guardianAddress" class="form-control summernote" rows="3"></textarea>
                                </div>
                                <div class="btn-container">
                                    <button type="button" class="btn btn-primary previous">Previous</button>
                                    <button type="submit" class="btn btn-primary">Finish</button>
                                </div>
                            </fieldset>
                            
                            
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('adminjs')

<script>
    $(document).ready(function() {
        var currentStep = 1;
        var totalSteps = 5; // Total number of steps

        // Initialize the form
        showStep(currentStep);

        $(".timeline-item").click(function() {
            var step = $(this).data('step');

            if (step <= currentStep) {
                showStep(step);
                hideAlerts(); // Hide alerts when moving between steps
            }
        });

        $(".next").click(function() {
            var nextStep = currentStep + 1;
            if (validateStep(currentStep)) {
                if (nextStep <= totalSteps) {
                    showStep(nextStep);
                    hideAlerts(); // Hide alerts when moving between steps
                } else if (nextStep === totalSteps + 1) {
                    if (validateStep(currentStep)) { // Check if the last step is valid
                        showAlert("You have completed all steps.", "success");
                    } else {
                        showAlert("Please fill in all required fields before proceeding.", "danger");
                    }
                }
            } else if (currentStep < totalSteps) {
                showAlert("Please fill in all required fields before proceeding.", "danger");
            }
        });

        $(".previous").click(function() {
            var previousStep = currentStep - 1;
            if (previousStep >= 1) {
                showStep(previousStep);
                hideAlerts(); // Hide alerts when moving between steps
            }
        });

        function showStep(step) {
            // Hide all fieldsets
            $("fieldset").hide();

            // Show the selected step
            $("fieldset:nth-child(" + step + ")").show();

            // Update the active step in the timeline
            $(".timeline-item").removeClass("active");
            $(".timeline-item[data-step='" + step + "']").addClass("active");

            currentStep = step;
        }

        function validateStep(step) {
            var isValid = true;
            var currentFieldset = $("fieldset:nth-child(" + step + ")");
            
            // Check for required input fields
            currentFieldset.find("input[required]").each(function() {
                if ($(this).val() === "") {
                    isValid = false;
                    return false; // Exit the loop on the first empty required field
                }
            });

            // Check for required select elements
            currentFieldset.find("select[required]").each(function() {
                if ($(this).val() === "") {
                    isValid = false;
                    return false; // Exit the loop on the first empty required field
                }
            });

            // Check for required radio buttons
            if (currentFieldset.find("input[type=radio][required]").length > 0) {
                if (currentFieldset.find("input[type=radio][required]:checked").length === 0) {
                    isValid = false;
                }
            }

            // Check for required checkboxes
            if (currentFieldset.find("input[type=checkbox][required]").length > 0) {
                if (currentFieldset.find("input[type=checkbox][required]:checked").length === 0) {
                    isValid = false;
                }
            }

            return isValid;
        }


        function showAlert(message, alertType) {
            var alertElement = $("#completionAlert");
            alertElement.removeClass("alert-danger alert-success");
            alertElement.addClass("alert-" + alertType);
            alertElement.find("#completionAlertText").text(message);
            alertElement.show();
        }

        function hideAlerts() {
            $("#errorAlert, #completionAlert").hide();
        }
    });
</script>



@endpush
