<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRewardRequest;
use App\Http\Requests\UpdateRewardRequest;
use App\Models\Reward;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class RewardController extends Controller
{
    public function getMyPoints()
    {

        $reward = Reward::where('user_id', auth()->user()->id)->first();


        if ($reward != false) {
            Log::channel('callvcal')->info("getMyPoints request " . json_encode($reward->points));
            return $reward->points;
        }

        return 0;
    }

    public function getMyReward()
    {
        $reward = Reward::where('user_id', auth()->user()->id)->first();

        if ($reward != false) {
            return $reward;
        }
        $user = auth()->user();

        return Reward::create([
            'user_id' => $user->id,
            'name' => $user->name,
            'mobile' => $user->mobile,
            'points' => 0.0,
        ]);
    }

    public function getMyRewardWithId($id)
    {
        $user = User::find($id);
        $reward = Reward::where('user_id', $id)->first();

        if ($reward) {
            return $reward;
        }

        return Reward::create([
            'user_id' => $user->id,
            'name' => $user->name,
            'mobile' => $user->mobile,
            'points' => 0.0,
        ]);
    }
}
