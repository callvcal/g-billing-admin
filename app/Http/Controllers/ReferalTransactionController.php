<?php

namespace App\Http\Controllers;

use App\Admin\Forms\Setting;
use App\Http\Requests\StoreReferalTransactionRequest;
use App\Http\Requests\UpdateReferalTransactionRequest;
use App\Jobs\ReferEarnJob;
use App\Jobs\SendMessage;
use App\Models\Referal;
use App\Models\ReferalTransaction;
use App\Models\RewardTransaction;
use App\Models\SpecialDiscount;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReferalTransactionController extends Controller
{

    public function create($request)
    {
        date_default_timezone_set("Asia/Kolkata");

        try {


            $setting = (new Setting())->data();



            $link = $request['refer']['link'];
            $query = parse_url($link, PHP_URL_QUERY);
            parse_str($query, $params);

            $isNewUser = $request['isNewUser'];

            $referring_user_id = $params['user_id'];
            $referred_user_id = $request['referedUserId'];

            $user = User::find($referring_user_id);
            $refered = User::find($referred_user_id);

            $transaction = ReferalTransaction::create([
                'referring_user_id' => $referring_user_id,
                'referred_user_id' => $referred_user_id,
                'name' => $user->name,
                'mobile' => $user->mobile,
                'transaction_type' => 'credit',
                'remark' => 'Refered to ' . $refered->name,
                'date_time' => Carbon::now(),
            ]);

            $refer = (new ReferalController())->getReferWithID($referring_user_id);

            $refer->refered_count = $refer->refered_count + 1;

            $refer->save();

            $rewardToReferringUser = (new RewardController())->getMyRewardWithId($referring_user_id);
            $rewardToReferredUser = (new RewardController())->getMyRewardWithId($referred_user_id);

            $rewardTransactionsToReferringUser = RewardTransaction::create([
                'points' => $isNewUser ? $setting['refer_earn_new_user_rewards'] : $setting['refer_earn_old_user_rewards'],
                'reward_id' =>  $rewardToReferringUser->id,
                'referal_id' => null,
                'user_id' => $user->id,

                'transaction_type' => 'credit',
                'status' => 'Pending',
                'authenticated_user_id' => $user->id,
                'authenticated_user_role' => 'user',
                'remark' => ' You refered to ' . $refered->name,
                'name' => $user->name,
                'mobile' => $user->mobile,
                'date_time' => Carbon::now()

            ]);
            $rewardTransactionsToReferredUser = RewardTransaction::create([
                'points' => $isNewUser ? $setting['refer_earn_new_user_rewards'] : $setting['refer_earn_old_user_rewards'],
                'reward_id' =>  $rewardToReferredUser->id,
                'referal_id' => null,
                'user_id' => $referred_user_id,

                'transaction_type' => 'credit',
                'status' => 'Pending',
                'remark' => $user->name . ' refer to you',
                'authenticated_user_id' => $user->id,
                'authenticated_user_role' => 'user',
                'name' => $refered->name,
                'mobile' => $refered->mobile,
                'date_time' => Carbon::now()

            ]);

            $rewardToReferringUser->points = $rewardToReferringUser->points + $rewardTransactionsToReferringUser->points;
            $rewardToReferringUser->save();

            $rewardToReferredUser->points = $rewardToReferredUser->points + $rewardTransactionsToReferredUser->points;
            $rewardToReferredUser->save();

            dispatch(new ReferEarnJob(referred_user_id: $referred_user_id, referred_user_reward: $rewardTransactionsToReferredUser->points, referring_user_reward: $rewardTransactionsToReferringUser->points, referring_user_id: $referring_user_id))->afterResponse();

            if ($isNewUser) {
                SpecialDiscount::create([
                    'date_of_allocation' => Carbon::now('Asia/Kolkata')->format('Y-m-d H:i:s'),
                    'refering_user_id' => $referring_user_id,
                    'user_id' => $referred_user_id,
                    'is_used' => 0,
                    'is_valid' => 0,
                    'discount' => $setting['refer_earn_refered_discount_first_order'] ?? 50,
                    'used_date' => null,
                    'sell_id' => null,
                    'mobile' => null,
                    'discount_index' => 1,
                ]);
                SpecialDiscount::create([
                    'date_of_allocation' => Carbon::now('Asia/Kolkata')->format('Y-m-d H:i:s'),
                    'refering_user_id' => $referring_user_id,
                    'user_id' => $referred_user_id,
                    'is_used' => 0,
                    'is_valid' => 0,
                    'discount' => $setting['refer_earn_refered_discount_second_order'] ?? 25,
                    'used_date' => null,
                    'sell_id' => null,
                    'mobile' => null,
                    'discount_index' => 2,
                ]);
                SpecialDiscount::create([
                    'date_of_allocation' => Carbon::now('Asia/Kolkata')->format('Y-m-d H:i:s'),
                    'refering_user_id' => $referring_user_id,
                    'user_id' => $referred_user_id,
                    'is_used' => 0,
                    'is_valid' => 0,
                    'discount' => $setting['refer_earn_refered_discount_third_order'] ?? 10,
                    'used_date' => null,
                    'sell_id' => null,
                    'mobile' => null,
                    'discount_index' => 3,
                ]);

                dispatch(new ReferEarnJob(referred_user_id: $referred_user_id, referred_user_reward: $rewardTransactionsToReferredUser->points, referring_user_reward: $rewardTransactionsToReferringUser->points, referring_user_id: $referring_user_id, type: 'special'))->afterResponse();
            }


        } catch (Exception $e) {
            Log::channel('callvcal')->info("ReferalTransactionController error " . $e);
            Log::channel('callvcal')->error("Stack trace: " . $e->getTraceAsString());
            Log::channel('callvcal')->error("Error in SendMessage.handle: " . $e->getMessage());
            Log::channel('callvcal')->error("Error code: " . $e->getCode());
            Log::channel('callvcal')->error("File: " . $e->getFile());
            Log::channel('callvcal')->error("Line: " . $e->getLine());
            Log::channel('callvcal')->error("Stack trace: " . $e->getTraceAsString());

            // return response([
            //     'getTraceAsString'=>$e->getTraceAsString(),
            //     'params'=>$params,
            //     'setting'=>$setting

            // ]);

        }
        // return response([
        //     'transaction'=>$transaction,
        //     'params'=>$params,
        //     'refer'=>$refer,
        //     'rewardToReferringUser'=>$rewardToReferringUser,
        //     'rewardToReferredUser'=>$rewardToReferredUser
        // ]);
    }
}
