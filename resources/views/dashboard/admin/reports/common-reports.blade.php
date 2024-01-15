<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            font-family: bangla;
        }

        .container {
            width: 100%;
            margin: 0 auto;
        }

        .header {
            margin-bottom: 30px;
        }

        .photo {
            float: left;
            margin-right: 20px;
            width: 20%;
        }

        .school_info {
            float: left;
        }

        h1,
        h2 {
            margin: 0;
        }

        p {
            margin: 5px 0;
        }

        .info-column {
            float: left;
            margin-bottom: 20px;
            margin-right: 0; /* No margin between the columns */
        }

        .left {
            width: 60%;
        }


        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        .amount-in-words {
            text-align: center;
            clear: both;
            /* Clear the float to prevent issues */
            margin-top: 20px;
        }

        .thank-you {
            margin-top: 20px;
        }

        /* Add space between columns in the no-border-table */
        .info-column:not(:last-child) {
            margin-right: 4%;
            /* Adjust the margin as needed */
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="photo">
                <img src="{{ public_path('storage/img/logo/logo.png') }}" alt="School Logo">
            </div>
            <div class="school_info">
                <h1>Shalikha Thana High School</h1>
                <p>Hajrahati, Shalikha, Magura</p>
                <p>Phone: 123-456-7890</p>
                <p>Email: shalikhaschool@gmail.com</p>
                <p>Web: https://shalikhaschool.edu.bd/</p>
            </div>
        </div>

        <h2 style="text-align: center">{!! $title !!}</h2>

        <div style="clear: both;"></div> <!-- Clear the float -->

        {!! $data !!}


    </div>
</body>

</html>
