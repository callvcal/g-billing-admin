<!DOCTYPE html>
<html lang="en">

<head>
    <title>Namak POS Barcodes</title>
    <link rel="preload" href="https://fonts.googleapis.com/css?family=Libre+Barcode+39" as="style">
    <link href="https://fonts.googleapis.com/css?family=Libre+Barcode+39" rel="stylesheet">
    <style>
        @page {
            size: 78mm {{ $height }}mm;
            /* Set margins to 0 for thermal printers */
            margin: 0;
        }

        html,
        body {
            width: 78mm; /* Width of the sticker */
            height: auto; /* Adjust height as needed */
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
        }

        .container {
            border: 1px solid #ccc;
            padding: 0; /* Remove padding */
            margin: 0; /* Remove margin */
            box-sizing: border-box;
            text-align: center; /* Center-align all content */
        }

        .barcode {
            font-family: 'Libre Barcode 39', sans-serif;
            font-size: 42px; /* Adjust font size for the barcode */
            display: block;
            margin: 0;
        }

        .item-name {
            font-size: 14px; /* Adjust font size for the product name */
            font-weight: bold;
            text-transform: uppercase;
            display: block;
            margin-top: 4px; /* Space between barcode and product name */
        }

        .item-code {
            font-size: 18px; /* Font size for the product code above the barcode */
            display: block;
            margin-bottom: 4px; /* Space between the code and barcode */
        }
    </style>
</head>

<body>
    <div>
        @foreach ($menus as $menu)
            <div class="container">
                <p class="item-code">{{ $menu->code }}</p>
                <p class="barcode">{{ $menu->code }}</p>
                <span class="item-name">{{ $menu->name }}</span>
            </div>
        @endforeach
    </div>
</body>

</html>
