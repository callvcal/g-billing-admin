<?php

namespace App\Http\Controllers;

use App\Admin\Forms\Setting as FormsSetting;
use App\Jobs\WalletCreditJob;
use App\Models\RewardTransaction;
use App\Models\ServiceModel;
use App\Models\Setting;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\WalletTransactionDetail;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WalletTransactionController extends Controller
{

    // protected $callvcalWalletID = null;
    protected $walletTransactionLogger = "wallet-transactions";
    protected $walletCallbackLogger = "wallet-callback";
    protected $walletLogger = "wallet";

    function generateOrderID($id)
    {
        return (new OrderController())->generateOrderID($id);
    }


    public function initCredit(Request $request)
    {

        $request->validate([
            'amount' => 'required'
        ]);
        $user = auth()->user();
        $wallet = Wallet::where('user_id', $user->id)->first();
        if ($wallet == false) {
            $wallet = Wallet::create([
                'user_id' => $user->id,
                'name' => $user->name,
                'mobile' => $user->mobile,
                'balance' => 0.0,
            ]);
        }

        $order_id = $this->generateOrderID($user->id);

        $amount = $request->amount;






        // $txn_token = $manager->getTokenWallet($tokenRequest);


        $walletTransaction = WalletTransaction::create([
            'amount' => $amount,
            'order_id' => $order_id,
            'user_id' => $user->id,
            'credit_wallet_id' => $wallet->id,
            'transaction_type' => 'credit',
            'status' => 'Pending',
            'authenticated_user_id' => auth()->user()->id,
            'authenticated_user_role' => 'user',
            'name' => $user->name,
            'mobile' => $user->mobile,
            'name' => $user->name,
            'date_time' => $request->date_time

        ]);
        Log::channel($this->walletTransactionLogger)->info("transaction started with user_id:" . auth()->user()->id . ", walletTransactionID: " . $walletTransaction->id);



        return response([
            'data' => $walletTransaction,
            'razorpay' => (new RazorPayController())->createOrder(walletTransactionID: $walletTransaction->id)
        ]);
    }

    public function onCallback(Request $request)
    {


        $orderId = $request->ORDERID;

        $walletTransaction = WalletTransaction::with('user', 'creditor')->where('order_id', $orderId)->first();
        // Log::channel($this->walletCallbackLogger)->info("received callback user_id:".$walletTransaction->user_id.", walletTransactionID: ".$walletTransaction->id);


        $isVerifySignature = true;
        // $isVerifySignature =$manager->isCallbackVerified($request);
        Log::channel($this->walletCallbackLogger)->info("isVerifySignature:$isVerifySignature callback user_id:" . $walletTransaction->user_id . ", walletTransactionID: " . $walletTransaction->id);



        if ($isVerifySignature) {
            Log::channel($this->walletCallbackLogger)->info("transaction verified with user_id:" . $walletTransaction->user_id . ", walletTransactionID: " . $walletTransaction->id);
            $walletTransaction->status = $request->STATUS;
            $walletTransaction->transaction_id = $request->TXNID;
            $walletTransaction->save();

            if ($request['STATUS'] == 'TXN_SUCCESS') {

                try {
                    Log::channel($this->walletCallbackLogger)->info("transaction successfuly with user_id:" . $walletTransaction->user_id . ", walletTransactionID: " . $walletTransaction->id);
                    Log::channel($this->walletLogger)->info("transaction successfuly with user_id:" . $walletTransaction->user_id . ", walletTransactionID: " . $walletTransaction->id);


                    $walletTransaction->creditor->balance = $walletTransaction->creditor->balance + $walletTransaction->amount;
                    Log::channel($this->walletLogger)->info($walletTransaction->amount . " is credited into creditor: " . $walletTransaction->creditor->id . " user_id:" . $walletTransaction->user_id . ", walletTransactionID: " . $walletTransaction->id);


                    $walletTransaction->creditor->save();
                    // $walletTransaction->debitor->save();
                    Log::channel($this->walletLogger)->info("Transaction saved successfuly" . " user_id:" . $walletTransaction->user_id . ", walletTransactionID: " . $walletTransaction->id);
                } catch (Exception $e) {
                    Log::channel('callvcal')->info("wallet-transaction-callback-error:" . json_encode($e));
                }
            }

            return;
        }
    }








    public function rewardConvert(Request $request)
    {



        $request->validate([
            'points' => 'required'
        ]);


        $user = auth()->user();
        $points = $request->points;
        $wallet = Wallet::where('user_id', $user->id)->first();
        $rewardConvertRate = 0.25;


        $setting = (new FormsSetting())->data();

        if ($setting) {
            $rewardConvertRate = floatval($setting['rewardConvertRate'] ?? $rewardConvertRate);
        }

        $amount = (float)($rewardConvertRate) * $points;

        $walletTransaction = WalletTransaction::create([
            'amount' => $amount,
            'txn_token' => null,
            'order_id' => $this->generateOrderID($user->id),
            'user_id' => $user->id,
            'credit_wallet_id' => $wallet->id,
            'transaction_type' => 'credit',
            'status' => 'Pending',
            'authenticated_user_id' => auth()->user()->id,
            'authenticated_user_role' => 'user',
            'name' => $user->name,
            'mobile' => $user->mobile,
            'name' => $user->name,
            'date_time' => $request->date_time

        ]);
       
        $rewardTransaction = RewardTransaction::create([
            'points' => $points,
            'reward_id' =>  $wallet->id,
            'referal_id' => null,
            'user_id' => $user->id,

            'transaction_type' => 'credit',
            'status' => 'Pending',
            'authenticated_user_id' => auth()->user()->id,
            'authenticated_user_role' => 'user',
            'name' => $user->name,
            'mobile' => $user->mobile,
            'name' => $user->name,
            'date_time' => $request->date_time

        ]);
        $walletTransaction->creditor->balance = $walletTransaction->creditor->balance + $walletTransaction->amount;
        $walletTransaction->remark="Reward conversion";
        $walletTransaction->creditor->save();


        $rewardTransaction->reward->points = $rewardTransaction->reward->points - $rewardTransaction->points;
        $rewardTransaction->reward->save();

        $walletTransaction->status='success';

        $walletTransaction->save();
        Log::channel($this->walletTransactionLogger)->info("transaction started with user_id:" . auth()->user()->id . ", walletTransactionID: " . $walletTransaction->id);


        dispatch(new WalletCreditJob(token:$user->fcm_token,amount:$walletTransaction->amount,total:$walletTransaction->creditor->balance))->afterResponse();

        return (new WalletController())->getAccBal();
    }
    public function walletPay($amount)
    {

        $user = auth()->user();
        $wallet = Wallet::where('user_id', $user->id)->first();

        if($wallet->balance<$amount){
            return 'pending';
        }

        $rewardConvertRate = 0.25;


        $setting = (new FormsSetting())->data();

        if ($setting) {
            $rewardConvertRate = floatval($setting['rewardConvertRate'] ?? $rewardConvertRate);
        }


        $walletTransaction = WalletTransaction::create([
            'amount' => $amount,
            'txn_token' => null,
            'order_id' => $this->generateOrderID($user->id),
            'user_id' => $user->id,
            'credit_wallet_id' => $wallet->id,
            'transaction_type' => 'debit',
            'status' => 'Pending',
            'authenticated_user_id' => auth()->user()->id,
            'authenticated_user_role' => 'user',
            'name' => $user->name,
            'mobile' => $user->mobile,
            'name' => $user->name,
            'date_time' =>Carbon::now()

        ]);
       
       
        
        $walletTransaction->creditor->balance = $walletTransaction->creditor->balance - $walletTransaction->amount;
        $walletTransaction->remark="Purchasing";
        $walletTransaction->creditor->save();
        $walletTransaction->status='success';

        $walletTransaction->save();
        Log::channel($this->walletTransactionLogger)->info("transaction started with user_id:" . auth()->user()->id . ", walletTransactionID: " . $walletTransaction->id);


        dispatch(new WalletCreditJob(token:$user->fcm_token,amount:$walletTransaction->amount,total:$walletTransaction->creditor->balance,type:'debit'))->afterResponse();

        return 'paid';
    }
}
