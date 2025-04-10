<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Payment Link</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            max-width: 400px;
            width: 100%;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            color: #333;
        }

        .header p {
            color: #777;
        }

        .payment-link {
            text-align: center;
            margin: 20px 0;
        }

        .payment-button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            font-size: 16px;
            border-radius: 4px;
            text-align: center;
            transition: background-color 0.3s;
        }

        .payment-button:hover {
            background-color: #45a049;
        }

        .company-details {
            text-align: center;
            font-size: 14px;
            color: #555;
            margin-top: 20px;
        }

        .company-details p {
            margin: 5px 0;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <h1>Payment Information</h1>
            <p>Hello {{ $user->name }},</p>
        </div>

        <p>Thank you for booking with us! Please complete your payment by clicking the link below:</p>

        <div class="payment-link">
            <a href="{{ $paymentUrl }}" class="payment-button">Pay Here</a>
        </div>
        <div class="company-details">
            <p>Thank you for choosing HPower!</p>
            <p>If you have any questions, feel free to contact us.</p>
        </div>
    </div>

</body>

</html>