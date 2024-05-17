<!DOCTYPE html>
<html>
    
<head>
    <style>
        /* Add your CSS styles here */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo img {
            max-width: 150px;
            height: auto;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3490dc;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="{{ $data['app_logo'] }}" alt="Logo">
        </div>
        <h1>Account Deletion Request Received</h1>
        <p>Hello {{ $data['name'] }},</p>
        <p>We have received your request to delete your account. Please take note of the following details:</p>
        <ul>
            <li>Account data deletion is scheduled in 7 days.</li>
            <li>Account data deletion date is {{$data['date']}}</li>
            <li>You have the option to cancel this request before deletion.</li>
            
        </ul>
        <p>If you didn't initiate this request or wish to keep your account, you can ignore this message.</p>
        <p><a class="button" href="{{ $data['cancel_url'] }}">Cancel Deletion Request</a></p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>
</html>
