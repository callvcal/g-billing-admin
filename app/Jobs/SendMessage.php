<?php

namespace App\Jobs;

use App\Admin\Controllers\SellController;
use App\Events\OrderEvent;
use App\Http\Controllers\FirebaseController;
use App\Models\OrderStatusUpdate;
use App\Models\Sell;
use App\Models\TableRequest;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Event;
use Pusher\Pusher;

class SendMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $orderID;
    protected $model;
    protected $signal;

    /**
     * Create a new job instance.
     */
    public function __construct($orderID, $signal, $model)
    {
        $this->orderID = $orderID;
        $this->signal = $signal;
        $this->model = $model;
    }

    public function handle(): void
    {
        // if($this->signal=='order_create')
        try {
            if ($this->model == 'order') {

                $order = Sell::find($this->orderID);
                if (!$order) {
                    Log::channel('callvcal')->info("SendMessage:handle Order not found: " . $this->orderID);
                    return;
                }
                Log::channel('callvcal')->info("SendMessage:test Order: " . json_encode($order->toArray()));

                // Assuming User and FirebaseController classes are properly defined
                $user = User::find($order->user_id);
                if (!$user || !isset($user->fcm_token)) {
                    Log::channel('callvcal')->info("SendMessage:handle User or FCM token not found");
                    return;
                }

                $orderStatus = $order->order_status;
                $delivery_status = $order->delivery_status;



                $status = $this->status($orderStatus, $delivery_status, $order->driver_id);

                if ($status == null) {
                    return;
                }

                $data = [
                    "title" => $order->order_id,
                    "body" => $status,
                    "imageUrl" => null,
                    'order_id' => $order->id,
                    'type' => "order",
                ];
                $pusher = new Pusher(auth_key: env('PUSHER_APP_KEY'), secret: env('PUSHER_APP_SECRET'), app_id: env('PUSHER_APP_ID'), options: [
                    'cluster' => env('PUSHER_APP_CLUSTER')
                ]);
                $pusher->trigger('eatplan8', event: 'orders', data: [
                    'message' => $data['body'],
                    'body' => $data['body'],
                    'order_id' => $order->id,
                    'title' => $order->order_id,
                    'order_status' => $order->order_status,
                    'delivery_status' => $order->delivery_status,
                ]);
                $notification = [
                    "title" => $data['title'],
                    "body" => $data['body'],
                    "imageUrl" => $data['imageUrl'],
                    "sound" => "default",
                ];

                $controller = new FirebaseController();
                $controller->sendFcmMessage($user->fcm_token, $data, $notification);
                $localizedTimestamp = Carbon::now()->setTimezone('Asia/Kolkata');

                OrderStatusUpdate::create([
                    'sell_id' => $order->id,
                    'driver_id' => $order->driver_id,
                    'user_id' => $order->user_id,
                    'order_status' => $orderStatus,
                    'dominant' => Carbon::now(),
                    'status' => $data['body'],
                    'updated_at' => $localizedTimestamp,
                    'delivery_status' => $delivery_status,
                ]);
                if (isset($order->driver_id)) {

                    $driver = User::find($order->driver_id);
                    if ($driver && isset($driver->fcm_token)) {
                        $controller->sendFcmMessage($driver->fcm_token, $data, $notification);
                    }
                    if (($order->delivery_status == 'a_unassigned')) {
                        $order->delivery_status = 'b_assigned';
                        $order->save();
                    }
                }
                if ($orderStatus == 'a_sent') {
                    $controller->sendFcmToTopic('orders', [
                        'order_id' => $order->id,
                        'type' => "order",
                    ], [
                        'title' => "Order",
                        'body' => "New Order Received",
                    ]);
                }
            }
            if ($this->model == 'dining_table') {
                $item = TableRequest::find($this->orderID);
                if (!$item) return;
                $data = [
                    "title" => $item->order_id,
                    "body" => "Status of dining table is " . $item->status,
                    "imageUrl" => null,
                    'model_id' => $item->id,
                    'type' => "dining_table",
                ];

                $notification = [
                    "title" => $data['title'],
                    "body" => $data['body'],
                    "imageUrl" => $data['imageUrl'],
                    "sound" => "default",
                ];

                $controller = new FirebaseController();
                $user = User::find($item->user_id);
                $controller->sendFcmMessage($user->fcm_token, $data, $notification);
            }
        } catch (Exception $e) {
            // Log error details
            Log::channel('callvcal')->error("Error in SendMessage.handle: " . $e->getMessage());
            Log::channel('callvcal')->error("Error code: " . $e->getCode());
            Log::channel('callvcal')->error("File: " . $e->getFile());
            Log::channel('callvcal')->error("Line: " . $e->getLine());
            Log::channel('callvcal')->error("Stack trace: " . $e->getTraceAsString());

            throw $e;
        }
    }

    public function status($orderStatus, $deliveryStatus, $driverId)
    {
        switch ($orderStatus) {
            case 'unknown':
                return null;
            case 'a_sent':
                // Initial state when the order is placed
                return 'Your order has been placed successfully';
            case 'b_accepted':
                // Order has been accepted by the restaurant
                // switch ($deliveryStatus) {
                //     case 'b_assigned':
                //         return 'A delivery boy has been assigned to your order';
                //     case 'c_accepted':
                //         return 'A delivery boy has accepted your order';
                //     case 'h_rejected':
                //         return 'Your order is ready for pickup';
                //     case 'd_pickedUp':
                //         return 'The delivery boy has picked up your order';
                //     case 'e_outForDelivery':
                //         return 'Your order is now out for delivery';
                //     case 'f_delivered':
                //         return 'Your order has been delivered successfully';
                //     case 'g_returned':
                //         return 'The customer has returned the order';
                //     default:
                // }
                return 'Your order has been accepted and is in progress';
            case 'f_rejected':
                // Order has been rejected by the restaurant
                return 'Unfortunately, your order has been rejected';
            case 'g_cancelled':
                // Order has been cancelled
                return 'Your order has been cancelled';
            case 'c_preparing':
                // Order is being prepared by the restaurant
                return 'Your order is currently being prepared';
            case 'd_readyToPickup':
                // Order is ready for pickup
                switch ($deliveryStatus) {
                    case 'a_unassigned':
                        return 'Your order is ready for pickup';
                    case 'b_assigned':
                        return 'A delivery boy has been assigned for pickup';
                    case 'c_accepted':
                        return 'The delivery boy has accepted the pickup';
                    case 'h_rejected':
                        return 'Your order is ready for pickup';
                    case 'd_pickedUp':
                        return 'The delivery boy has picked up your order';
                    case 'e_outForDelivery':
                        return 'Your order is now out for delivery';
                    case 'f_delivered':
                        return 'Your order has been delivered successfully';
                    case 'g_returned':
                        return 'The customer has returned the order';
                    default:
                }
                return 'Your order is ready for pickup';
            case 'e_completed':
                // Order has been completed
                return 'Your order has been completed successfully';
            default:
                // Unknown status
                return 'Your order is in an unknown status';
        }
    }
}
