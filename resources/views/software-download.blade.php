


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




                        <a href="https://play.google.com/store/apps/details?id=org.callvcal.eatinsta.billing" >
                            <button class="btn" >Download</button>
                        </a>


                    </div>

                    <figure class="hero-banner">
                    <img src="{{ asset('img/hero-banner-bg.png') }}" width="820" height="716" alt="" aria-hidden="true"
                         class="w-100 hero-img-bg">

                    <img src="{{ asset('img/desktop.png') }}" width="700" height="637" loading="lazy" alt="img"
                         class="w-100 hero-img">
                   
                    </figure>

                    

                </div>
            </section>












            

    <!--
    - #FOOTER
  -->

    @include('body.footer')






            

</main>
</article>