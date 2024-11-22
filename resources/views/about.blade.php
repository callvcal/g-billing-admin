<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('public/img/logo.png') }}" type="image/x-icon">
    <title>EatPlanet Restro & Lounge - Great Food Make Great Mood!</title>

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

<body id="top">

    <!--
    - #HEADER
  -->

    <header class="header" data-header>
        <div class="container">


            <a href="/" class="logo">
                <img src="{{ asset('public/img/logoname.png') }}" alt="Eatplan8" style="width: 100px; height: auto;">
            </a>



            <nav class="navbar" data-navbar>
                <ul class="navbar-list">

                    <li class="nav-item">
                        <a href="/" class="navbar-link" data-nav-link>Home</a>
                    </li>

                    <li class="nav-item">
                        <a href="/about" class="navbar-link" data-nav-link>About Us</a>
                    </li>

                </ul>
            </nav>


            <!--<button class="btn btn-hover" onclick="redirectToApp()">Login</button>-->

            <a href="{{ route('app') }}">

                <button class="btn">Login</button>
            </a>



            <button class="nav-toggle-btn" aria-label="Toggle Menu" data-menu-toggle-btn>
                <span class="line top"></span>
                <span class="line middle"></span>
                <span class="line bottom"></span>
            </button>
        </div>

        </div>
    </header>

    <script>
        function redirectToApp() {
            window.location.href = "{{ route('app') }}";
        }
    </script>



    <main>
        <article>

            <!--
        - #HERO
      -->

            <section class="hero" id="home" style="#">
                <div class="hero-image">
                    <img src="{{ asset('public/img/hero2.jpg') }}" alt="Background">
                </div>

                <div class="container">

                    <div class="hero-content">

                        <p class="hero-subtitle" style="color: #ff5722;">Eatplan8 Restro & Lounge </p>

                        <h2 class="h1 hero-title" style="color: white;">RAMAGAYATRI FOODS PRIVATE LIMITED.</h2>

                        <p class="hero-text" style="color: grey;">Welcome to Eatplan8 Restro & Lounge, a venture of
                            Ramagayatri
                            Foods Private Limited. We offer food delivery, dine-in, and takeaway services.
                            Book our versatile party hall for anniversaries, birthdays, and more. Enjoy fresh, quality
                            ingredients
                            and exceptional service. Make your next meal or event unforgettable at Eatpln8! </p>





                    </div>

                    <figure class="hero-banner">
                        <img src="{{ asset('public/img/hero-banner-bg.png') }}" width="820" height="716" alt="eatplan8"
                            aria-hidden="true" class="w-100 hero-img-bg">

                        <img src="{{ asset('public/img/logo.png') }}" width="600" height="537" loading="lazy"
                            alt="eatplan8" class="w-100 hero-img">

                    </figure>



                </div>
            </section>






            <section class="section section-divider gray about" id="about">
                <div class="container">

                    <div class="about-banner">
                        <img src="{{ asset('public/img/eatplan8ceo.jpg') }}" width="409" height="359" loading="lazy"
                            alt="Founder and ceo eatplan8" class="w-100 about-img">


                    </div>

                    <div class="about-content">

                        <h2 class="h2 section-title">
                            Piyush Prasoon
                            <span class="span"></span>
                        </h2>

                        <p class="section-text">
                            Piyush Prasoon (B.Tech Mechanical Engineering), Founder and CEO of Eatplan8 Restro & Lounge,
                            Ramagayatri Foods Private Limited,
                            is dedicated to creating memorable dining experiences. With a passion for quality,
                            he offers food delivery, dine-in, and takeaway services, as well as versatile party halls
                            for events. Experience Piyush’s commitment to excellence at Eatpln8 and make your next meal
                            or event unforgettable.
                        </p>

                        <ul class="about-list">
                            <li class="about-item">
                                <a href="https://wa.me/9852914500" target="_blank">
                                    <ion-icon name="logo-whatsapp"></ion-icon>
                                    <span class="span">WhatsApp</span>
                                </a>
                            </li>

                            <li class="about-item">
                                <a href="https://www.facebook.com/profile.php?id=61558517727941" target="_blank">
                                    <ion-icon name="logo-facebook"></ion-icon>
                                    <span class="span">Facebook</span>
                                </a>
                            </li>

                            <li class="about-item">
                                <a href="https://www.instagram.com/im_eatplan8/" target="_blank">
                                    <ion-icon name="logo-instagram"></ion-icon>
                                    <span class="span">Instagram</span>
                                </a>
                            </li>
                        </ul>




                    </div>

                </div>
            </section>
            
            <section class="section section-divider white cta"
                style="background-image: url('./assets/images/hero-bg.jpg')">
                <div class="container">

                    <div class="cta-content">


                        <h2 class="h2 section-title" style=" color: #333; margin-bottom: 10px;">
                            Your perfect venue for

                            <span class="span" style="color: #ff5722;">unforgettable moments.</span>
                        </h2>

                        <p class="section-text" style=" color: #666;">


                            Celebrate at Eatplan8! Book our versatile party hall for birthdays, anniversaries, corporate
                            events, and buffet parties. Enjoy delicious food and impeccable service. Contact us at
                            9102424888
                        </p>


                        <button class="btn btn-hover"><a href="/">Order Now</a></button>
                    </div>

                    <figure class="cta-banner">

                        <div class="banner-images">
                            <img src="{{ asset('public/img/1.png') }}" alt="eatplan8" class="active">
                            <img src="{{ asset('public/img/2.png') }}" alt="eatplan8">
                            <img src="{{ asset('public/img/3.png') }}" alt="eatplan8">
                            <img src="{{ asset('public/img/4.png') }}" alt="eatplan8">
                            <img src="{{ asset('public/img/5.png') }}" alt="eatplan8">
                            <img src="{{ asset('public/img/6.png') }}" alt="eatplan8">
                            <img src="{{ asset('public/img/7.png') }}" alt="eatplan8">
                            <img src="{{ asset('public/img/8.png') }}" alt="eatplan8">
                            <img src="{{ asset('public/img/9.png') }}" alt="eatplan8">

                        </div>



                    </figure>

                </div>
            </section>






















        </article>
    </main>





    <!--
    - #FOOTER
  -->

    @include('body.footer')




    <!--
    - #BACK TO TOP
  -->

    <a href="#top" class="back-top-btn" aria-label="Back to top" data-back-top-btn>
        <ion-icon name="chevron-up"></ion-icon>
    </a>





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





    <!--
    - ionicon link
  -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>



</body>

</html>