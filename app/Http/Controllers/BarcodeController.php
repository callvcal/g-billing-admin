<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorebarcodeRequest;
use App\Http\Requests\UpdatebarcodeRequest;
use App\Models\Menu;
use App\Models\MyBarcode;
use Illuminate\Http\Request;

use PhpParser\Node\Expr\Cast\String_;

class BarcodeController extends Controller
{

    function genBarcode(Menu $menu): bool
    {
        // Check if the menu already has a barcode code
        if ($menu != null && isset($menu->code)) {
            return false;
        }

        // Generate a barcode using the next auto-increment ID from the MyBarcode table
        $nextId = MyBarcode::max('id') + 1; // Get the next ID (assuming 'barcode_id' is auto-incrementing)

        // Ensure the barcode has a minimum of 10 digits, padding with zeros if necessary
        $barcode = str_pad($nextId, 10, '0', STR_PAD_LEFT);

        if ($menu != null) {
            // Assign the generated barcode to the menu
            $menu->code = $barcode;
            $menu->save();
        }

        // Save the barcode information (assuming MyBarcode is a model for storing barcodes)
        MyBarcode::create([
            'barcode_id' => $nextId,
            'barcode' => $barcode,
            'menu_id' => $menu->id ?? null,
        ]);

        return true;
    }


    public function printBarcodes(Request $request)
    {
        // Get the IDs from the request
        $ids = $request->input('ids');

        // Validate that 'ids' is an array
        if (!is_array($ids)) {
            return response()->json(['status' => 400, 'message' => 'Invalid request: ids must be an array'], 400);
        }

        // Find all menus with the provided IDs
        $menus = Menu::whereIn('id', $ids)->get();

        if ($menus->isEmpty()) {
            return response()->json(['status' => 404, 'message' => 'No menus found for the provided IDs'], 404);
        }
        $data = [
            'menus' => $menus,
            'height' => 172 + (count($menus) * 12)
        ];
        // Render the view with the data
        $barcodeView = view('templates.barcodes', $data)->render();
        return response()->json(['status' => 200, 'html' => $barcodeView]);
    }
}
