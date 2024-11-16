<?php

namespace App\Http\Controllers;

use App\Admin\Forms\Setting;
use App\Jobs\WalletCreditJob;
use App\Models\EarningModel;
use App\Models\PaymentData;
use App\Models\PaymentTransaction;
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
    // protected $razorpayKey = '';
    public function razorpayKey()
    {
        // return 'rzp_test_jSpVqLFtzzb2Ws';
        return env('RAZORPAY_IOS_KEY');
        // return (new Setting())->data()['razorpayKey'];
    }
    // protected $razorpayKey = 'rzp_test_zy6lT4IBQKa0Sp';
    // protected $keySecrete = 'scnsflKLMAKKjsI89C8u9q44';

    public function keySecrete()
    {
        // return 'Y3Wdsko5BWZuNdH7aGVdjzJp';
        return env('RAZORPAY_IOS_SECRETE');
        // return (new Setting())->data()['keySecrete'];
    }
    // protected $keySecrete = '';
    // protected $keySecrete = 'scnsflKLMAKKjsI89C8u9q44';
    protected $callbackUrl = 'https://amarjeans.in/api/callback';



    // protected $callvcalWalletID = null;
    protected $walletTransactionLogger = "wallet-transactions";
    protected $walletCallbackLogger = "wallet-callback";
    protected $walletLogger = "wallet";

    // v7r0WOhrLhbskYzsY2FlKZNv86CwG2xTgha
    public function callback(Request $request)
    {
        
        return response(['message' => 'success']);
    }

    function isSuccessfully($id)
    {

        $payment = $this->showAt($id);

        return ($payment);
    }



    function showAt($id)
    {
        // Validate $id and set a default value if it's null
        // $id = $id ?? 'order_Nu5DWHGafHgPyn';

        $response = [];

        // Check if $id is still null after validation
        if ($id === null) {
            return 'n/a';
        } else {


            try {
                // Assuming $this->razorpayKey() and $this->keySecrete() are initialized somewhere
                $api = new Api($this->razorpayKey(), $this->keySecrete());

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
                    $response['receipt'] = $res['receipt'];
                }
            } catch (Exception $e) {
                $response['message'] = $e;
            }
        }



        return  ($response);
    }









    public function createOrder(PaymentTransaction $transaction)
    {
        $amount=$transaction->amount;
        $platform=$transaction->platform??'';
        $order_id=$transaction->order_id;

        Log::info('razorpayKey: '.$this->razorpayKey().' Secrete: '. $this->keySecrete().' '.$order_id);

        $api = new Api($this->razorpayKey(), $this->keySecrete());
        $body = [
            'amount' => $amount * 100,
            'currency' => 'INR',
            'receipt' => $order_id,
        ];

        $json=$transaction->json??[];

        $res = $api->order->create($body);



        if ($res['id'] == null) {
            $res = [
                "error" => $res['error']
            ];
        } else {
            $res = [
                "id" => $res['id'],
                "entity" => $res['entity'],
                "amount" => $res['amount'],
                "amount_paid" => $res['amount_paid'],
                "amount_due" => $res['amount_due'],
                "currency" => $res['currency'],
                "receipt" => $res['receipt'],
                "offer_id" => $res['offer_id'],
                "status" => $res['status'],
                "attempts" => $res['attempts'],
                "notes" => $res['notes'],
                "notes" => $res['notes'],
                "razorpayKey" => $this->razorpayKey(),
                "created_at" => $res['created_at']
            ];
        $transaction->transaction_id=$res['id'];

        }

        array_push($json,$res);
        

        $transaction->json=$json;
        $transaction->save();


        return ($res);
    }
}
