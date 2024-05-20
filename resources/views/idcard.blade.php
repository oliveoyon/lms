@php
    $gs = \App\Models\Admin\GeneralSetting::find(1);
@endphp


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student ID Card</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: red;
            /* Whitish background */
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .card {
            width: 53.98mm;
            height: 85.6mm;
            background-color: #fff;
            /* White card background */
            /* border-radius: 10px; */
            overflow: hidden;
            /* box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); */
            position: relative;
        }

        .header {
            background-color: #004b10;
            /* Dark gray header background */
            color: #fff;
            padding: 10px;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
        }

        .photo {
            width: 85px;
            margin: 10px auto;
            overflow: hidden;
        }

        .photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }


        .info-container {
            padding: 5px;
            padding-top: 0px;
            text-align: center;
            color: white;

        }

        .info-container h1 {
            margin: 1px 0;
            font-size: 16px;
            font-weight: bold;
            color: #028814;
            /* Dark gray text color */
        }

        .info-container p {
            margin: 5px 0;
            font-size: 14px;
            color: #000000;
            /* Lighter gray text color */
        }

        .barcode-signature-container {
            margin: 5px 0;
            display: flex;
            /* Use flex to ensure items are in a single line */
            justify-content: space-around;
            align-items: center;
        }

        .barcode-signature-container img {
            width: 80%;
            max-width: 80px;
            height: auto;
            flex-shrink: 0;
            /* Prevent images from shrinking */
        }


        .back {
            background-color: #fff;
            /* White card background for back part */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 10;
            text-align: center;
            color: white;
            /* margin-bottom: 5px; */
        }

        /* Additional styles for the back part */
        .back h1 {
            font-size: 14px;
            font-weight: bold;
            color: #028814;
        }

        .back p {
            font-size: 12px;
            color: #000000;
            margin-bottom: 2px;
        }
    </style>
</head>

<body>

    @foreach($stds as $std)
    @php
        $date = new DateTime($std->std_dob);
        $formattedDate = $date->format('d M Y');
    @endphp
    <div class="card">
        <div class="header">{{$gs->school_title}}</div>
        <div class="photo">
            <img src="{{ public_path('dashboard/img/std_img/'.$std->std_picture) }}" alt="Student Photo">
        </div>
        <div class="watermark">
            <div class="info-container">
                <!-- Your centered text content goes here -->
                <h1>{{ $std->std_name }}</h1>
                <p style="font-size: 12px">Student ID: {{ $std->std_id }}</p>
                <p style="font-size: 12px">Class: {{ $std->class_name }}</p>
                <p style="font-size: 12px">DOB: {{ $formattedDate }}</p>
            </div>
        </div>


        <div style="text-align: center">
            <?php
                $barcodeImage = 'data:image/png;base64,' . DNS1D::getBarcodePNG($std->std_id, 'C39', 1, 20);
                echo '<img  src="' . $barcodeImage . '" alt="barcode"  />';
            ?>
        </div>


    </div>

    <div class="card">
        <div class="back">
            {{-- <h1>Back Part Information</h1> --}}
            <p><span style="font-weight:bold">{{ $std->std_name }}</span></p>
            <p>Blood Group: {{ $std->blood_group }}</p>
            <p>Emergency Contact: {{ $std->std_phone }}</p>
            <p>If you found this card, Contact:</p>
            <p><span style="font-weight:bold">{{$gs->school_title}}</span> <br> {{$gs->school_address}} <br> {{$gs->school_phone}}</p>
        </div>



        <div style="text-align: center">
            <?php
                $barcodeImage = 'data:image/png;base64,' . DNS2D::getBarcodePNG('https://shalikhaschool.edu.bd/', 'QRCODE');
                echo '<img  height="40" src="' . $barcodeImage . '" alt="barcode"  />';
            ?>
        </div>

    </div>

    @endforeach





</body>

</html>
