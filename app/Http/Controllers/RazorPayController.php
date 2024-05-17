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
    protected $callbackUrl = 'https://eatplan8.callvcal.com/api/callback';



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



    public function createOrder($sellId = null, $walletTransactionID = null)
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
                "razorpayKey" => $this->razorpayKey,
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
                "razorpayKey" => $this->razorpayKey,
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
