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
            background-color: #f5f5f5;
            /* Whitish background */
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .card {
            width: 2.125in;
            height: 3.375in;
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
            width: 70px;
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
            text-align: center;
            color: white;

        }

        .info-container h1 {
            margin: 1px 0;
            font-size: 18px;
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
            padding: 20px;
            text-align: center;
            color: white;
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
        }
    </style>
</head>

<body>


    <div class="card">
        <div class="header">{{ $card->school_name }}</div>
        <div class="photo">
            <img src="{{ public_path('23001.jpg') }}" alt="Student Photo">
        </div>
        <div class="watermark">
            <div class="info-container">
                <!-- Your centered text content goes here -->
                <h1>{{ $card->name }}</h1>
                <p>Student ID: {{ $card->std_id }}</p>
                <p>Class: {{ $card->class }}</p>
                <p>DOB: {{ $card->dob }}</p>
            </div>
        </div>

        <div class="barcode-signature-container">
            <img src="{{ public_path('barcode.gif') }}" alt="Barcode"
                style="width: 80%; max-width: 80px; height: auto; flex-shrink: 0; margin-left: 10px;">
            <img src="{{ public_path('barcode.gif') }}" alt="Principal Signature"
                style="width: 80%; max-width: 80px; height: auto; flex-shrink: 0; margin-left: 10px;">
        </div>



    </div>

    <div class="card">
        <div class="back">
            <h1>Back Part Information</h1>
            <p>Full Name:<br><span style="font-weight:bold">{{ $card->full_name }}</span></p>
            <p>Blood Group: {{ $card->blood_group }}</p>
            <p>Emergency Contact: {{ $card->emergency }}</p>
            <p>If you found this card, please contact the school at:</p>
            <p>{{ $card->school_address }} <br> {{ $card->school_contact }}</p>
        </div>


    </div>



</body>

</html>
