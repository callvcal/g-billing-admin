<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Page</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        .containerabout {
            padding-top: 100px;
            max-width: 1000px;
            margin: 50px auto;
            display: flex;
            justify-content: space-around;
        }
        .section {
            width: 45%;
            text-align: center;
        }
        .section img {
            max-width: 100%;
            border-radius: 50%;
            margin-bottom: 20px;
        }
        .social-links a {
            margin: 0 10px;
            font-size: 20px;
            text-decoration: none;
            color: #333;
        }
        .call-button {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            background-color: #3498db;
            color: #fff;
            text-decoration: none;
            cursor: pointer;
        }
        .call-button:hover {
            background-color: #2980b9;
        }
    </style>
</head>


<body>
@include('body.header')

    <div class="containerabout">
        <div class="section">
            <img src="{{ asset('photos/img/mukul.png') }}" alt="Shop Owner">
            <h2>Shop Owner</h2>
            <div class="social-links">
                <a href="https://play.google.com/store/apps/details?id=org.com.callvcall">Facebook</a>
                <a href="https://play.google.com/store/apps/details?id=org.com.callvcall">Twitter</a>
                <a href="https://play.google.com/store/apps/details?id=org.com.callvcall">Instagram</a>
            </div>
            <a href="tel:9721-184773" class="call-button">Call</a>
        </div>
        <div class="section">
            <img src="{{ asset('photos/img/sonu.png') }}" alt="Company Owner">
            <h2> Developer</h2>
            <div class="social-links">
                <a href="https://play.google.com/store/apps/details?id=org.com.callvcall">Facebook</a>
                <a href="https://play.google.com/store/apps/details?id=org.com.callvcall">LinkedIn</a>
                <a href="https://play.google.com/store/apps/details?id=org.com.callvcall">GitHub</a>
                <a href="https://play.google.com/store/apps/details?id=org.com.callvcall">YouTube</a>
            </div>
            <a href="tel:9721-184773" class="call-button">Call</a>
        </div>
    </div>


@include('body.footer')



 <!-- 
    - custom js link
  -->
  <script src="{{ asset('js/script.js') }}" defer></script>

 <!-- 
    - ionicon link
  -->
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

    
</body>

</html>
