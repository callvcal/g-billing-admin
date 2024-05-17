<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\CartItem;
use App\Models\DiningTable;
use App\Models\OrderStatusUpdate;
use App\Models\Sell;
use App\Models\SellItem;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function toggleCart(Request $request)
    {
        $item = $request->item;

        if (isset($request->id) && ($request->id != null)) {
            // Delete item from cart based on ID
            $cart = CartItem::with('menu.unit')->find($request->id);

            if ($cart) {
                if (($cart->qty == 1) && ($request->change == -1)) {
                    $cart->delete();
                    return response(['message' => 'Item deleted successfully'], 201);
                }

                $cart->qty = $cart->qty + $request->change;
                $cart->total_amt = $request->total_amt;
                $cart->save();

                return response($cart, 200);
            }
        }

        // Create a new cart item
        $cart = CartItem::create([
            'date_time' => ($item['date_time']) ?? Carbon::now(),
            'total_amt' => $request->total_amt,
            'qty' => 1,
            'dining_table_id' => $request->dining_table_id,
            'menu_id' => $item['id'],
            'user_id' => auth()->user()->id,
        ]);

        $cart->load(['menu']);
        return response($cart);
    }

    function updateAddress(Request $request)
    {
        // $request->merge(['user_id' => auth()->user()->id]);
        $address = $request->all();
        $address['user_id'] = auth()->user()->id;
        if (isset($request->id)) {
            Address::find($request->id)->update($request->all());
        } else {
            $address = Address::create($address);
        }
        return response($address);
    }


    function deleteAddress($id)
    {
        return response(Address::find($id)->delete());
    }
    public function generateOtp()
    {
        $otp = rand(100000, 999999);
        return $otp;
    }

    public  function bulkCartsUpdate(Request $request)
    {
        $items = $request->items;
        foreach ($items as $item) {
            CartItem::where('id', $item['id'])->update($item);
        }
        CartItem::where('total_amt', 0)->delete();

        return response([
            'message' => 'updated'
        ]);
    }

    public function placeOrder(Request $request)
    {
        $user = auth()->user();
        $user_id = $user->id;

        $orderData = $request->sell;
        $orderData['date_time'] = ($orderData['date_time']) ?? Carbon::now();
        $orderData['user_id'] = $user_id;
        $orderData['delivery_status'] = "a_unassigned";
        $orderData['payment_status'] = "pending";
        $orderData['customer_name'] = $user->name;

        if (isset($orderData['address_id']) && !isset($orderData['full_address'])) {
            $address = Address::find($orderData['address_id']);
            if ($address) {
                $orderData['full_address'] = $address->address;
            }
        }

        $orderData['customer_mobile'] = $user->mobile;
        $orderData['order_status'] = ($orderData['payment_method'] == 'cash') ? "a_sent" : 'unknown';
        $orderData['invoice_id'] = (new KotTokenController())->generateToken(type: 'invoice');
        // $orderData['gst_amt'] = $orderData['total_amt'] * 0.05;
        $orderData['total_amt'] = (int) $orderData['total_amt'];
        $orderData['delivery_pick_up_otp'] = $this->generateOtp();
        $orderData['order_complete_otp'] = $this->generateOtp();
        $orderData['order_id'] = $this->generateOrderID($user_id);

        $order = Sell::create($orderData);
        if ($orderData['payment_method'] == "wallet") {
            $order->payment_status = (new WalletTransactionController())->walletPay($orderData['total_amt']);

            if ($order->payment_status == 'paid') {
                $order->paid_amt = $orderData['total_amt'];
                $order->due_amt = 0;
                $order->order_status = 'a_sent';
            }
            $order->save();
        }

        $items = $request->items;
        foreach ($items as $item) {

            CartItem::where('id', $item['id'])->delete();
            $orderItemData = [
                'date_time' => ($orderData['date_time']) ?? Carbon::now(),
                'qty' => $item['qty'],
                'total_amt' => $item['total_amt'],
                'user_id' => $user_id,
                'menu_id' => $item['menu_id'],
                'sell_id' => $order->id,
                'address_id' => $order->address_id,
            ];
            SellItem::create($orderItemData);
        }

        $response = [
            'order' => $order,
        ];
        if ($order->payment_method == "razorpay") {
            $response['razorpay'] = (new RazorPayController())->createOrder($order->id);
        }

        $order->load('address');


        return response($response);
    }


    function generateOrderID($user_id)
    {
        // Get current timestamp in milliseconds
        $timestamp = round(microtime(true) * 1000);

        // Concatenate user ID and timestamp to create a unique ID
        $orderID = $user_id . $timestamp;

        return $orderID;
    }

    public function cancelOrder($id)
    {
        $order = Sell::with(['address', 'driver'])->find($id);

        if ($order->user_id != auth()->user()->id) {
            return response(['message' => "You are not allowed to cancel this order!!!"], 403);
        }
        $order->order_status = 'g_cancelled';
        // $order->delivery_status = 'cancelled';
        $order->save();
        return response($order);
    }

    public function getOrderItems($id)
    {
        // $order = Sell::find($id);

        // if ($order->user_id != auth()->user()->id) {
        //     return response(['message' => "You are not allowed to see this order!!!"], 403);

        // }
        $items = SellItem::with('menu')->where('sell_id', $id)->get();

        return response($items);
    }

    function getSales()
    {
        if (isset(apache_request_headers()['isdriver']) && (apache_request_headers()['isdriver'] == 'true')) {

            $sales = Sell::with(['address', 'user'])->where('driver_id', auth()->user()->id);
        } else
        if (isset(apache_request_headers()['isbilling']) && (apache_request_headers()['isbilling'] == 'true')) {

            $sales = Sell::with(['address', 'user']);
        } else {
            $sales = Sell::with(['address', 'driver'])->where('user_id', auth()->user()->id);
        }

        $from = request('from');
        $to = request('to');

        if (isset($from)) {
            $sales = $sales->where('created_at', '>=', $from);
        }
        if (isset($to)) {
            $sales = $sales->where('created_at', '<=', $to);
        }

        return ($sales->get());
    }

    public function getAllSales()
    {
        return response($this->getSales());
    }

    function getCarts()
    {
        return (CartItem::with('menu')->where('user_id', auth()->user()->id)->get());
    }

    function getSaleAt($id)
    {
        return  response(Sell::with(['address', 'user', 'driver', 'items.menu.unit'])->find($id));
    }

    function getAddress()
    {
        return (Address::where('user_id', auth()->user()->id)->get());
    }



    ///update delivery stautus
    /// Delivery Status:
    /// accepted,received,rejected,outForDelivery,shipped,delivered,returned

    public function updateDeliveryStatus($id)
    {
        $sell = Sell::find($id);
        if (!$sell) {
            return response([
                'message' => "Sell does not exist"
            ], 203);
        }
        $status = request()->input('status');
        if ($status === 'h_rejected') {

            $sell->driver_id = null;
            $sell->delivery_status = "a_unassigned";
            $sell->save();
            return response([
                'message' => "Booking rejected successfully"
            ], 203);
        }
        if ($status === 'f_delivered') {
            $sell->order_status = "e_completed";
        }

        $sell->delivery_status = $status;

        $sell->save();
        $sell->load(['address', 'user']);
        return response([
            'message' => "Order completed successfully",
            'order' => $sell
        ]);
    }

    function orderTimeLine($orderId)
    {
        return response(OrderStatusUpdate::where('sell_id', $orderId)->get());
    }








    ///Billin app PlaceOrder
    public function placeOrderPOS(Request $request)
    {
        $orderData = $request->toArray();

        $orderData['date_time'] = Carbon::now();
        $orderData['admin_id'] = auth()->user()->id;
        $orderData['delivery_status'] = "a_unassigned";
        $orderData['order_status'] = ($request->pos_action == 'BILL') ? "e_completed" : (isset($request->dining_table_id) ? 'KOT' : 'e_completed');
        if ($request->pos_action == 'BILL') {
            $orderData['order_status'] = 'e_completed';
        }
        $orderData['invoice_id'] = (new KotTokenController())->generateToken(type: 'invoice');
        // $orderData['gst_amt'] = $request->total_amt * 0.05;
        $orderData['total_amt'] =  (int)($request->total_amt);
        $orderData['order_id'] = (new OrderController())->generateOrderID(1);

        if ($orderData['payment_status'] == 'paid') {
            $orderData['due_amt'] =  0;
            $orderData['paid_amt'] =   $orderData['total_amt'];
        }

        $order = Sell::updateOrCreate(
            ['id' => $request->id],
            $orderData
        );


        $items = [];

        $itemsJson = ($request->items);
        foreach ($itemsJson as $key => $item) {

            $orderItemData = [
                'date_time' => ($orderData['date_time']) ?? Carbon::now(),
                'qty' => $item['qty'],
                'total_amt' => $item['total_amt'],
                'menu_id' => $item['menu_id'],
                'admin_id' => auth()->user()->id,
                'token_number' => (new KotTokenController())->generateToken(),
                'sell_id' => $order->id,
            ];
            $model = SellItem::updateOrCreate([
                'sell_id' => $order->id,
                'menu_id' => $item['menu_id'],

            ], $orderItemData);

            $model->load('menu');
            array_push($items, $model);
        }
        $table = null;
        // return json_encode($request->toArray());
        if (isset($order->dining_table_id)) {
            $table = DiningTable::find($order->dining_table_id);
            $table->customer_name = $order->customer_name;
            $table->customer_mobile = $order->customer_mobile;
            $table->amount = $order->total_amt;
            $table->sell_id = $order->id;
            if ($request->pos_action == 'BILL') {
                $table->customer_name = null;
                $table->customer_mobile = null;
                $table->amount = null;
                $table->sell_id = null;
                $table->status = 'blank';
            } else {
                $table->customer_name = $order->customer_name;
                $table->customer_mobile = $order->customer_mobile;
                $table->amount = $order->total_amt;
                $table->sell_id = $order->id;
                $table->status = 'running';
            }

            $order->pos_status = $request->pos_action;
            $order->save();
            $table->save();
        }

        $order->load(['address', 'user', 'driver', 'items.menu.unit']);


        return response(
            [
                'order' => $order,
                // 'items' => $items,
                'table' => $table
            ]
        );
    }
}
