<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Multi-Step Form</title>

    <!-- Include your CSS links here -->

    <style>
        /* Add your custom styles here */
        body {
            font-family: 'Source Sans Pro', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        #app {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        form {
            background: white;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 20px;
            width: 400px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
        }

        .form-step {
            display: none;
        }

        .form-step.active {
            display: block;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 5px;
        }

        .previous {
            background-color: #f44336;
        }
    </style>
</head>
<body>

<div id="app">
    <form id="multiStepForm" action="{{ route('admin.stdAdmission') }}" method="POST">
        @csrf
        <div class="form-step active" data-step="1">
            <h2>Step 1</h2>
            <!-- Your Step 1 Form Fields -->
            <button type="button" class="next">Next</button>
        </div>

        <div class="form-step" data-step="2">
            <h2>Step 2</h2>
            <!-- Your Step 2 Form Fields -->
            <button type="button" class="previous">Previous</button>
            <button type="button" class="next">Next</button>
        </div>

        <div class="form-step" data-step="3">
            <h2>Step 3</h2>
            <!-- Your Step 3 Form Fields -->
            <button type="button" class="previous">Previous</button>
            <button type="button" class="next">Next</button>
        </div>

        <div class="form-step" data-step="4">
            <h2>Step 4</h2>
            <!-- Your Step 4 Form Fields -->
            <button type="button" class="previous">Previous</button>
            <button type="submit">Submit</button>
        </div>
    </form>
</div>

<!-- Include your JS scripts here -->
<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
    $(document).ready(function() {
        var currentStep = 1;
        var totalSteps = 4; // Adjust the total number of steps accordingly

        // Initialize the form
        showStep(currentStep);

        $(".next").click(function() {
            var nextStep = currentStep + 1;
            if (validateStep(currentStep)) {
                if (nextStep <= totalSteps) {
                    showStep(nextStep);
                }
            }
        });

        $(".previous").click(function() {
            var previousStep = currentStep - 1;
            if (previousStep >= 1) {
                showStep(previousStep);
            }
        });

        function showStep(step) {
            // Hide all form steps
            $(".form-step").removeClass("active");

            // Show the selected step
            $(".form-step[data-step='" + step + "']").addClass("active");

            currentStep = step;
        }

        function validateStep(step) {
            // Add your validation logic here
            return true;
        }
    });
</script>


</body>
</html>
