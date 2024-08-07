<!DOCTYPE html>
<html lang="en">

<head>
    <title>Eatinsta POS Food Items Bill</title>
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
    <div>




        @foreach ($items as $item)
            <div class="container">
                @isset($setting['shop_name'])
                <div class="shop-name">{{$setting['shop_name']}}</div>
                @endisset
                <div class="address">Order: {{ $item->order_id }}</div>
                <hr>
                <div class="receipt-info">
                    <p class="lable">{{ $item->menu->food_type ?? '' }}</p>
                    <span class="item-name">{{ $item->menu->name }} - {{ $item->menu->unit->name ?? '' }} </span>
                </div>
                <hr>

              

            </div>
        @endforeach



    </div>
</body>

</html>
