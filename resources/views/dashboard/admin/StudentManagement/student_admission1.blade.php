@extends('dashboard.admin.layouts.admin-layout-with-cdn')
@section('title', 'Fee Head')
@push('admincss')
<!-- Add your custom CSS here -->
<style>
    .required:after {
        content: " *";
        color: red;
    }

    /* Custom Form Styles */
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

    /* Timeline Styles */
    .timeline {
        list-style: none;
        display: flex;
        justify-content: space-between;
        padding: 0;
        background: #673AB7;
        color: white;
        margin: 0;
        padding: 0;
        margin-bottom: 10px;
    }

    .timeline li {
        flex: 1;
        text-align: center;
        cursor: pointer;
        padding: 10px 0;
        transition: background-color 0.3s, color 0.3s;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .timeline li.active {
        background: #9a63f8;
    }

    .timeline li i {
        font-size: 20px;
        display: block;
        margin-bottom: 10px;
    }

    /* Hide hidden fields in inactive steps */
    .custom-form fieldset:not(:first-of-type) [type="hidden"] {
        display: none !important;
    }

    /* Mobile Responsive Styles */
    @media (max-width: 768px) {
        .timeline {
            display: none;
        }
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
                    <div class="card custom-form">
                        
                        <form action="{{ route('admin.stdAdmission') }}" method="POST" autocomplete="off" id="add-student-form">
                            @csrf
                        
                            <div class="card">
                                <div class="card-header">
                                    <h2>Academic Details</h2>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="academicYear" class="required">Academic Year:</label>
                                                <select class="form-control" name="academic_year" id="academic_year">
                                                    <!-- Academic Year options go here -->
                                                </select>
                                            </div>
                                        </div>
                                        <!-- Add other Academic Details fields here -->
                                    </div>
                                </div>
                            </div>
                        
                            <div class="card">
                                <div class="card-header">
                                    <h2>Personal Details</h2>
                                </div>
                                <div class="card-body">
                                    <div class="form-row">
                                        <!-- Personal Details fields go here -->
                                    </div>
                                </div>
                            </div>
                        
                            <div class="card">
                                <div class="card-header">
                                    <h2>Parents/Guardian Details</h2>
                                </div>
                                <div class="card-body">
                                    <div class="form-row">
                                        <!-- Parents/Guardian Details fields go here -->
                                    </div>
                                </div>
                            </div>
                        
                            <div class="card">
                                <div class="card-header">
                                    <h2>Image Upload</h2>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <!-- Image Upload fields go here -->
                                    </div>
                                </div>
                            </div>
                        
                            <div class="card">
                                <div class="card-header">
                                    <h2>Guardian's Information (If the student does not live with parents)</h2>
                                </div>
                                <div class="card-body">
                                    <div class="form-row">
                                        <!-- Guardian's Information fields go here -->
                                    </div>
                                </div>
                            </div>
                        
                            <div class="btn-container">
                                <button type="submit" class="btn btn-primary">Finish</button>
                            </div>
                        
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

$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


$(document).ready(function() {
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
});

</script>
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
            // Hide all containers with class "form-step"
            $(".form-step").hide();

            // Show the container for the selected step
            $(".form-step:nth-child(" + step + ")").show();

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
