<?php

namespace App\Http\Controllers;


use App\Jobs\SendMessages;
use App\Models\AdminUser;
use App\Models\Driver;
use App\Models\EarningModel;
use App\Models\Business;
use App\Models\Order;
use App\Models\PaymentTransaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use paytm\paytmchecksum\PaytmChecksum;
use App\Models\TransactionDetails;
use Barryvdh\DomPDF\PDF;
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
            'business_id' => $user->business_id,
            'plan' => $request->plan,
            'transaction_status_local' => 'pending',
            'transaction_status_callback' => 'pending',
            'amount' => $request->amount,
            'gst' => $request->gst,
            'order_id' => $this->generateUniqueOrderId($user->id),
            'service_charge' => $request->service_charge,
        ]);

        return response([
            'transaction' => $transaction,
            'token' => $this->getToken($transaction),
            'callbackUrl' => $this->callbackUrl,
        ]);
    }

    public static function generateUniqueOrderId($userId)
    {
        $prefix = 'ORD'; // You can customize the prefix as needed
        $timestamp = now()->timestamp; // Current Unix timestamp
        $uniqueId = Str::random(8); // Generate a random string (8 characters)

        $orderId = $prefix . $userId . $timestamp . $uniqueId;

        return $orderId;
    }








    public function getToken($transaction)
    {



        $body = $this->getBookingPaymentBody($transaction->order_id, $transaction->amount);
        $res = $this->exec($body, $transaction->order_id);

        // $transaction->json = json_decode($res);
        // $transaction->save();

        return json_decode($res)->body->txnToken;
    }

    public function getBookingPaymentBody($orderId, $amount)
    {
        $user = auth()->user();
        return array(
            'requestType' => 'Payment',
            'mid' => $this->mid,
            'websiteName' => 'DEFAULT',
            'orderId' => $orderId,
            'callbackUrl' => $this->callbackUrl,
            'txnAmount' => array(
                'value' => $amount,
                'currency' => 'INR',
            ),
            'enablePaymentMode' => $this->enablePaymentMode(),
            'userInfo' => [
                'custId' => $user->id,
                'id' => $user->id,
                'mobile' => $user->mobile,
                'email' => $user->email,
                'firstName' => $user->name,
            ]
        );
    }

    public function isCallbackVerified($request)
    {


        $paytmChecksum = $request->CHECKSUMHASH;
        unset($request->CHECKSUMHASH);

        $isVerifySignature = PaytmChecksum::verifySignature($request->all(), $this->key, $paytmChecksum);

        if ($isVerifySignature) {
            return true;
        }

        return false;
    }


    public function onCallback(Request $request)
    {

        $request = $request->all();

        $this->verifyStatus($request);
    }

    function verifyStatus($request,$local=false)
    {
        $orderId = $request['ORDERID'];

        $order = PaymentTransaction::with('user')->where('order_id', $orderId)->first();
       

        if($local){
            $order->transaction_status_local = $request['STATUS'];
        }else{
            $order->callback_json = [
                'status' => $request['STATUS'],
            ];
        }

        $paytmChecksum = $request['CHECKSUMHASH'];

        $isVerifySignature = PaytmChecksum::verifySignature($request, $this->key, $paytmChecksum);

        if ($isVerifySignature) {
            if ($request['STATUS'] === 'TXN_SUCCESS') {
                $business = Business::find($order->business_id);

                if ($business->expiry_date != $order->expiry_date || $business->plan != $order->plan || $order->transaction_status_callback != $request['STATUS'] || $order->transaction_status_local != $request['STATUS']) {
                    $business->expiry_date = $order->expiry_date;
                    $business->purchase_date = now();
                    $business->plan = $order->plan;
                    $business->active = 1;
                    $business->save();
                }
            }
            if(!$local){
                $order->transaction_status_callback = $request['STATUS'];
            }
        }

        $order->json = [
            'status' => $request['STATUS'],
            'title' => $request['STATUS'] === 'TXN_SUCCESS' ? "Transaction successfull" : 'Transaction Failed',
            'body' => $request['STATUS'] === 'TXN_SUCCESS' ? "Dear customer, plan upgraded to " . $order->plan : "Dear customer, plan not upgraded to " . $order->plan . " you can try again with another payment option.",
        ];
        $order->save();
    }

    function paymentSuccess(Request $request)
    {
        $orderId = $request->response['ORDERID'];
        $this->verifyStatus($request->response);
        $order = PaymentTransaction::with('user')->where('order_id', $orderId)->first();
        return response([
            'user' => AdminUser::with('business')->find(auth()->user()->id),
            'message' => $order->transaction_status_callback == 'TXN_SUCCESS' ? "Plan upgraded successfully" : "Failed to upgrate plan please try again"
        ]);
    }




    public function exec($body, $orderId)
    {
        $data = array();
        $data['body'] = $body;

        $checksum = PaytmChecksum::generateSignature(json_encode($data['body'], JSON_UNESCAPED_SLASHES), $this->key);
        $data['head'] = array(
            'signature' => $checksum
        );
        $postData = json_encode($data, JSON_UNESCAPED_SLASHES);
        $url = "https://securegw.paytm.in/theia/api/v1/initiateTransaction?mid=$this->mid&orderId=$orderId";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        return curl_exec($curl);
    }


    public function enablePaymentMode()
    {
        return array(
            array(
                'mode' => "UPI",
                'channels' => ['UPIPUSH', 'UPIPUSHEXPRESS', 'UPI']
            ),
            array(
                'mode' => "BALANCE",
                // 'channels' => ['UPIPUSH', 'UPIPUSHEXPRESS']
            ),
            array(
                'mode' => "PPBL",
                // 'channels' => ['UPIPUSH', 'UPIPUSHEXPRESS']
            ),
            array(
                'mode' => "CREDIT_CARD",
                'channels' => ['VISA', 'MASTER', 'AMEX', 'RUPAY']
            ),
            array(
                'mode' => "DEBIT_CARD",
                'channels' => ['VISA', 'MASTER', 'AMEX', 'RUPAY']
            ),
            array(
                'mode' => "NET_BANKING",
                // 'channels' => ['UPIPUSH', 'UPIPUSHEXPRESS']
            ),
            array(
                'mode' => "PAYTM_DIGITAL_CREDITFor",
                // 'channels' => ['UPIPUSH', 'UPIPUSHEXPRESS']
            ),
        );
    }
}
