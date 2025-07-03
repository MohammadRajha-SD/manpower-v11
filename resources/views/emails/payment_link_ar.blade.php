<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رابط الدفع</title>
    <style>
        body {
            font-family: "Cairo", sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            direction: rtl;
        }

        .container {
            background-color: #ffffff;
            padding: 20px;
            max-width: 400px;
            margin: auto;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
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
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <h1>معلومات الدفع</h1>
            <p>مرحباً {{ $user->name }}،</p>
        </div>

        <p>شكراً لحجزك معنا! يرجى إتمام عملية الدفع من خلال الرابط التالي:</p>

        <div class="payment-link">
            <a href="{{ $paymentUrl }}" class="payment-button">ادفع الآن</a>
        </div>

        <div class="company-details">
            <p>شكرًا لاختيارك HPower!</p>
            <p>إذا كان لديك أي استفسارات، لا تتردد في التواصل معنا.</p>
        </div>
    </div>

</body>

</html>