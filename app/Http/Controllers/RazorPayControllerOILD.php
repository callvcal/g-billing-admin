<?php

namespace App\Http\Controllers;

use App\Jobs\WalletCreditJob;
use App\Models\EarningModel;
use App\Models\PaymentData;
use App\Models\Sell;
use App\Models\TableRequest;
use App\Models\User;
use App\Models\WalletTransaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Mockery\Matcher\Type;
use Razorpay\Api\Api;

class RazorPayController extends Controller
{

    protected $api = "https://api.razorpay.com";
    protected $mid = 'Iv0uwP6JVTx0aa';
    protected $razorpayKey = 'rzp_live_hVRQG0QH0uGGFb';
    // protected $razorpayKey = 'rzp_test_zy6lT4IBQKa0Sp';
    protected $keySecrete = 'APmk2gvvwJGMmGKXSlhc9MPg';
    // protected $keySecrete = 'scnsflKLMAKKjsI89C8u9q44';
    protected $callbackUrl = 'https://eatinsta.callvcal.com/api/callback';



    // protected $callvcalWalletID = null;
    protected $walletTransactionLogger = "wallet-transactions";
    protected $walletCallbackLogger = "wallet-callback";
    protected $walletLogger = "wallet";



    function showAt($id)
    {
        // Validate $id and set a default value if it's null
        $id = $id ?? 'order_Nu5DWHGafHgPyn';

        $response = [];

        // Check if $id is still null after validation
        if ($id === null) {
            return 'n/a';
        } else {


            try {
                // Assuming $this->razorpayKey and $this->keySecrete are initialized somewhere
                $api = new Api($this->razorpayKey, $this->keySecrete);

                // Fetch data from the API
                $res = $api->order->fetch($id)->toArray();

                // Check if $res contains an 'error' key (indicating a failure response)
                if (isset($res['error'])) {

                    $response['message'] = $res['error']['description'];
                } else {

                    $response['id'] = $res['id'];

                    $response['amount_paid'] = $res['amount_paid'] / 100;
                    $response['amount_due'] = $res['amount_due'] / 100;
                    $response['amount'] = $res['amount'] / 100;
                    $response['status'] = $res['status'];
                }
            } catch (Exception $e) {
                $response['message'] = $e;
            }
        }



        return json_encode($response);
    }

    public function  getQRcode(Sell $sell)
    {
        return null;
       try{
        $api = new Api($this->razorpayKey, $this->keySecrete);
        // $data = array(
        //     "type" => "upi_qr",
        //     "name" => env("APP_NAME"),
        //     "usage" => "single_use",
        //     "fixed_amount" => 1,
        //     "payment_amount" => $sell->total_amt * 100,
        //     "customer_id" => $sell->user_id, "description" => json_encode(count($sell->items)), "close_by" => now()->addHour(2), "notes" => array("purpose" => "QR CODES PAYMENTS")
        // );
        // $res=$api->qrCode->create($data);
        $res=$api->qrCode->create(array("type" => "upi_qr","name" => "Store Front Display", "usage" => "single_use","fixed_amount" => 1,"payment_amount" => 300,"customer_id" => "cust_HKsR5se84c5LTO","description" => "For Store 1","close_by" => 1681615838,"notes" => array("purpose" => "Test UPI QR code notes")));
        if ($res['id'] != null) {
            $sell->trancation_id=$res['id'];

            return $res['image_url'];
        } 
        return null;
       }catch(Exception $e){
        Log::channel('callvcal')->error("Stack trace: " . $e->getTraceAsString());
       }
       return null;
    }



   


  
}
