<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Link</title>
</head>
<body>
    <p>Hello {{ $user->name }},</p>

    <p>Thank you for booking with us! Please complete your payment by clicking the link below:</p>

    <p><a href="{{ $paymentUrl }}">Click here to pay now</a></p>

    <p>If you have any questions, feel free to contact us.</p>

    <p>Best regards,<br>HPower</p>
</body>
</html>
