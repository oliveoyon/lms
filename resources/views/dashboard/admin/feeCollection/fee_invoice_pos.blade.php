<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Bill Receipt</title>
    <style>
        body {
            font-family: monospace; /* Use a monospace font for consistent character spacing */
            font-size: {{ $printmeta['fontsize'] }}px;
        }

        .container {
            width: {{ $printmeta['width'] }}mm; /* Adjust based on your paper width */
            margin: 0 auto;
        }

        .header {
            text-align: center;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }

        .item {
            margin-bottom: 5px;
        }

        .total {
            font-weight: bold;
            margin-top: 10px;
        }

        .footer {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>School Name</h2>
            <p>Address Line 1</p>
            <p>Address Line 2</p>
            <p>Phone: 123-456-7890</p>
        </div>
        <div class="line"></div>

        <div class="item">
            <span>Student Name: {{ $payments[0]->std_name }}</span>
        </div>
        <div class="item">
            <span>Class: {{ $payments[0]->class_name }}</span>
        </div>
        <div class="item">
            <span>Student ID: {{ $payments[0]->std_id }}</span>
        </div>

        <div class="line"></div>

        <div class="item">
            <span>Invoice No: 00123</span>
            <span style="float: right;">Payment Method: {{ $payments[0]->payment_method }}</span>
        </div>
        <div class="item">
            <span>Payment Date: {{ \Carbon\Carbon::parse($payments[0]->payment_date)->format('F d, Y') }}</span>
        </div>


        <div class="line"></div>

        <div class="item">
            <span>S/N</span>
            <span>Particular</span>
            <span style="float: right;">Amount</span>
        </div>

        @foreach ($payments as $payment)
        <div class="item">
            <span>{{$loop->iteration}}. {{$payment->fee_description}}</span>
            <span style="float: right;">{{$payment->amount_paid}}</span>
        </div>
        @endforeach


        <!-- Add more items as needed -->


        <div class="line"></div>

        <div class="total">
            <span>Total:</span>
            <span style="float: right;">{{$payments->sum('amount_paid')}}</span>
        </div>

        <div class="line"></div>

        <div class="footer">
            <p>Thank you for your payment!</p>
        </div>
    </div>
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
