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
            width: 15%;
        }

        .school_info {
            float: left;
            text-align: center;
            width: 70%;
        }

        .right {
            float: right;
            width: 15%;
            text-align: right;
            padding-top: -90px;
            /* Adjust as needed to vertically align the barcode with the logo */
        }


        h1,
        h2 {
            margin: 0;
        }

        p {
            margin: 5px 0;
        }

        h1 {
            font-size: 22px;
        }

        h2 {
            font-size: 20px;
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
        @php
        $gs = \App\Models\Admin\GeneralSetting::find(1);
        @endphp
        <!--mpdf
        <htmlpageheader name="myheader">
            <div class="header">
                <div class="photo">
                    <img src="{{ public_path('storage/img/logo/'.$gs->school_logo) }}" alt="School Logo">
                </div>
                <div class="school_info">
                    <h1>{{$gs->school_title}}</h1>
                    <p>{{$gs->school_address}}, Phone: {{$gs->school_phone}}, {{$gs->school_phone1}}</p>
                    <p>Email: {{$gs->school_email}}, Web: {{url('/')}}</p>
                </div>
                <div class="right">
                    <?php
                    $barcodeImage = 'data:image/png;base64,' . DNS2D::getBarcodePNG('https://shalikhaschool.edu.bd/', 'QRCODE');
                    echo '<img width="80%"  src="' . $barcodeImage . '" alt="barcode"  />';
                    ?>
                </div>
            </div>
        </htmlpageheader>

        <sethtmlpageheader name="myheader" value="on" show-this-page="1" />
        mpdf-->




        <h2 style="text-align: center; margin-top: 0px; padding-top: 0px;">{!! $title !!}</h2>

        <div style="clear: both;"></div> <!-- Clear the float -->

        {!! $data !!}


    </div>
</body>

</html>