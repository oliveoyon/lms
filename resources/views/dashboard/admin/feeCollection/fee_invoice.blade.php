<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Fees Invoice</title>

    <!-- Your custom CSS styles -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        .invoice-container {
            max-width: 800px;
            width: 100%;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border-radius: 8px;
            overflow: hidden;
        }

        .invoice-header {
            text-align: center;
            width: 100%;
            margin-bottom: 20px;
        }

        .column {
            width: 48%;
            float: left;
            margin-right: 4%;
            margin-bottom: 20px;
        }

        .details {
            margin-bottom: 10px;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .invoice-table, .invoice-table th, .invoice-table td {
            border: 1px solid #ccc;
        }

        .invoice-table th, .invoice-table td {
            padding: 10px;
            text-align: left;
        }

        .total-amount {
            margin-top: 20px;
            text-align: right;
        }

        .amount-in-words {
            margin-top: 20px;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            width: 100%;
            clear: both;
        }
    </style>
</head>
<body>

    <div class="invoice-container">
        <div class="invoice-header">
            <h2>School Fees Invoice</h2>
        </div>
        <div class="column">
            <div class="details">School Name: Your School Name</div>
            <div class="details">Address: 123 School Street, City, Country</div>
            <div class="details">Phone: +1 (123) 456-7890</div>
            <div class="details">Website: www.yourschoolwebsite.com</div>
            <div class="details">Serial Number: INV123456</div>
            <div class="details">Date: January 10, 2024</div>
        </div>
        <div class="column">
            <div class="details">Student Name: John Doe</div>
            <div class="details">Student ID: 12345</div>
            <div class="details">Payment Mode: Online Payment</div>
        </div>

        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Serial</th>
                    <th>Description</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Tuition Fee</td>
                    <td>$500.00</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Books and Materials</td>
                    <td>$50.00</td>
                </tr>
                <!-- Add more rows as needed -->
            </tbody>
        </table>
        <div class="total-amounts">
            <p><strong>Total Amount: $550.00</strong></p>
        </div>
        <div class="amount-in-words">
            <p><strong>Amount in Words:</strong> Five Hundred and Fifty Dollars Only</p>
        </div>
        <div class="footer">
            <p>Thank you for your payment!</p>
        </div>
    </div>

</body>
</html>
