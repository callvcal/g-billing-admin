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
        
        $json['title'] = "Payment Started";
        $json['body'] = "Waiting for payment...";
        $transaction->json=$json;
        $transaction->save();


        return ($res);
    }

    public function makeWalletPayment(WalletTransaction $walletTransaction)
    {
        $amount=$walletTransaction->amount;
        $order_id=$walletTransaction->order_id;

        Log::info('razorpayKey: '.$this->razorpayKey().' Secrete: '. $this->keySecrete().' '.$order_id);

        $api = new Api($this->razorpayKey(), $this->keySecrete());
        $body = [
            'amount' => $amount * 100,
            'currency' => 'INR',
            'receipt' => $order_id,
        ];

        

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
        $walletTransaction->transaction_id=$res['id'];

        }

        PaymentData::create(
            [
                'json' => $res,
                'user_id' => $walletTransaction->user_id,
                'sell_id' => null,
                'receipt_id' => $order_id,
                'payment_gateway' => 'razorpay',
                'request_type' => 'createOrder',
                'wallet_transaction_id' => null,
                'table_request_id' => null,
                'payment_type' => 'wallet',
            ]
        );


        return ($res);
    }

    public function razorpayOrder($sellId = null, $walletTransactionID = null)
    {

        if (isset($sellId)) {
            $sell = Sell::find($sellId);
            if (!$sell) {
                return null;
            }
            $userID = $sell->user_id;
            $order_id = $sell->order_id;
            $amount = $sell->total_amt;
        }
        if (isset($walletTransactionID)) {
            $walletTransaction = WalletTransaction::find($walletTransactionID);
            if (!$walletTransaction) {
                return null;
            }
            $userID = $walletTransaction->user_id;
            $order_id = $walletTransaction->order_id;
            $amount = $walletTransaction->amount;
        }



        $api = new Api($this->razorpayKey(), $this->keySecrete());
        $body = [
            'amount' => $amount * 100,
            'currency' => 'INR',
            'receipt' => $order_id,
        ];

        $res = $api->order->create($body);


        Log::channel('callvcal')->info(json_encode([
            'order_id' => $res['id'],
            'receipt' => $res['receipt'],
        ]));

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
        }

        if (isset($sell)) {
            $sell->transaction_id = $res['id'];
            $sell->save();
        }
        if (isset($walletTransaction)) {
            $walletTransaction->transaction_id = $res['id'];
            $walletTransaction->save();
        }



        PaymentData::create(
            [
                'json' => $res,
                'user_id' => $userID,
                'sell_id' => $sellId,
                'receipt_id' => $order_id,
                'payment_gateway' => 'razorpay',
                'request_type' => 'createOrder',
                'wallet_transaction_id' => $walletTransactionID,
                'payment_type' => ($sellId != null) ? 'sell' : (($walletTransactionID != null) ? 'wallet' : 'n/a'),
            ]
        );



        return ($res);
    }
    public function createTableRequest($model)
    {

        $userID = $model->user_id;
        $order_id = $model->order_id;
        $amount = $model->charge;



        $api = new Api($this->razorpayKey, $this->keySecrete);
        $body = [
            'amount' => $amount * 100,
            'currency' => 'INR',
            'receipt' => $order_id,
        ];

        $res = $api->order->create($body);


        Log::channel('callvcal')->info(json_encode([
            'order_id' => $res['id'],
            'receipt' => $res['receipt'],
        ]));

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
        }

        $model->transaction_id = $res['id'];
        $model->save();



        PaymentData::create(
            [
                'json' => $res,
                'user_id' => $userID,
                'sell_id' => null,
                'receipt_id' => $order_id,
                'payment_gateway' => 'razorpay',
                'request_type' => 'createOrder',
                'wallet_transaction_id' => null,
                'table_request_id' => $model->id,
                'payment_type' => 'dining-table',
            ]
        );



        return ($res);
    }

    public function paymentSuccessDining(Request $request)
    {

        $table_request_id = $request->table_request_id;

        $model = TableRequest::find($table_request_id);

        if (!$model) {
            return response(['message' => "Request does not exist"], 401);
        }

        $model->payment_status = "paid";
        $model->save();

        return response(['message' => "Payment successfull", 'data' => $model]);
    }
    public function paymentErrorDining(Request $request)
    {

        $table_request_id = $request->table_request_id;

        $model = TableRequest::find($table_request_id);

        if (!$model) {
            return response(['message' => "Request does not exist"], 401);
        }

        $model->payment_status = "transaction_failed";
        $model->save();

        return response(['message' => "Payment failed", 'data' => $model]);
    }

    public function paymentSuccess(Request $request)
    {

        $sellId = $request->sell_id;

        $sell = Sell::find($sellId);

        if (!$sell) {
            return response(['message' => "Sell does not exist"], 401);
        }

        $sell->order_status = "a_sent";
        $sell->payment_status = "paid";
        $sell->save();

        return response(['message' => "Sell updated", 'sell' => $sell]);
    }
    public function paymentError(Request $request)
    {

        $sellId = $request->sell_id;

        $sell = Sell::find($sellId);

        if (!$sell) {
            return response(['message' => "Sell does not exist"], 401);
        }

        $sell->order_status = "g_cancelled";
        $sell->save();

        return response(['message' => "Sell updated", 'sell' => $sell]);
    }

    ///Wallet
    public function paymentSuccessWallet(Request $request)
    {

        $id = $request->id;

        $walletTransaction = WalletTransaction::with('user', 'creditor')->find($id);

        if (!$walletTransaction) {
            return response(['message' => "transaction does not exist"], 401);
        }

        $walletTransaction->status = "success";
        $walletTransaction->save();
        try {
            Log::channel($this->walletCallbackLogger)->info("transaction successfuly with user_id:" . $walletTransaction->user_id . ", walletTransactionID: " . $walletTransaction->id);
            Log::channel($this->walletLogger)->info("transaction successfuly with user_id:" . $walletTransaction->user_id . ", walletTransactionID: " . $walletTransaction->id);


            $walletTransaction->creditor->balance = $walletTransaction->creditor->balance + $walletTransaction->amount;
            Log::channel($this->walletLogger)->info($walletTransaction->amount . " is credited into creditor: " . $walletTransaction->creditor->id . " user_id:" . $walletTransaction->user_id . ", walletTransactionID: " . $walletTransaction->id);

            $walletTransaction->remark = "Wallet Top Up";
            $walletTransaction->creditor->save();
            dispatch(new WalletCreditJob(token: (User::find($walletTransaction->user_id))->fcm_token, amount: $walletTransaction->amount, total: $walletTransaction->creditor->balance))->afterResponse();

            // $walletTransaction->debitor->save();
            Log::channel($this->walletLogger)->info("Transaction saved successfuly" . " user_id:" . $walletTransaction->user_id . ", walletTransactionID: " . $walletTransaction->id);
        } catch (Exception $e) {
            Log::channel('callvcal')->info("wallet-transaction-callback-error:" . json_encode($e));
        }
        return ((new WalletController())->getAccBal());
    }
    public function paymentErrorWallet(Request $request)
    {

        $id = $request->id;

        $trn = WalletTransaction::find($id);

        if (!$trn) {
            return response(['message' => "transaction does not exist"], 401);
        }

        $trn->order_status = "failed";
        $trn->save();

        return ((new WalletController())->getAccBal());
    }
}
