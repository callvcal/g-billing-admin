<header class="header" data-header>
    <div class="container">

        <h1>
            <a href="/" class="logo">EatPlane8<span class="span"></span></a>
        </h1>

        <nav class="navbar" data-navbar>
            <ul class="navbar-list">
                <div class="Product-bg">
                    <li class="nav-item">
                        <a href="{{ route('shop.index') }}" class="navbar-link" style="color: white;" data-nav-link>
                            Products
                        </a>
                    </li>
                </div>

                <div class="about-bg">
                    <li class="nav-item">

                        <a href="/about" class="navbar-link " style="color: white;" data-nav-link>
                            About Us
                        </a>
                    </li>
                </div>
            </ul>



        </nav>




        <div class="profile">
            <!-- <button class="profile-btn"> -->
            <!-- Replace with profile image using Laravel path -->
            <img class="img-icon" src="{{ asset('photos/img/sonu.png') }}" alt="Profile Image" OnClick="toggleMenu()">
            <!-- </button> -->

            <div class="profile-wrapper" id="subMenu">
                <div class="open-menu">
                    <div class="profile-info">
                        <!-- Replace with profile image using Laravel path -->
                        <img class="img-icon" src="{{ asset('photos/img/sonu.png') }}" alt="Profile Image">
                        <!-- Replace with user's name -->
                        <p class="profile-name">Sonu Sah</p>
                    </div>
                    <ul class="dropdown-list">

                        <li class="dropdown-item">
                            <!-- Replace icons with image and add Laravel path -->
                            <a href="#">
                                <img class="img-icon" src="{{ asset('photos/webicon/order.png') }}"
                                    alt="Orders Icon">Order
                                <!-- <p class="drop-item-name"> Orders</p> -->
                            </a>
                        </li>
                        <li class="dropdown-item">
                            <!-- Replace icons with image and add Laravel path -->
                            <a href="#">
                                <img class="img-icon" src="{{ asset('photos/webicon/wishlist.png') }}"
                                    alt="wishlist Icon">
                                wishlist
                            </a>
                        </li>
                        <li class="dropdown-item">
                            <!-- Replace icons with image and add Laravel path -->
                            <a href="#">
                                <img class="img-icon" src="{{ asset('photos/webicon/address.png') }}"
                                    alt="Address Icon">
                                Address
                            </a>
                        </li>

                        <li class="dropdown-item">
                            <!-- Replace icons with image and add Laravel path -->
                            <a href="#">
                                <img class="img-icon" src="{{ asset('photos/webicon/refer.png') }}" alt="referal Icon">
                                refferal
                            </a>
                        </li>


                        <li class="dropdown-item">
                            <!-- Replace icons with image and add Laravel path -->
                            <a href="#">
                                <img class="img-icon" src="{{ asset('photos/webicon/account.png') }}"
                                    alt="Account Setting Icon">
                                Account Setting
                            </a>
                        </li>

                        <li class="dropdown-item">
                            <!-- Replace icons with image and add Laravel path -->
                            <a href="#">
                                <img class="img-icon" src="{{ asset('photos/webicon/logout.png') }}" alt="logout Icon">
                                logout
                            </a>
                        </li>
                        <!-- ...Repeat for other dropdown items... -->
                    </ul>
                </div>
            </div>
        </div>







        <a class="btn btn-light" href="{{route('login')}}">Login</a>


        <!-- Login Popup -->
        <div class="popup" id="loginPopup">
            <div class="popup-content">
                <span class="close-btn" id="closeBtn">&times;</span>
                <h2>Login</h2>
                <form action="#" method="post">
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit" class="login-submit-btn">Login</button>
                    <div class="or-divider">OR</div>
                    <button class="btn btn-hover" id="signupBtn">Sign Up</button>
                    <button type="button" class="google-login-btn">Login with Google</button>
                    <button type="button" class="mobile-otp-btn">Login with Mobile OTP</button>
                </form>
            </div>
        </div>

        <!-- Signup Popup -->
        <div class="popup" id="signupPopup">
            <div class="popup-content">
                <span class="close-btn" id="closeBtnSignup">&times;</span>
                <h2>Sign Up</h2>
                <form action="#" method="post">
                    <input type="text" name="name" placeholder="Name" required>
                    <input type="tel" name="mobile" placeholder="Mobile Number" required>
                    <select name="city" required>
                        <option value="" disabled selected>Select City</option>
                        <option value="city1">Patna</option>
                        <option value="city2">Dharbhanga</option>
                        <!-- Add more city options as needed -->
                    </select>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <input type="password" name="confirmPassword" placeholder="Confirm Password" required>
                    <button type="submit" class="signup-submit-btn">Sign Up</button>
                </form>
            </div>
        </div>




        <button class="nav-toggle-btn" aria-label="Toggle Menu" data-menu-toggle-btn>
            <span class="line top"></span>
            <span class="line middle"></span>
            <span class="line bottom"></span>
        </button>
    </div>

    </div>
</header>





<script>
    let subMenu = document.getElementById('subMenu');

    function toggleMenu() {

        subMenu.classList.toggle('open-menu');

    }

</script>
