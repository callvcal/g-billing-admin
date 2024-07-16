<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorebarcodeRequest;
use App\Http\Requests\UpdatebarcodeRequest;
use App\Models\Menu;
use App\Models\MyBarcode;
use PhpParser\Node\Expr\Cast\String_;

class BarcodeController extends Controller
{
    function genBarcode(Menu $menu): bool
    {

        if (($menu) != null) {
            if (isset($menu->code)) {
                return false;
            }
        }


        $barcode = MyBarcode::create();
        $barcode->barcode_id = $barcode->id;
        $barcode->barcode = ''.$barcode->id;
        if (($menu) != null) {
            $barcode->menu_id = $menu->id ?? null;
            $menu->code = $barcode->barcode;
            $menu->save();
        }
        $barcode->save();

        return true;
    }
}
