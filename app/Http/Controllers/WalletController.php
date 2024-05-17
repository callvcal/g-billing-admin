<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function getAccBal()
    {
        $user = auth()->user();
        $id = $user->id;
        $wallet = Wallet::where('user_id', $id)->first();
        if (!$wallet) {
            $wallet = Wallet::create([
                'user_id' => $user->id,
                'name' => $user->name,
                'mobile' => $user->mobile,
                'balance' => 0.0,
            ]);
        }

        $reward = (new RewardController())->getMyReward();


        $transactions = WalletTransaction::where('user_id', $id)->get();
        return response([
            'wallet' => $wallet,
            'transactions' => $transactions,
            'reward' => $reward,
        ]);
    }
}
