<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Admission Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .header {
            padding: 20px;
            overflow: hidden; /* Clear float */
        }

        .left-section {
            float: left;
            width: 30%; /* Adjust as needed */
            margin-right: 20px; /* Add some spacing between sections */
        }

        .right-section {
            float: left;
            width: 65%; /* Adjust as needed */
        }

        .school-info {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .school-address {
            font-style: italic;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 15px;
            text-align: left;
        }

        .student-image {
            width: 150px;
            height: 150px;
            border: 1px solid #ddd;
            margin-bottom: 20px;
        }

        th{
            width: 25%;
            font-size: 15px;
        }


    </style>
</head>
<body>

    <div class="header">
        <div class="left-section">
            <img class="student-image" src="{{ public_path('storage/img/applied_std_img/'.$user->std_picture) }}" alt="Student Image">

            {{-- <div class="barcode">
                <img style="height: 40px;" class="student-image" src="{{ public_path('barcode.gif') }}" alt="Student Image">
            </div> --}}
        </div>
        <div class="right-section">
            <div class="school-info">Shalikha Thana High School</div>
            <div class="school-address">Hajrahati, Shalikha, Magura</div>
            <div class="school-address">Email: shalikhaschool@gmail.com <br>
                Phone: 01309118082</div>
            <br>
            <div class="school-info" style="text-decoration: underline">Admission Form</div>
        </div>
    </div>

    <div class="form-container">

        <table>

            <tr>
                <th>Name:</th>
                <td>{{ $user->std_name }}</td>
                <th>Name in Bangla:</th>
                <td style="font-family: bangla">{{ $user->std_name_bn }}</td>
            </tr>
            <tr>
                <th>Class Name:</th>
                <td>{{ $user->class_name }}</td>
                <th>Birth Certificate:</th>
                <td>{{ $user->std_birth_reg }}</td>
            </tr>
            <tr>
                <th>Date of Birth:</th>
                <td>{{ date('d F Y', strtotime($user->std_dob)) }}</td>
                <th>Gender:</th>
                <td>{{ $user->std_gender }}</td>
            </tr>
            <tr>
                <th>Address:</th>
                <td colspan="3">{{ $user->std_present_address }}</td>
            </tr>
            <tr>
                <th>Contact Number:</th>
                <td>{{ $user->std_phone }}</td>
                <th>Email:</th>
                <td>{{ $user->std_email }}</td>
            </tr>
            <tr>
                <th>Guardian Name:</th>
                <td>{{ $user->std_fname }}</td>
                <th>Guardian Contact:</th>
                <td></td>
            </tr>
        </table>
        <br>
        <p style="text-align: center; font-style: italic; font-family: bangla"><span style="font-weight: bold">বিঃদ্রঃ</span> ভর্তির দিন জন্ম নিবন্ধন সার্টিফিকেট এবং প্রয়োজনীয় কাগজপত্র সংগে আনতে হবে।</p>

    </div>

</body>
</html>
