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

<body id="top">

 @include('body.header')



    <main>
        <article>

            <!--
        - #HERO
      -->

            <section class="hero" id="home" style="#">
                <div class="hero-image">
                    <img src="{{ asset('img/hero4.jpg') }}" alt="Background">
                </div>

                <div class="container">

                    <div class="hero-content">

                        <p class="hero-subtitle" style="color: #ff5722; font:poppin; ">EATINSTA</p>

                        <h2 class="h1 hero-title" style="color: white;">Pos Billing Software</h2>

                        <p class="hero-text" style="color: grey;">Effortless Billing & Management for Restaurants, Sweet Shops, Groceries & More </p>




                        <a href="https://play.google.com/store/apps/details?id=org.callvcal.eatinsta.billing" >
                            <button class="btn" >Download</button>
                        </a>


                    </div>

                    <figure class="hero-banner">
                    <img src="{{ asset('img/hero-banner-bg.png') }}" width="820" height="716" alt="" aria-hidden="true"
                         class="w-100 hero-img-bg">

                    <img src="{{ asset('img/heroi.png') }}" width="700" height="637" loading="lazy" alt="img"
                         class="w-100 hero-img">
                   
                    </figure>

                    

                </div>
            </section>




  <!-- 
        - #CTA
      -->

      <section class="section section-divider white cta" style="background-image: url('./assets/images/hero-bg.jpg')">
        <div class="container">

          <div class="cta-content">


          <h2 class="h2 section-title" style=" color: #333; margin-bottom: 10px;">
          Effortless Billing & Management 

  <span class="span" style="color: #ff5722;">for Restaurants, Sweet Shops, Groceries & More.</span>
</h2>

<p class="section-text" style=" color: #666;">


Because your business deserves the best. EatInsta POS Billing is more than just software; it's a partner in your success. Let us help you provide exceptional service, streamline your operations, and grow your business.
</p>


            <button class="btn btn-hover"><a href="https://play.google.com/store/apps/details?id=org.callvcal.eatinsta.billing">Download Now</a></button>
          </div>

          <figure class="cta-banner">

          <div class="banner-images">
            <img src="{{asset('img/1.jpg')}}" alt="Eatinsta" class="active">
            <img src="{{asset('img/2.jpg')}}" alt="Eatinsta">
            <img src="{{asset('img/3.jpg')}}" alt="Eatinsta">
            <img src="{{asset('img/4.jpg')}}" alt="Eatinsta">
            <img src="{{asset('img/5.jpg')}}" alt="Eatinsta">
            <img src="{{asset('img/6.jpg')}}" alt="Eatinsta">
            <img src="{{asset('img/7.jpg')}}" alt="Eatinsta">
            <img src="{{asset('img/8.jpg')}}" alt="Eatinsta">
            <img src="{{asset('img/9.jpg')}}" alt="Eatinsta">
            
            
          </div> 



            
          </figure>

        </div>
      </section>



























      <!-- 
        - #PROMO
      -->

      <section class="section section-divider white promo">
        <div class="container">

          <ul class="promo-list has-scrollbar">


          <li class="promo-item">
          <div class="promo-card">
              <h3 class="h3 card-title">Free</h3>

               <div class="card-icon">
                 <h1 style="color: #4CAF50; font-size: 1.5em; font-weight: bold;">Price: ₹0</h1>
                 <h1 style="color: #4CAF50; font-size: 1em; font-weight: light;">Price: ₹0 for Other Country</h1>
               </div>

               <p class="card-text">
                   <span style="color: green;">✔</span> Full Access
               </p>
               <p class="card-text">
                   <span style="color: green;">✔</span> Customer support
               </p>
               <p class="card-text">
                    <span style="color: red;">✔</span> Ads will be shown
               </p>
  
        <a href="https://play.google.com/store/apps/details?id=org.callvcal.eatinsta.billing" style="background-color: #FF5733; color: white; border: 1px; padding: 15px 32px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin: 4px 2px; cursor: pointer; border-radius: 12px;">
         Subscribe
        </a>
  </div>
</li>



           
          <li class="promo-item">
          <div class="promo-card">
              <h3 class="h3 card-title">Monthly</h3>

               <div class="card-icon">
                 <h1 style="color: #4CAF50; font-size: 1.5em; font-weight: bold;">Price: ₹300</h1>
                 <h1 style="color: #4CAF50; font-size: 1em; font-weight: light;">Price: $5 for Other Country</h1>
               </div>

               <p class="card-text">
                   <span style="color: green;">✔</span> Full Access
               </p>
               <p class="card-text">
                   <span style="color: green;">✔</span> Pro Customer support
               </p>
               <p class="card-text">
                    <span style="color: red;">✖</span> Ads will be shown
               </p>
  
        <a href="https://play.google.com/store/apps/details?id=org.callvcal.eatinsta.billing" style="background-color: #FF5733; color: white; border: 1px; padding: 15px 32px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin: 4px 2px; cursor: pointer; border-radius: 12px;">
         Subscribe
        </a>
  </div>
</li>




<li class="promo-item">
          <div class="promo-card">
              <h3 class="h3 card-title">Anually</h3>

               <div class="card-icon">
                 <h1 style="color: #4CAF50; font-size: 1.5em; font-weight: bold;">Price: ₹3500</h1>
                 <h1 style="color: #4CAF50; font-size: 1em; font-weight: light;">Price: $50 for Other Country</h1>
               </div>

               <p class="card-text">
                   <span style="color: green;">✔</span> Full Access
               </p>
               <p class="card-text">
                   <span style="color: green;">✔</span>Pro Customer support
               </p>
               <p class="card-text">
                    <span style="color: red;">✖</span> Ads will be shown
               </p>
  
        <a href="https://play.google.com/store/apps/details?id=org.callvcal.eatinsta.billing" style="background-color: #FF5733; color: white; border: 1px; padding: 15px 32px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin: 4px 2px; cursor: pointer; border-radius: 12px;">
         Subscribe
        </a>
  </div>
</li>


<li class="promo-item">
          <div class="promo-card">
              <h3 class="h3 card-title">3 Years</h3>

               <div class="card-icon">
                 <h1 style="color: #4CAF50; font-size: 1.5em; font-weight: bold;">Price: ₹10000</h1>
                 <h1 style="color: #4CAF50; font-size: 1em; font-weight: light;">Price: $120 for Other Country</h1>
               </div>

               <p class="card-text">
                   <span style="color: green;">✔</span> Full Access
               </p>
               <p class="card-text">
                   <span style="color: green;">✔</span>Pro Customer support
               </p>
               <p class="card-text">
                    <span style="color: red;">✖</span> Ads will be shown
               </p>
  
        <a href="https://play.google.com/store/apps/details?id=org.callvcal.eatinsta.billing" style="background-color: #FF5733; color: white; border: 1px; padding: 15px 32px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin: 4px 2px; cursor: pointer; border-radius: 12px;">
         Subscribe
        </a>
  </div>
</li>



<li class="promo-item">
          <div class="promo-card">
              <h3 class="h3 card-title">Partnership </h3>

               <div class="card-icon">
                 <h1 style="color: #4CAF50; font-size: 1.5em; font-weight: bold;">Price: ₹20000</h1>
                 <h1 style="color: #4CAF50; font-size: 1em; font-weight: light;">Price: $300 for Other Country</h1>
               </div>

               <p class="card-text">
                   <span style="color: green;">✔</span> Full Access on your server
               </p>
               <p class="card-text">
                   <span style="color: green;">✔</span> Customer support
               </p>
               <p class="card-text">
                    <span style="color: red;">✔</span> Get Custom dashboard
               </p>
  
        <a href="https://play.google.com/store/apps/details?id=org.callvcal.eatinsta.billing" style="background-color: #FF5733; color: white; border: 1px; padding: 15px 32px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin: 4px 2px; cursor: pointer; border-radius: 12px;">
         Subscribe
        </a>
  </div>
</li>


          </ul>

        </div>
      </section>












            <!--
        - #FOOD MENU
      -->






            <!--
        - #CTA
      -->

            <section class="section section-divider white cta"
                style="background-image: url('./assets/images/hero-bg.jpg')">
                <div class="container">

                    <div class="cta-content">


                        <h2 class="h2 section-title" style=" color: #333; margin-bottom: 10px;">
                        Multi-Platform Accessibility

                            <span class="span" style="color: #ff5722;">Streamline Your Business Effortlessly!</span>
                        </h2>

                        <p class="section-text" style=" color: #666;">

                        Access your business data anytime, anywhere with our app, website, 
                        and desktop software for seamless and efficient management.

                        </p>



                        <button class="btn btn-hover" herf="https://play.google.com/store/apps/details?id=org.callvcal.eatinsta.billing">download Now</a></button>
                    </div>

                    <figure class="cta-banner" style="text-align: center;">
                      <img src="{{ asset('img/apps.png') }}" width="500" height="500" loading="lazy" alt="callvcal" class="w-60 cta-img" style="width: 60%; max-width: 500px; height: auto;">
                    </figure>


                </div>
            </section>




            <!--
        - #DELIVERY
      -->




            <section class="section section-divider gray delivery">
                <div class="container">

                    <div class="delivery-content">

                        <h2 class="h2 section-title">
                            Moment of happiness,<span class="span"> Delivered </span> Right on time!
                        </h2>

                        <p class="section-text">
                            Experience punctuality redefi ned with our delivery service.
                            We guarantee timely and accurate deliveries, ensuring your order arrives promptly.
                            Our commitment to exceptional
                            customer experience means every delivery is handled with care, ensuring satisfaction from
                            order to doorstep
                        </p>


                        <button class="btn btn-hover" onclick="redirectToApp()">Order Now</a></button>
                    </div>

                    <figure class="delivery-banner">
                        <img src="{{ asset('img/delivery-banner-bg.png') }}" width="700" height="602"
                            loading="lazy" alt="clouds" class="w-100">

                        <img src="{{ asset('img/boy.svg') }}" width="700" height="600" loading="lazy"
                            alt="delivery boy" class="w-100 delivery-img" data-delivery-boy>
                    </figure>

                </div>
            </section>


            <!--
        - #TESTIMONIALS
      -->

            <section class="section section-divider white testi">
                <div class="container">

                    <p class="section-subtitle">Testimonials</p>

                    <h2 class="h2 section-title">
                        Our Customers <span class="span">Reviews</span>
                    </h2>

                    <p class="section-text">
                        Food is any substance consumed to provide nutritional
                        support for an organism.
                    </p>

                    <ul class="testi-list has-scrollbar">

                        <li class="testi-item">
                            <div class="testi-card">

                                <div class="profile-wrapper">

                                    <figure class="avatar">
                                        <img src="{{ asset('img/sonu.png') }}" width="80" height="80"
                                            loading="lazy" alt="sonu sah">
                                    </figure>

                                    <div>
                                        <h3 class="h4 testi-name">Sonu Sah</h3>

                                        <p class="testi-title">CEO callvcal</p>
                                    </div>

                                </div>

                                <blockquote class="testi-text">
                                    "🌟🌟🌟🌟

                                    Food Quality: ⭐️⭐️⭐️⭐️⭐️

                                    Each dish bursts with Bihari essence. Palate-pleasing and aromatic. A delightful,
                                    culinary adventure."
                                </blockquote>

                                <div class="rating-wrapper">
                                    <ion-icon name="star"></ion-icon>
                                    <ion-icon name="star"></ion-icon>
                                    <ion-icon name="star"></ion-icon>
                                    <ion-icon name="star"></ion-icon>
                                    <ion-icon name="star"></ion-icon>
                                </div>

                            </div>
                        </li>

                        <li class="testi-item">
                            <div class="testi-card">

                                <div class="profile-wrapper">

                                    <figure class="avatar">
                                        <img src="{{ asset('img/mukul.png') }}" width="80" height="80"
                                            loading="lazy" alt="mukul kumar">
                                    </figure>

                                    <div>
                                        <h3 class="h4 testi-name">Mukul Kumar</h3>

                                        <p class="testi-title">Patna</p>
                                    </div>

                                </div>

                                <blockquote class="testi-text">
                                    "🌟🌟🌟🌟🌟

                                    Food Quality: ⭐️⭐️⭐️⭐️⭐️

                                    Savory flavors explode! Authentic Bihari spices dance on taste buds. A culinary
                                    journey worth savoring."
                                </blockquote>

                                <div class="rating-wrapper">
                                    <ion-icon name="star"></ion-icon>
                                    <ion-icon name="star"></ion-icon>
                                    <ion-icon name="star"></ion-icon>
                                    <ion-icon name="star"></ion-icon>
                                    <ion-icon name="star"></ion-icon>
                                </div>

                            </div>
                        </li>

                        <li class="testi-item">
                            <div class="testi-card">

                                <div class="profile-wrapper">

                                    <figure class="avatar">
                                        <img src="{{ asset('img/sah.png') }}" width="80" height="80"
                                            loading="lazy" alt="sah">
                                    </figure>

                                    <div>
                                        <h3 class="h4 testi-name">Neeraj Mehta</h3>

                                        <p class="testi-title">Kankarbaag</p>
                                    </div>

                                </div>

                                <blockquote class="testi-text">
                                    "🌟🌟🌟🌟🌟

                                    Food Quality: ⭐️⭐️⭐️⭐️⭐️

                                    Exquisite Bihari flavors! A symphony of spices that transports taste buds to the
                                    heart of Bihar. Unforgettable!"
                                </blockquote>

                                <div class="rating-wrapper">
                                    <ion-icon name="star"></ion-icon>
                                    <ion-icon name="star"></ion-icon>
                                    <ion-icon name="star"></ion-icon>
                                    <ion-icon name="star"></ion-icon>
                                    <ion-icon name="star"></ion-icon>
                                </div>

                            </div>
                        </li>

                    </ul>

                </div>


                </div>
            </section>











            <!--
        - #BANNER
      -->

            <section class="section section-divider gray banner">
                <div class="container">

                    <ul class="banner-list">

                        <li class="banner-item banner-lg">
                            <div class="banner-card">

                                <img src="{{ asset('img/banner-1.jpg') }}" width="550" height="450"
                                    loading="lazy" alt="Discount For Delicious Tasty Burgers!" class="banner-img">

                                <div class="banner-item-content">
                                    <p class="banner-subtitle"></p>

                                    <h3 class="banner-title">Discount For Delicious Tasty Burgers!</h3>

                                    <p class="banner-text">Sale off only this week</p>

                                    <button class="btn">Order Now</button>
                                </div>

                            </div>
                        </li>

                        <li class="banner-item banner-sm">
                            <div class="banner-card">

                                <img src="{{ asset('img/banner-2.jpg') }}" width="550" height="465"
                                    loading="lazy" alt="Delicious Pizza" class="banner-img">

                                <div class="banner-item-content">
                                    <h3 class="banner-title">Delicious Pizza</h3>

                                    <p class="banner-text"> offer</p>

                                    <button class="btn">Order Now</button>
                                </div>

                            </div>
                        </li>

                        <li class="banner-item banner-sm">
                            <div class="banner-card">

                                <img src="{{ asset('img/banner-3.jpg') }}" width="550" height="465"
                                    loading="lazy" alt="American Burgers" class="banner-img">

                                <div class="banner-item-content">
                                    <h3 class="banner-title">American Burgers</h3>

                                    <p class="banner-text"> Offer</p>

                                    <button class="btn">Order Now</button>
                                </div>

                            </div>
                        </li>

                        <li class="banner-item banner-md">
                            <div class="banner-card">

                                <img src="{{ asset('img/banner-4.jpg ') }}" width="550" height="220"
                                    loading="lazy" alt="Tasty Buzzed Pizza" class="banner-img">

                                <div class="banner-item-content">
                                    <h3 class="banner-title">Tasty Buzzed Pizza</h3>

                                    <p class="banner-text">Sale off only this week</p>

                                    <button class="btn">Order Now</button>
                                </div>

                            </div>
                        </li>

                    </ul>

                </div>

                </div>
                </li>



                </div>
            </section>





            <!--
        - #BLOG
      -->







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
