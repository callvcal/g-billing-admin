<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eatinsta</title>
    <style>
        /* Define your desired styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
        }

        .message {
            font-size: 16px;
            margin-bottom: 20px;
            text-align: center;
        }

        .card {
            background-color: #f5f5f5;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .card-title {
            font-weight: bold;
            margin-bottom: 10px;
            color: #007bff;
            font-size: 20px;
            text-align: center;
        }

        .otp-container {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .otp-digit {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border: 1px solid #ccc;
            font-size: 20px;
            font-weight: bold;
            padding: auto;
            text-align: center;
            margin: 10px;
            border-radius: 5px;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2 class="company-name">Eatinsta</h2>
        </div>

        <div class="message">
            <p>Hello dear user,</p>
            <p>Here is your One-Time Password (OTP) for verification:</p>
        </div>

        <div class="card">
            <div class="card-title">OTP Code: <span>{{implode(" ",str_split($otp))}}</span></div>
            <div class="otp-container">
                {{-- Display OTP digits --}}
                {{-- <?php
                  // for ($i = 0; $i < strlen($otp); $i++) {
                  //   $digit = $otp[$i];
                  //   echo "<div class='otp-digit'>$digit</div>";
                  // }
                ?> --}}
            </div>
        </div>

        <div class="footer">
            <p>Please do not share this OTP with anyone. It expires after a certain period.</p>
        </div>
    </div>
</body>

</html>
