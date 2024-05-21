<!DOCTYPE html>
<html lang="en">

<head>
    <title>Eatplan8 POS Food Items Bill</title>
    <style>
        @page {
            size: 78mm {{ $height }}mm;
            /* Width: 88mm, Height: 125mm */
            margin-top: 0;
            margin-bottom: 0;
            margin-left: 0;
            margin-right: 0;

            @top-left, @top-center, @top-right {
                content: none;
            }

            @bottom-left, @bottom-center, @bottom-right {
                content: none;
            }
        }

        html,
        body {
            width: 78mm;
            margin: 0;
            height: auto;
            padding: 8px;

        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;

        }

        .container {
            border: 1px solid #ccc;
            padding: 8px;
            margin: 8px;
        }

        .logo {
            text-align: center;
        }

        .shop-name {
            text-align: center;
            font-size: 25px;
            font-weight: bold;
            margin-top: 0px;
        }

        .address {
            text-align: center;
            font-size: 14px;
            text-transform: uppercase;
            margin-top: 0px;
        }

        .lable {
            text-align: center;
            font-size: 14px;
            text-transform: uppercase;
        }

        .receipt-info {
            text-align: left;
            font-size: 14px;
        }

        .item-list {
            margin-top: 0px;
        }

        .item {
            margin-bottom: 0px;
        }

        .item-name {
            text-align: left;
            padding: 4%;
            font-size: 0.9em;
            font-weight: 600;
        }

        .item-qty,
        .item-amount {
            width: 20mm;
            font-size: 0.91em;
            font-weight: 500;
            padding: 4%;
            text-align: right;
        }

        .total {
            margin-top: 0px;
            font-size: 16px;
            font-weight: 600;
        }

        .thank-message {
            margin-top: 0px;
            text-align: center;
            background-color: #e6e6e6;
            padding: 0px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .icon {
            font-size: 24px;
            margin-right: 0px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div>


            @php

                $isIncluded = $setting['is_gst_included'] == 1;
                if( $isIncluded){
                    $subtotal = $sell->total_amt - $sell->gst_amt - $sell->delivery_charge + $sell->discount_amt;

                }else{
                    $subtotal = $sell->total_amt - $sell->gst_amt - $sell->delivery_charge + $sell->discount_amt;
                }

            @endphp


            <div class="shop-name">{{ $setting['shop_name'] }}</div>
            <div class="address">{{ $setting['address'] }}<br>
                {{ $setting['mobile'] }} <br>
                <p>Invoice</p>

                <p>{{ $sell->serve_type }}</p>

            </div>
            <hr>
        </div>
        <div class="receipt-info">
            <p>Customer: {{ $sell->customer_name ?? ($sell->user->name ?? '') }}</p>
            <p>Phone: {{ $sell->customer_mobile ?? ($sell->user->name ?? '') }}</p>

            @isset($sell->address)
                <p>Address: {{ $sell->address->address ?? '' }}</p>
            @endisset

            <p>Bill No : {{ $sell->invoice_id }}</p>
            <p>Table No : {{ $sell->diningTable->number ?? '' }}</p>
            <p>ID: {{ $sell->order_id }}</p>
            <p>Date Time: {{ $sell->date_time }}</p>
            <p>Payment : {{ $sell->payment_method }}</p>
        </div>
        <hr>

        <div class="item-list">

            @foreach ($items as $item)
                <span class="item-name"> {{ $item->menu->name }}</span>
                <div class="item">
                    <span class="item-qty"> {{ $item->qty }}</span>
                    <span class="item-amount">Rs. {{ $item->total_amt }}</span>
                </div>
            @endforeach
        </div>
        <hr>

        <div class="total">
            <div>
                <span>Subtotal :</span>
                <span>Rs. {{ $subtotal }}</span>
            </div>

            @if ($sell->gst_amt > 0)
                <div>
                    <span>GST ({{ $setting['gst_rate'] }}%) :</span>
                    <span>Rs. {{ $sell->gst_amt }}</span>
                </div>
            @endif


            @if (($sell->delivery_charge ?? 0) > 0)
                <div>
                    <span>Delivery Fee :</span>
                    <span>Rs. {{ $sell->delivery_charge }}</span>
                </div>
            @endif


            @if (($sell->delivery_tip ?? 0) > 0)
                <div>
                    <span>Delivery TIP :</span>
                    <span>Rs. {{ $sell->delivery_tip }}</span>
                </div>
            @endif
            <div>
                <span>Discount :</span>
                <span>Rs. {{ $sell->discount_amt }}</span>
            </div>
            <hr>
            <div>
                <span>Total :</span>
                <span>Rs. {{ $sell->total_amt }}</span>
            </div>
        </div>
        <hr>

        <div class="thank-message">
            <i class="fas fa-star text-warning icon"></i> {{ $setting['footer_message'] }} <i
                class="fas fa-star text-warning icon"></i>
        </div>


    </div>
    </div>
</body>

</html>
