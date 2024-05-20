<?php

namespace App\Http\Controllers;

use App\Admin\Forms\Setting;
use App\Models\Sell;
use App\Models\SellItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;
use Rawilk\Printing\Facades\Printing;

class PrintController extends Controller
{
    function billPrint($id)
    {


        $sell = Sell::with(['user', 'items.menu','diningTable'])->find($id);
        $items = $sell->items;
        $data = [
            "sell" => $sell,
            'setting'=>(new Setting())->data(),
            'items' => $items,
            'height'=> 172 + (count($items)*12)
        ];

        $view = view('templates.bill', $data)->render();
        return response()->json(['html' => $view]);

        $pdf = Pdf::loadView('templates.bill', $data);
        return $pdf->stream('bill.pdf');
       
        

        return;
        $setting = (new Setting())->data();
        $printer_port = $setting['printer_port'];
        $printer_network_address = $setting['printer_network_address'];




        $connector = new NetworkPrintConnector($printer_network_address, $printer_port);

        // Create a new Printer object
        $printer = new Printer($connector);

        // Set up some basic formatting (optional)
        $printer->initialize();
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->setTextSize(2, 2);


        // Print shop name and address
        $printer->text("Eatplan8\n");
        $printer->text("GAYATRI PLACE, WEST PATEL NAGAR, ADARSH, L.B.S NAGAR PHULWARI PATNA, BIHAR 800023 INDIA\n");
        $printer->text("+91 97211 84773\n");
        $printer->text("BILL RECEIPT\n\n");

        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("-----------------------------\n");
        $printer->text("#" . $sell->order_id . "  Dt. " . Carbon::parse($sell->date_time)->format('Y-m-d H:i:s') . "\n");
        $printer->text("Customer: " . $sell->user->name ?? '' . "\n");

        $printer->text("-----------------------------\n");

        // Print item list header
        $printer->text(sprintf("%-20s %-6s %-10s\n", "Item Name", "Qty", "Amount\n"));
        $printer->text("-----------------------------\n");

        // Print each item

        foreach ($items as $item) {
            $printer->text(sprintf("%-20s %-6s %-10s\n", $item->menu->name ?? '', $item->qty ?? '', $item->qty));
        }



        $printer->setJustification(Printer::JUSTIFY_RIGHT);

        // Print subtotal, GST, delivery fee, delivery tip, and total
        $printer->text("\nSubtotal: " . $sell->total_amt - $sell->gst_amt - $sell->delivery_charge . "\n");
        $printer->text("GST: " . $sell->gst_amt . "\n");
        $printer->text("Delivery Fee: " . $sell->delivery_charge . "\n");
        $printer->text("Delivery TIP: " . $sell->delivery_tip . "\n");
        $printer->text("-----------------------------\n");

        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("Total: " . $sell->total_amt . "\n\n");

        // Print thank you message
        $printer->text("Thank you for dining with us!\n");

        // Cut the paper (if supported by the printer)
        $printer->cut();

        // Close the printer connection
        $printer->close();
    }
    function kotPrint($id)
    {
        $sell = Sell::with(['user', 'items.menu'])->find($id);
        $items = $sell->items;
        $data = [
            "sell" => $sell,
            'items' => $items,
            'setting'=>(new Setting())->data(),
            'height'=>  (count($items)*50)
        ];
        $view = view('templates.kot', $data)->render();
        return response()->json(['html' => $view]);

        $pdf = Pdf::loadView('templates.kot', $data);
        return $pdf->stream('kot.pdf');

        $setting = (new Setting())->data();
        $printer_port = $setting['printer_port'];
        $printer_network_address = $setting['printer_network_address'];

        // $pdf = Pdf::loadView('templates.bill', $data);
        // return $pdf->stream('bill.pdf');
        foreach ($items as $item) {


            $connector = new NetworkPrintConnector($printer_network_address, $printer_port);

            // Create a new Printer object
            $printer = new Printer($connector);

            // Set up some basic formatting (optional)
            $printer->initialize();
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setTextSize(2, 2);


            // Print shop name and address
            $printer->text("Eatplan8\n");
            $printer->text("KOT\n\n");

            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("-----------------------------\n");
            $printer->text("#" . $sell->order_id . "  Dt. " . Carbon::parse($sell->date_time)->format('Y-m-d H:i:s') . "\n");

            $printer->text("-----------------------------\n");

            // Print item list header
            $printer->text(sprintf("%-20s %-6s %-10s\n", "Item Name", "Qty", "Amount\n"));
            $printer->text("-----------------------------\n");

            // Print each item

            $printer->text(sprintf("%-20s %-6s %-10s\n", $item->menu->name ?? '', $item->qty ?? '', $item->qty));


            // Cut the paper (if supported by the printer)
            $printer->cut();

            // Close the printer connection
            $printer->close();
        }
    }

    function stickersPrint($id)
    {


        $sell = Sell::with(['items.menu.unit'])->find($id);
        $items = $sell->items;
        $data = [
            "sell" => $sell,
            'items' => $items,
            'setting'=>(new Setting())->data(),
            'height'=> (count($items)*48)
        ];
        $view = view('templates.sticker', $data)->render();
        return response()->json(['html' => $view]);

        $pdf = Pdf::loadView('templates.sticker', $data);
        return $pdf->stream('sticker.pdf');
    }
}
