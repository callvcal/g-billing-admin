<style>
    .cart-item {
        padding: 10px;
        margin-bottom: 5px;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2)
    }

    .searchInput {
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        /* Add elevation effect */
        width: 300px;
        margin: 10px;
    }

    .float-top {
        position: float;
        top: 100px;
        width: 100%;
        left: 400px;
        padding: 10px;
        background-color: #f0f0f0;
    }

    .float-bottom {
        position: fixed;
        bottom: 0;
        width: calc(100% - 300px);
        /* Adjusted width to account for the 300px sidebar */
        left: 300px;
        /* Width of the sidebar */
        padding: 10px;
        background-color: #f0f0f0;
        z-index: 1000;
        /* Ensure it's above the sidebar */
    }


    .din-table {
        align-items: center;
    }

    .cart-item-name {
        padding: 2px 2px;
    }

    .button {
        margin: 2px 2px;
    }

    .running {
        margin: 2px 2px;
    }

    .qty {
        padding: 2px 2px;
        text-align: center
    }

    .subcategory-selected {
        background-color: #6a0dad;
        color: white;
    }

    /* Custom styling for menu items */
    .menu-item {
        flex-direction: column;
        align-items: center;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2) padding: 10px;
        margin-bottom: 10px;
        width: 111px;
        height: 151px;
    }

    .decrease-button {
        align-items: center;
        width: 32px;
        height: 32px;
        size: 32px;
        color: red;
    }

    .increase-button {
        align-items: center;
        width: 32px;
        size: 32px;
        height: 32px;
        color: green;
    }

    /* Custom styling for subcategory buttons */
    .subcategory-button {
        width: 100%;
        padding: 5px;
        margin-bottom: 5px;
        text-align: center;
    }
</style>

<form id="pos_form" class="container needs-validation" action="/admin/placeOrder" method="POST" novalidate>
    <div class=" float-top">
        <div>
            <div class="row">
                <div class="col-2 din-table">

                    <label for="serve_type">Order Type</label>
                    <select class="form-select" name="serve_type" id="serve_type">
                        <option id="dine-in" value="dine-in">
                            Dine In</option>
                        <option id="take-away" value="take-away">
                            Parcel</option>
                    </select>
                </div>
                <div class="col-2 din-table" id="select_dining_table_id">

                    <label for="dining_table_id">Table</label>
                    <select class="form-select" name="dining_table_id" id="dining_table_id">
                        @foreach ($tables as $item)
                            @if ($item->status == 'blank' || $item->status == null || $item->id == $sell->dining_table_id)
                                <option id="dining_table_id_option_{{ $item->id }}" value="{{ $item->id }}">
                                    Table {{ $item->number }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-2 din-table">

                    <label for="payment_method">Method</label>
                    <select class="form-select" name="payment_method" id="payment_method">
                        <option value="" disabled>Payment Method</option>
                        <option value="cash" selected>Cash</option>
                        <option value="upi">UPI</option>
                        <option value="card">Card</option>
                        <option value="due">Due</option>
                    </select>

                </div>
                <div class="col-2 din-table">

                    <label for="payment_status">Payment</label>
                    <select class="form-select" name="payment_status" id="payment_status">
                        <option value="" disabled>Payment Status</option>
                        <option value="pending">Due</option>
                        <option value="paid" selected>Paid</option>
                    </select>

                </div>
                @csrf

                <input type="hidden" name="items" id="items">
                <input type="hidden" name="sell_type" id="sell_type" value="counter">
                <input type="hidden" name="uuid" id="uuid" value="{{ $sell->uuid }}">
                <input type="hidden" name="discount_amt" id="discount_amt" value="0">
                <input type="hidden" name="remark" id="remark" value="Order placed from admin panel">
                <input type="hidden" name="total_amt" id="total_amt" value="0">
                <input type="hidden" name="id" id="id" value="{{ $sell->id }}">
                <input type="hidden" name="pos_action" id="pos_action" value="KOT">


                <div class="col-2 din-table">
                    <label for="customer_name">Cust. name</label>
                    <input type="text" class="form-control" name="customer_name" id="customer_name" />
                </div>
                <div class="col-2 din-table">
                    <label for="customer_mobile">Cust. mobile</label>
                    <input type="text" class="form-control" name="customer_mobile" id="customer_mobile" />
                </div>





            </div>
            <hr>



            <input type="text" class="searchInput" id="searchInput" placeholder="Search for items...">
            <hr>

            <div class="row">
                <div class="col-1">
                    <span>Running Tables</span>

                    <div class="row" id="running_container">
                        @foreach ($tables as $item)
                            @if ($item->status == 'running')
                                <div id="running_container_{{ $item->id }}" class="btn btn-outline-info running"
                                    onclick="openTable({{ $item->id }})">
                                    <span>Table {{ $item->number }}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>


                </div>
                <div class="col-2" id="subcategoryContainer">
                    <!-- Render subcategories -->

                </div>

                <div class="col" id="menuContainer">
                    <!-- Menus will be displayed here -->
                </div>

                <div class="col-3" id="cartContainer">
                    <!-- Cart list will be displayed here -->
                </div>
            </div>
            <div class="float-bottom row align-items-center justify-content-between">
                <div class="col-3">
                    <label for="discount" class="form-label" style="font-size: 0.9em;">Discount (%)</label>
                    <input type="number" class="form-control form-control-sm" name="discount" id="discount" />
                </div>

                <div class="col-2">
                    <span class="text-muted" style="font-size: 0.9em;"><b>Items:</b> <span
                            id="carts-count">0</span></span>
                </div>

                <div class="col-2">
                    <span class="text-muted" style="font-size: 0.9em;"><b>Total:</b> Rs <span
                            id="total">0</span></span>
                </div>

                <div class="col-5 text-end">


                    <button class="btn btn-sm btn-info mx-1" type="button" onclick="submitForm('KOT')">
                        <i class="fas fa-list-alt"></i> KOT
                    </button>
                    <button class="btn btn-sm btn-primary mx-1" type="button" onclick="submitForm('BILL')">
                        <i class="fas fa-file-invoice"></i> BILL & Print
                    </button>
                    <button class="btn btn-sm btn-primary mx-1" type="button" onclick="submitForm('SAVE')">
                        <i class="fas fa-save"></i> Save
                    </button>
                    <button class="btn btn-sm btn-secondary mx-1" type="button" onclick="submitForm('CANCEL')">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                </div>
            </div>


</form>

<script type="text/javascript" data-navigate-once defer>

    console.log('localStorage:'+localStorage.getItem('pageReloaded'));
    
    
    var selectedSubcategoryId = null;
    let searchInput = document.getElementById('searchInput');


    searchInput.addEventListener('input', filterMenus);

    function filterMenus() {
        let searchTerm = searchInput.value.toLowerCase().trim(); // Convert search term to lowercase and trim whitespace
        console.log(searchTerm);
        showMenus(null, searchTerm);
    }

    // Initialize variables with default values if PHP variables are not set
    let discountAmt = {{ $sell->discount_amt ?? 1 }};
    let totalAmt = {{ $sell->total_amt ?? 100 }};

    // Calculate discount based on discountAmt and totalAmt
    let discount = (discountAmt == 0) ? 0 : 100 - (totalAmt / discountAmt);

    // Retrieve DOM elements
    let customerNameElement = document.getElementById('customer_name');
    let customerMobileElement = document.getElementById('customer_mobile');
    let paymentMethodElement = document.getElementById('payment_method');
    let paymentStatusElement = document.getElementById('payment_status');
    let diningTableElement = document.getElementById('dining_table_id');

    // Check if DOM elements exist before setting their values
    {
        customerNameElement.value = "{{ $sell->customer_name }}";
    } {
        customerMobileElement.value = "{{ $sell->customer_mobile }}";
    }
    if ("{{ $sell->payment_method }}") {
        Array.from(paymentMethodElement.options).forEach(option => {
            if (option.value === "{{ $sell->payment_method }}") {
                option.selected = true;
            }
        });
    }
    if ("{{ $sell->payment_status }}") {
        Array.from(paymentStatusElement.options).forEach(option => {
            if (option.value === "{{ $sell->payment_status }}") {
                option.selected = true;
            }
        });
    }
    console.log("dining_table: " + "{{ $sell->dining_table_id }}");
    // Check if $sell->dining_table_id is not null or empty
    @if (!empty($sell->dining_table_id))
        // Execute JavaScript only if $sell->dining_table_id has a value
        var diningTableId = "{{ $sell->dining_table_id }}";

        // Check if diningTableElement exists and it's a select element
        if (diningTableElement && diningTableElement.tagName === 'SELECT') {
            // Loop through options to find and select the desired option
            Array.from(diningTableElement.options).forEach(option => {
                if (option.value === diningTableId) {
                    option.selected = true;
                }
            });
        }
    @endif


    var orderItems = {!! json_encode($items) !!};
    console.log(orderItems);

    var cart = {}; // Initialize the cart object

    for (var i = 0; i < orderItems.length; i++) {
        var item = orderItems[i];
        cart[item.menu_id] = {
            qty: item.qty,
            id: item.id,
            total_amt: item.total_amt,
            menu_id: item.menu_id,
            menu: item.menu
        };
    }
    const subcategories = {!! json_encode($subcategories) !!};

    console.log(cart);
    if (Array.isArray(subcategories) && subcategories.length > 0) {
        selectedSubcategoryId = subcategories[0].id;
    }

    {{--  renderSubcategories();  --}}
    showMenus(selectedSubcategoryId, '');


    renderCart();
    update();



    var myInput = document.getElementById('discount');

    // Add a change event listener to the input field
    myInput.addEventListener('input', function(event) {
        discount = event.target.value;
        console.log(event.target.value);
        update();
    });


    var serveType = document.getElementById('serve_type');
    var diningTable = document.getElementById('select_dining_table_id');
    var diningTableID = document.getElementById('dining_table_id');

    // Add a change event listener to the input field
    serveType.addEventListener('change', function(event) {
        console.log(event.target.value);
        if (event.target.value == 'take-away') {
            // Clear the selected option in the diningTable select
            diningTableID.selectedIndex = -1;

            // Hide the diningTable and show the serveType
            diningTable.style.display = 'none';
            serveType.style.display = 'block';
        } else if (event.target.value == 'dine-in') {
            // Show the diningTable and hide the serveType
            diningTable.style.display = 'block';
        }
    });


    function openTable(id) {
        window.location.href = "/admin/pos?dining_table_id=" + id;
    }

    function kot(selectedValue, text) {
        console.log("KOT: " + "Table Value: " + selectedValue + " Table Text: " + text);

        // Remove the option from the dropdown if it exists
        var dropdown = document.getElementById('dining_table_id');
        var optionToRemove = document.getElementById('dining_table_id_option_' + selectedValue);
        if (optionToRemove) {
            optionToRemove.remove();
        }

        // Create a new button
        var newButton = document.createElement('div');
        newButton.className = 'btn btn-outline-info running';
        newButton.setAttribute('id', 'running_container_' + selectedValue);
        newButton.textContent = 'Table ' + text;

        // Add an onclick event to the new button
        newButton.onclick = function() {
            openTable(selectedValue); // Use selectedValue instead of value
        };

        // Get the running tables container
        var runningTablesContainer = document.getElementById('running_container');

        // Check if a button with the same ID already exists
        var existingButton = document.getElementById('running_container_' + selectedValue);
        if (existingButton) {
            runningTablesContainer.removeChild(existingButton); // Remove the existing button
        }

        // Append the new button to the container
        runningTablesContainer.appendChild(newButton);
    }


    function bill(selectedValue, text) { // Changed value to selectedValue
        var dropdown = document.getElementById('dining_table_id');
        var newOption = document.createElement('option');
        newOption.value = selectedValue; // Changed value to selectedValue
        newOption.setAttribute('id', 'dining_table_id_option_' + selectedValue);

        newOption.textContent = 'Table ' + text;
        dropdown.appendChild(newOption);


        var runningTablesContainer = document.getElementById('running_container');
        var tableToRemove = document.getElementById('running_container_' +
            selectedValue); // Changed value to selectedValue
        if (tableToRemove) {
            tableToRemove.remove();
        }
    }

    function updateTables(action) {
        let diningTableElement = document.getElementById('dining_table_id');

        if (diningTableElement.value) {
            var tables = {!! json_encode($tables) !!};
            console.log(tables); // Log the tables array to the console
            var table = tables.find(function(item) {
                return parseInt(item.id) === parseInt(diningTableElement.value, 0);
            });

            if (table) { // Check if a table with the selected ID was found
                if (action == "BILL") {
                    bill(diningTableElement.value, table.number);
                } else if (action == "KOT") {
                    kot(diningTableElement.value, table.number);
                }
            } else {
                console.error('Table not found for ID:', diningTableElement.value);
            }
        }

        var id = document.getElementById('id');
        id.value = null;
    }

    function submitForm(action) {

        if (action == "CANCEL") {
            window.location.href = '/admin/pos';
            return;
        }


        // Get the form and input elements
        var form = document.getElementById('pos_form');
        var input1 = document.getElementById('pos_action');






        // Set values of input fields based on the action
        input1.value = action;
        if (action == "SAVE") {
            form.submit();

            return;
        }
        // Create a FormData object to send with fetch
        var formData = new FormData(form);

        // Submit the form using fetch
        fetch(form.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Handle response data
                console.log(data);

                form.reset();
                discount = 0;
                cart = {};
                update();
                renderCart();

                updateTables(action);
                // Show receipt modal and print
                showModal(data);
            })
            .catch(error => console.log(error));

        // Prevent default form submission
        event.preventDefault();
    }

    if (!Array.prototype.clear) {
        Array.prototype.clear = function() {
            this.length = 0;
        };
    }



    function showModal(data) {
        // Update modal content with HTML from response
        let newWindow = window.open('', '_blank');
        newWindow.document.write(data.html);
        newWindow.document.close();

        // Wait for the new window to finish loading before printing
        newWindow.onload = function() {
            // Print the contents of the new window
            newWindow.print();

            // Close the new window (optional)
            newWindow.close();
        };
    }

    function printReceipt() {
        // JavaScript code for printing
        window.print();

        // Close modal after printing (optional)
        $('#receiptModal').modal('hide');
    }


    function update() {
        var total = 0;
        var totalSpan = document.getElementById('total');
        var totalInput = document.getElementById('total_amt');
        var discount_amt = document.getElementById('discount_amt');

        for (var menuId in cart) {
            var cartItem = cart[menuId];
            total += cartItem.total_amt;
        }
        totalInput.value = Math.round(total * (1 - discount / 100));
        totalSpan.textContent = Math.round(total * (1 - discount / 100));
        discount_amt.value = Math.round(total * (discount / 100));
        var cartDataInput = document.getElementById('items');
        cartDataInput.value = JSON.stringify(cart);

    }


    function showMenus(subcategoryId, query) {

    if (subcategoryId != null) {
            selectedSubcategoryId = subcategoryId;
        }

        var menuContainer = document.getElementById('menuContainer');
        menuContainer.innerHTML = ''; // Clear existing menus

        // Render menus for the selected subcategory
        @foreach ($menus as $menu)


            if (query == '') {

                if ((subcategoryId+"") === "{{ $menu->subcategory_id }}") {
                    var menuItem = `
                        <div class="menu-item btn btn-outline-primary m-1" onclick="addToCart({{ $menu->id }})">
                            <img class="card-img-top" src="{{ Storage::url($menu->image??'') }}" alt="" style="width: 64px; height:64px">
                            <p class="card-text" style="font-size: 0.8em"> {{ $menu->name }}</p>
                            <p class="card-text" style="font-size: 0.9em"> Rs. {{ $menu->price }}</p>
                           
                        </div>
                    `;
                    menuContainer.insertAdjacentHTML('beforeend', menuItem);
                }

            } else if ("{{ $menu->name }}".toLowerCase().includes(query)||("{{ $menu->code }}".toLowerCase().includes(query))) {
                var menuItem = `
                    <div class="menu-item btn btn-outline-primary m-1" onclick="addToCart({{ $menu->id }})">
                        <img class="card-img-top" src="{{ Storage::url($menu->image??'') }}" alt="" style="width: 64px; height:64px">
                        <p class="card-text" style="font-size: 0.8em"> {{ $menu->name }}</p>
                        <p class="card-text" style="font-size: 0.9em"> Rs. {{ $menu->price }}</p>
                       
                    </div>
                `;
                menuContainer.insertAdjacentHTML('beforeend', menuItem);


            }
        @endforeach
        //var selectedButton = document.getElementById('subcategory-' + subcategoryId);
        //selectedButton.classList.add('subcategory-selected');
        renderSubcategories();

    }

    function renderSubcategories() {
        var scatContainer = document.getElementById('subcategoryContainer');
        scatContainer.innerHTML = ''; // Clear existing menus



        // Render menus for the selected subcategory
        @foreach ($subcategories as $subcategory)
            var menuItem = `
                <div id="subcategory-{{ $subcategory->id }}" class="btn btn-outline-primary m-1" style="width: 100%" onclick="showMenus({{ $subcategory->id }},'')">
                   
                    <div style="font-size:0.8em">
                        {{ $subcategory->name }}
                    </div>
                </div>
        `;



            scatContainer.insertAdjacentHTML('beforeend', menuItem);
            if (selectedSubcategoryId == {{ $subcategory->id }}) {
                var selectedButton = document.getElementById('subcategory-' + selectedSubcategoryId);
                selectedButton.classList.add('subcategory-selected');
            }
        @endforeach


    }


    function addToCart(menuId) {
        var menus = {!! json_encode($menus) !!};
        var menu = menus.find(function(item) {
            return item.id === menuId;
        });

        if (cart[menuId]) {
            cart[menuId].qty += 1;
            cart[menuId].total_amt = menu.price * cart[menuId].qty;
        } else {
            cart[menuId] = {
                qty: 1,
                total_amt: menu.price,
                menu_id: menu.id,
                menu: menu
            };
        }

        renderCart();
    }

    function renderCart() {
        var cartContainer = document.getElementById('cartContainer');

        // Clear the cart container before rendering
        cartContainer.innerHTML = '';

        // Initialize total and cart count
        var total = 0;
        var cartCount = 0;

        // Display items in the cart
        for (var menuId in cart) {
            var cartItem = cart[menuId];

            // Create container for cart item
            var cartItemDiv = document.createElement('div');
            cartItemDiv.className = 'cart-item row';

            // Display menu name
            var menuName = document.createElement('span');
            menuName.className = 'cart-item-name col-6';
            menuName.textContent = cartItem.menu.name;
            cartItemDiv.appendChild(menuName);

            // Display quantity
            var quantityText = document.createElement('span');
            quantityText.className = 'qty col-2';
            quantityText.textContent = cartItem.qty;
            cartItemDiv.appendChild(quantityText);

            // Create button for increasing quantity
            var increaseButton = document.createElement('i');
            increaseButton.className = 'increase-button icon-plus-circle btn col-2';
            increaseButton.onclick = (function(item) {
                return function() {
                    item.qty++;
                    item.total_amt = item.qty * item.menu.price;
                    renderCart();
                };
            })(cartItem); // Capture cartItem in closure
            cartItemDiv.appendChild(increaseButton);

            // Create button for decreasing quantity
            var decreaseButton = document.createElement('i');
            decreaseButton.className = 'decrease-button icon-minus-circle btn col-2';
            decreaseButton.onclick = (function(item) {
                return function() {
                    if (item.qty > 1) {
                        item.qty--;
                        item.total_amt = item.qty * item.menu.price;
                        renderCart();
                    } else {
                        delete cart[item.menu_id]; // Remove item from cart
                        renderCart();
                    }
                };
            })(cartItem); // Capture cartItem in closure
            cartItemDiv.appendChild(decreaseButton);

            cartContainer.appendChild(cartItemDiv);

            // Update total and cart count
            total += cartItem.total_amt;
            cartCount += cartItem.qty;
        }

        // Update total and cart count displayed on the page
        var cartsCountSpan = document.getElementById('carts-count');

        cartsCountSpan.textContent = cartCount;
        update();
    }
</script>
