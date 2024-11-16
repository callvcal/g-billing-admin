<?php

namespace App\Http\Controllers;


use App\Jobs\SendMessages;
use App\Models\AdminUser;
use App\Models\Business;
use App\Models\Driver;
use App\Models\EarningModel;
use App\Models\Location;
use App\Models\Order;
use App\Models\PaymentTransaction;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\TransactionDetails;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Mockery\Matcher\Type;
use Illuminate\Support\Str;

class ManagePaymentController extends Controller
{

    protected $mid = 'VGPkMx36117629022922';
    protected $key = '5ZnAu3EpiaDJpeev';
    protected $callbackUrl = 'https://parking.callvcal.com/api/paytm/callback';


    function buyPlan(Request $request)
    {
        $user = auth()->user();


        $transaction = PaymentTransaction::create([
            'user_id' => $user->id,
            'date_time' => now(),
            'expiry_date' => $request->expiry_date,
            'location_id' => $user->location_id,
            'plan' => $request->plan,
            'platform' => $request->platform ?? 'android',
            'method' => 'razorPay',
            'transaction_status_local' => 'pending',
            'transaction_status_callback' => 'pending',
            'amount' => $request->amount,
            'gst' => $request->gst,
            'json' => [],
            'order_id' => $this->generateUniqueOrderId($user->id),
            'service_charge' => $request->service_charge,
        ]);

        if (!isset($request->razorpay)) {
            return response([
                'message' => "Please use the Google Pay option for plan upgrades. We are currently experiencing issues with our payment gateway, which will be resolved in an upcoming update."
            ], 401);
        }



        return response([
            'transaction' => $transaction,
            'token' => null,
            'razorpay' => (new RazorPayController())->createOrder($transaction),
            'callbackUrl' => $this->callbackUrl,
        ]);
    }

    public static function generateUniqueOrderId($userId)
    {
        $prefix = 'ORD'; // 3 characters
        $timestamp = now()->timestamp; // 10 characters
        $uniqueId = Str::random(16); // Adjusted to 16 characters for a unique string within 40-character limit

        // Concatenate to create orderId
        $orderId = $prefix . $userId . $timestamp . $uniqueId;

        // Ensure the final length is within 40 characters
        return substr($orderId, 0, 40);
    }













    public function onCallBack(Request $request)
    {


        $order_id = $request->payload['payment']['entity']['order_id'];

        return response($this->verifyStatus($order_id));
    }





    function verifyStatus($orderId)
    {
        // Verify payment status
        $status = (new RazorPayController())->isSuccessfully($orderId);
    
        if (!isset($status['receipt'])) {
            return false;
        }
    
        $transaction = PaymentTransaction::where('order_id', $status['receipt'])->first();
        if (!$transaction) {
            return false;
        }
    
        $json = is_array($transaction->json) ? $transaction->json : [];
        $plan = $transaction->plan;
    
        if ($status['status'] === 'paid') {
            $business = Business::find($transaction->location_id);
    
            if (!$business) {
                return false;
            }
    
            // Calculate the new expiry date based on the plan
            $expiry_date = Carbon::now();
            switch ($plan) {
                case 'monthly':
                    $expiry_date->addMonth();
                    break;
                    case 'weekly':
                        $expiry_date->addDays(7);
                        break;
                case 'annual':
                    $expiry_date->addYear();
                    break;
                case 'lifetime':
                    $expiry_date->addYears(10);
                    break;
            }

            Log::channel("callvcal")->info('expiry_date: '.$expiry_date.' '.$plan);
    
            $businessExpiryDate = Carbon::parse($business->expiry_date);
    
            // Check if the business plan and expiry date match, allowing a slight difference
            if (
                $business->plan === $plan &&
                $businessExpiryDate->diffInMinutes($expiry_date) <= 5 // Changed to 5 for greater reliability
            ) {
                return true;
            }
    
            // Update the business with new plan details
            $business->expiry_date = $expiry_date;
            $business->purchase_date = now();
            $business->plan = $plan;
            $business->active = 1;
            $business->save();
    
            // Update transaction json with success message
            $json['title'] = "Payment Successful";
            $json['body'] = "Dear customer, your plan has been successfully upgraded to the $plan Plan. It is valid until $expiry_date.";
        } else {
            // Update transaction json with failure message
            $json['title'] = "Payment Failed";
            $json['body'] = "Dear customer, we could not upgrade your plan to the $plan Plan.";
        }
    
        // Save the updated JSON data in the transaction
        $transaction->json = $json;
        $transaction->save();
    
        return $status['status'] === 'paid';
    }
    

    function paymentSuccess(Request $request)
    {
        $isPaid = $this->verifyStatus($request->response['razorpay_order_id']);

        if ($isPaid) {
            return response([
                'user' => AdminUser::with('business')->find(auth()->user()->id),
                'isPaid' => $isPaid,
                'message' => $isPaid ? "Plan upgraded successfully" : "Failed to upgrade plan, please try again",
            ]);
        } else {
            return response([
                'user' => AdminUser::with('business')->find(auth()->user()->id),
                'isPaid' => $isPaid,
                'message' => $isPaid ? "Plan upgraded successfully" : "Failed to upgrade plan, please try again",
            ], 201);
        }
    }
}
