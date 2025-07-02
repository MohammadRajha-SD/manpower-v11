<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background-color: #007bff;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .header img {
            max-width: 120px;
            margin-bottom: 10px;
        }
        h1 {
            font-size: 24px;
            margin: 0;
            color: #ffffff;
        }
        .content {
            padding: 20px;
            text-align: left;
        }
        .content h2 {
            font-size: 22px;
            color: #333333;
            margin-bottom: 10px;
        }
        .content p {
            font-size: 16px;
            line-height: 1.5;
            color: #333333;
            margin-bottom: 20px;
        }
        .content a.verify-button {
            display: inline-block;
            background-color: #007bff;
            color: #ffffff;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
            width: 100%;
            max-width: 200px;
        }
        .footer {
            background-color: #f4f4f4;
            text-align: center;
            padding: 15px;
            font-size: 12px;
            color: #777777;
        }
        .footer a {
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Header with Logo and Welcome Message -->
    <div class="header">
        <img src="{{ asset('images/logo_default.png') }}" alt="HPower Logo">
        <h1>Welcome, {{ $user->name }}!</h1>
    </div>

    <!-- Main Content -->
    <div class="content">
        <h2>Thank You for Joining Us!</h2>
        <p>We are excited to have you with us at HPower. To get started, please confirm your email address:</p>
        <p style="text-align: center;"><a href="{{ $verificationUrl }}" class="verify-button">Verify Email Address</a></p>
        <p>If you didn’t create an account, please disregard this email.</p>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; {{ date('Y') }} HPower. All rights reserved.</p>
        <p><a href="#">Unsubscribe</a> | <a href="#">Contact Us</a></p>
    </div>
</div>
</body>
</html>
