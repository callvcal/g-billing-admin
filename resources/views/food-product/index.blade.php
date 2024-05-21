<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/shop.css') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">



</head>

<body>

    @include('body.header')

    <section class="section food-menu" id="food-menu">
        <div class="container">



            <h2 class="h2 section-title">
                Our Delicious <span class="span">Foods</span>
            </h2>

            <p class="section-text">
                Food is any substance consumed to provide nutritional support for an organism.
            </p>
        </div>





        <div class="product-page">
            <aside class="category-sidebar">
                <h2 class="sidebar-title">Food Categories</h2>
                <ul class="category-list">
                    <li class="category-item">
                        <img class=" img-icon" src="{{ asset('photos/img/cta-banner.png') }}" alt="Image 1"> Pizza
                        <span class="product-count">5</span>
                    </li>
                    <li class="category-item">
                        <img class=" img-icon" src="{{ asset('photos/img/cta-banner.png') }}" alt="Image 1"> Biryani
                        <span class="product-count">5</span>
                    </li>
                    <li class="category-item">
                        <img class=" img-icon" src="{{ asset('photos/img/cta-banner.png') }}" alt="Image 1"></i>
                        Sandwich
                        <span class="product-count">5</span>
                    </li>
                    <li class="category-item">
                        <img class=" img-icon" src="{{ asset('photos/img/cta-banner.png') }}" alt="Image 1"> Noodles
                        <span class="product-count">5</span>
                    </li>
                    <li class="category-item">
                        <img class=" img-icon" src="{{ asset('photos/img/cta-banner.png') }}" alt="Image 1"> Ice Cream
                        <span class="product-count">5</span>
                    </li>

                    <li class="category-item">
                        <img class=" img-icon" src="{{ asset('photos/img/cta-banner.png') }}" alt="Image 1"> Desert
                        <span class="product-count">5</span>
                    </li>



                    <!-- Add more categories as needed -->
                </ul>
            </aside>



            <section class="food-category">
                <h2 class="category-title">Pizza</h2>

                <div class="category-pizza food-category">


                    <div class="product-list">
                        <div class="product-item">
                            <img src="{{ asset('photos/web_photos/1.png') }}" alt="Pizza">
                            <p class="product-amount">Single 2.5 Inch</p>
                            <h3 class="product-title">Margherita Pizza</h3>
                            <p class="product-subtitle">Classic Italian Pizza</p>
                            <div class="price-section">
                                <span class="original-price">₹20.00</span>
                                <span class="discounted-price">₹10.00</span>
                                <span class="offer-hint">20% off</span>
                            </div>
                            <div class="quantity-section">
                                <button class="quantity-btn minus-btn">-</button>
                                <input type="number" id="quantity" name="quantity" min="1" value="1">
                                <button class="quantity-btn plus-btn">+</button>
                            </div>
                            <button class="add-to-cart">Add to Cart</button>
                            <p class="empty-message" style="display: none;">No products available in this category</p>
                        </div>
                    </div>





                    <div class="product-list">
                        <div class="product-item">
                            <img src="{{ asset('photos/web_photos/1.png') }}" alt="Pizza">
                            <p class="product-amount">Single 2.5 Inch</p>
                            <h3 class="product-title">Margherita Pizza</h3>
                            <p class="product-subtitle">Classic Italian Pizza</p>
                            <div class="price-section">
                                <span class="original-price">₹20.00</span>
                                <span class="discounted-price">₹10.00</span>
                                <span class="offer-hint">20% off</span>
                            </div>
                            <div class="quantity-section">
                                <button class="quantity-btn minus-btn">-</button>
                                <input type="number" id="quantity" name="quantity" min="1" value="1">
                                <button class="quantity-btn plus-btn">+</button>
                            </div>
                            <div class="add-to-cart">Add to Cart</div>
                            <!-- <button class="add-to-cart">Add to Cart</button> -->
                            <p class="empty-message" style="display: none;">No products available in this category</p>
                        </div>
                    </div>
                </div>
            </section>






            <!-- Add more product items here -->
        </div>





        <!-- Product items as you provided in your original code -->
    </section>
    </div>





    <!-- Add more food categories and products as needed -->








    @include('body.footer')

    <!--
    - custom js link
  -->
    <script src="{{ asset('js/script.js') }}" defer></script>

    <!--
    - ionicon link
  -->

    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addToCartBtn = document.querySelector('.add-to-cart');
            const quantitySection = document.querySelector('.quantity-section');
            const minusBtn = document.querySelector('.minus-btn');
            const plusBtn = document.querySelector('.plus-btn');
            const quantityInput = document.getElementById('quantity');

            // Initially hide the quantity section
            quantitySection.style.display = 'none';

            // Show quantity section and hide 'Add to Cart' button when 'Add to Cart' is clicked
            addToCartBtn.addEventListener('click', function() {
                quantitySection.style.display = 'flex'; // or 'block', depending on your layout
                addToCartBtn.style.display = 'none';
            });

            // Handle minus button click
            minusBtn.addEventListener('click', function() {
                let currentValue = parseInt(quantityInput.value);
                if (currentValue > 1) {
                    quantityInput.value = currentValue - 1;
                } else {
                    quantitySection.style.display = 'none';
                    addToCartBtn.style.display = 'block';
                }
            });

            // Handle plus button click
            plusBtn.addEventListener('click', function() {
                let currentValue = parseInt(quantityInput.value);
                quantityInput.value = currentValue + 1;
            });

            // Update 'Add to Cart' visibility based on input change
            quantityInput.addEventListener('change', function() {
                if (parseInt(quantityInput.value) === 0) {
                    quantitySection.style.display = 'none';
                    addToCartBtn.style.display = 'block';
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const productItems = document.querySelectorAll('.product-item');

            // Loop through each product item
            productItems.forEach(product => {
                const originalPriceElem = product.querySelector('.original-price');
                const discountedPriceElem = product.querySelector('.discounted-price');
                const offerHintElem = product.querySelector('.offer-hint');
                const quantityInput = product.querySelector('.quantity-input');
                const addToCartBtn = product.querySelector('.add-to-cart');

                function calculateDiscountedPrice(originalPrice, discountPercentage) {
                    return originalPrice - (originalPrice * (discountPercentage / 100));
                }

                function updatePrices(originalPrice, discountPercentage) {
                    const discountedPrice = calculateDiscountedPrice(originalPrice, discountPercentage);
                    if (discountedPrice > 0) {
                        discountedPriceElem.textContent = `₹${discountedPrice.toFixed(2)}`;
                        offerHintElem.textContent = `${discountPercentage}% off`;
                    } else {
                        discountedPriceElem.textContent = '';
                        offerHintElem.textContent = '';
                    }
                }

                // Initial price update based on default values or setup
                const originalPrice = parseFloat(originalPriceElem.textContent.replace('₹', ''));
                const defaultDiscountPercentage = 20; // Change this to your default discount percentage
                updatePrices(originalPrice, defaultDiscountPercentage);

                // Update prices and offer hint on quantity change
                quantityInput.addEventListener('change', function() {
                    updatePrices(originalPrice, defaultDiscountPercentage);
                });

                // Add to Cart button functionality (customize according to your requirements)
                addToCartBtn.addEventListener('click', function() {
                    // Add logic to add the item to the cart
                    // You can use quantityInput.value to get the selected quantity
                    // Example: addToCart(product, quantityInput.value);




                    document.addEventListener('DOMContentLoaded', function() {
                        const categoryItems = document.querySelectorAll('.category-item');
                        const foodCategories = document.querySelectorAll('.food-category');

                        // Function to filter products based on selected category
                        function filterProducts(category) {
                            foodCategories.forEach(category => {
                                category.style.display = 'none';
                                const emptyMessage = category.querySelector(
                                    '.empty-message');
                                emptyMessage.style.display = 'none';
                            });

                            const selectedCategory = document.querySelector(
                                `.category-${category}`);
                            if (selectedCategory) {
                                selectedCategory.style.display = 'block';
                                const productsInCategory = selectedCategory
                                    .querySelectorAll('.product-item');
                                const emptyMessage = selectedCategory.querySelector(
                                    '.empty-message');

                                if (productsInCategory.length === 0) {
                                    emptyMessage.style.display = 'block';
                                } else {
                                    emptyMessage.style.display = 'none';
                                }
                            }
                        }

                        // Function to handle category click event
                        categoryItems.forEach(item => {
                            item.addEventListener('click', function() {
                                const category = this.textContent.trim()
                                    .toLowerCase();
                                filterProducts(category);
                            });
                        });

                        // Function to update sidebar categories while scrolling
                        function updateSidebarCategories() {
                            // Simulated logic for updating sidebar categories while scrolling
                            // Update the active category in the sidebar based on scroll position
                            // This part might depend on your layout and scrolling behavior
                        }

                        // Event listener for scrolling
                        window.addEventListener('scroll', updateSidebarCategories);

                        // Initial call to update sidebar categories
                        updateSidebarCategories();
                    });



                });
            });
        });
    </script>








</body>

</html>
