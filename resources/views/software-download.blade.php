
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('public/img/logo.jpg') }}" type="image/x-icon">
    <title>EatInsta POS Billing - Elevate Your Business, Simplify Your Life</title>

    <!--
    - favicon
  -->
    <link rel="shortcut icon" href="./favicon.svg" type="image/svg+xml">

    <!--
    - custom css link
  -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!--
    - google font link
  -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
        integrity="sha512-tIB+w1mt3v+eu+Cz6LxDMCeSXwCwY8QWZ46MfRmvPXvBwYwyX8/KuE18sLXqM7U11Sh1KkLtVLQPsS9siLDhfQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&family=Rubik:wght@400;500;600;700&family=Shadows+Into+Light&display=swap"
        rel="stylesheet">

    <!--
    - preload images
  -->
    <link rel="preload" as="image" href="{{ asset('img/hero-banner.png') }}" media="min-width: 768px">
    <link rel="preload" as="image" href="{{ asset('img/hero-banner-bg.png') }}" media="min-width: 768px">
    <link rel="preload" as="image" href="{{ asset('img/hero-bg.jpg') }}">

</head>






@include('body.header')



<main>
    <article>








<section class="hero" id="home" style="#">
                <div class="hero-image">
                    <img src="{{ asset('img/hero4.jpg') }}" alt="Background">
                </div>

                <div class="container">

                    <div class="hero-content">

                        <p class="hero-subtitle" style="color: #ff5722; font:poppin; ">EATINSTA Pos Billing</p>

                        <h2 class="h1 hero-title" style="color: white;">Get Desktop Billing Software</h2>

                        <p class="hero-text" style="color: grey;">Effortless Billing & Management for Restaurants, Sweet Shops, Groceries & More </p>






                    </div>

                    <figure class="hero-banner">
                    <img src="{{ asset('img/hero-banner-bg.png') }}" width="820" height="716" alt="" aria-hidden="true"
                         class="w-100 hero-img-bg">

                    <img src="{{ asset('img/desktop.png') }}" width="600" height="537" loading="lazy" alt="img"
                         class="w-100 hero-img">
                   
                    </figure>

                    

                </div>
            </section>



            
            <section class="section section-divider white cta"
                style="background-image: url('./assets/images/hero-bg.jpg')">
                <div class="container">

                    <div class="cta-content">


                        <h2 class="h2 section-title" style=" color: #333; margin-bottom: 10px;">
                        EatInsta POS Billing

                            <span class="span" style="color: #ff5722;">Now Available on Desktop!</span>
                        </h2>

                        <p class="section-text" style=" color: #666;">

                        Streamline your restaurant management with EatInsta's powerful desktop application. Enjoy quick billing,
                         inventory management, and seamless integration, all from the convenience of your desktop.t management.

                        </p>



                     
                        <a>    <button class="btn btn-hover"   herf="https://drive.google.com/file/d/1kLbJ6FA5sJEtFJEpxSTjDHWyeMCX3QLW/view?usp=sharing"   target="blank" >download Now</button> </a>
                    </div>

                    <figure class="cta-banner" style="text-align: center;">
                      <img src="{{ asset('img/desk.png') }}" width="500" height="500" loading="lazy" alt="callvcal" class="w-60 cta-img" style="width: 60%; max-width: 500px; height: auto;">
                    </figure>


                </div>
            </section>























            

    <!--
    - #FOOTER
  -->

    @include('body.footer')






            

</main>
</article>




    <!--
    - ionicon link
  -->
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>


    <!--
    - custom js link
  -->
  <script src="{{ asset('js/script.js') }}" defer></script>



<script>


document.addEventListener("DOMContentLoaded", function() {
const images = document.querySelectorAll(".banner-images img");
let currentImageIndex = 0;

setInterval(() => {
images[currentImageIndex].classList.remove("active");
currentImageIndex = (currentImageIndex + 1) % images.length;
images[currentImageIndex].classList.add("active");
}, 3000);
});







</script>
