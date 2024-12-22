
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Complete</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f3f3;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            text-align: center;
        }

        .container h1 {
            color: #27ae60;
            font-size: 28px;
            margin-bottom: 20px;
        }

        .container p {
            font-size: 16px;
            color: #333;
            margin-bottom: 30px;
        }

        .success-message {
            font-size: 18px;
            color: #27ae60;
            font-weight: bold;
        }

        .note {
            background-color: #f0f8ff;
            padding: 15px;
            border-left: 5px solid #3b82f6;
            color: #1d4ed8;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .button-group {
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .button-group button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        .button-group .back-btn {
            background-color: #ccc;
            color: #333;
        }

        .button-group .home-btn {
            background-color: #9b59b6;
            color: white;
        }

        .button-group .home-btn:hover {
            background-color: #8e44ad;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Thank You for Completing Registration!</h1>
        <p class="success-message">We’ve received your application and are currently reviewing it.</p>
        <p>You will receive an email with the status of your application within 5 business days.</p>

        <div class="note">
            <p><strong>Important:</strong> Make sure to check your email inbox (and spam folder) for updates. If you don't hear from us within 5 business days, feel free to reach out for more details.</p>
        </div>

        <div class="button-group">
        <button class="home-btn" onclick="window.location.href='../index.php'">Go to Home</button>
        </div>
    </div>
</body>
</html>
